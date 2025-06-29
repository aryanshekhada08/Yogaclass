<?php
session_start();
include 'db.php';
include 'Navbar.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['id'];

$sql = "SELECT o.id as order_id, o.status, o.order_date, oi.product_id, oi.quantity, p.name, p.image, p.price 
        FROM orders o 
        JOIN order_items oi ON o.id = oi.order_id 
        JOIN products p ON p.id = oi.product_id 
        WHERE o.user_id = ? 
        ORDER BY o.order_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[$row['order_id']]['status'] = $row['status'];
    $orders[$row['order_id']]['order_date'] = $row['order_date'];
    $orders[$row['order_id']]['items'][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4e73df;
            --success: #2ecc71;
            --danger: #e74c3c;
            --light: #f1f3f8;
            --dark: #2c3e50;
            --card-bg: #ffffff;
            --shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            --radius: 12px;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: var(--light);
            margin: 0;
            padding: 10px;
            color: var(--dark);
        }

        h2 {
            text-align: center;
            color: var(--primary);
            margin-bottom: 30px;
        }

        .order-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 30px;
            padding: 20px;
            transition: 0.3s ease-in-out;
        }

        .order-card:hover {
            transform: translateY(-5px);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .order-header h3 {
            margin: 0;
            font-size: 20px;
        }

        .order-header span {
            font-size: 14px;
            color: gray;
        }

        .product-item {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #f9f9f9;
            border-radius: var(--radius);
            padding: 12px;
            margin-bottom: 12px;
        }

        .product-item img {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
        }

        .product-details h4 {
            margin: 0 0 4px;
            font-size: 16px;
        }

        .product-details p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }

        .cancel-btn {
            background: var(--danger);
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
            transition: 0.3s;
        }

        .cancel-btn:hover {
            background: #c0392b;
        }

        .order-total {
            font-weight: bold;
            margin-top: 10px;
            color: var(--dark);
        }

        @media (max-width: 600px) {
            .product-item {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<h2>🧾 My Orders</h2>

<?php
if (isset($_SESSION['success'])) {
    echo "<p style='color: green; text-align:center;'>" . $_SESSION['success'] . "</p>";
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo "<p style='color: red; text-align:center;'>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']);
}
?>

<?php if (!empty($orders)): ?>
    <?php foreach ($orders as $order_id => $order): 
        $status = $order['status'];
        $order_date = $order['order_date'];

        switch ($status) {
            case 'Pending': $icon = '⏳'; break;
            case 'Processing': $icon = '🔄'; break;
            case 'Shipped': $icon = '🚚'; break;
            case 'Out for Delivery': $icon = '📬'; break;
            case 'Delivered': $icon = '✅'; break;
            case 'Cancelled': $icon = '🛑'; break;
            case 'Failed': $icon = '❌'; break;
            default: $icon = '📦';
        }

        $total = 0;
        foreach ($order['items'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    ?>
    <div class="order-card">
        <div class="order-header">
            <h3><?= $icon ?> Order #<?= $order_id ?></h3>
            <span><?= htmlspecialchars($status) ?> | <?= $order_date ?></span>
        </div>

        <?php foreach ($order['items'] as $item): ?>
        <div class="product-item">
            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
            <div class="product-details">
                <h4><?= htmlspecialchars($item['name']) ?></h4>
                <p>Quantity: <?= $item['quantity'] ?></p>
                <p>Price: ₹<?= number_format($item['price'], 2) ?></p>
            </div>
        </div>
        <?php endforeach; ?>

        <p class="order-total">Total: ₹<?= number_format($total, 2) ?></p>

        <?php if ($status !== 'Cancelled' && $status !== 'Delivered'): ?>
            <form action="cancel_user_order.php" method="POST" onsubmit="return confirm('Cancel this order?');">
                <input type="hidden" name="order_id" value="<?= $order_id ?>">
                <button class="cancel-btn" type="submit">❌ Cancel Order</button>
            </form>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <p style="text-align:center;">You haven't placed any orders yet.</p>
<?php endif; ?>

</body>
</html>
