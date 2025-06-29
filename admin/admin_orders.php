<?php
include '../db.php';
session_start();
 include 'admin_navbar.php';
// Fetch all orders with product & user info
$query = "
    SELECT 
        o.id AS order_id,
        o.order_date,
        o.status,
        oi.quantity,
        p.name AS product_name,
        p.price,
        u.username AS user_name,
        u.email
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    JOIN users u ON o.user_id = u.id
    ORDER BY o.order_date DESC
";

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Order Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f8f8f8;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 10px 8px;
            border: 1px solid #ccc;
            text-align: center;
            font-size: 14px;
        }

        th {
            background: #e9e9e9;
        }

        .cancel-btn {
            padding: 6px 12px;
            background: crimson;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .cancel-btn:hover {
            background: darkred;
        }

        .cancelled-row {
            background-color: #ffe6e6;
            color: #a10000;
            font-weight: bold;
        }

        select {
            padding: 5px;
        }

        @media screen and (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead tr {
                display: none;
            }

            td {
                position: relative;
                padding-left: 50%;
                text-align: left;
                border: none;
                border-bottom: 1px solid #ddd;
            }

            td:before {
                position: absolute;
                top: 10px;
                left: 10px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: bold;
            }

            td:nth-of-type(1):before { content: "Order ID"; }
            td:nth-of-type(2):before { content: "Date"; }
            td:nth-of-type(3):before { content: "User"; }
            td:nth-of-type(4):before { content: "Email"; }
            td:nth-of-type(5):before { content: "Product"; }
            td:nth-of-type(6):before { content: "Qty"; }
            td:nth-of-type(7):before { content: "Price"; }
            td:nth-of-type(8):before { content: "Status"; }
            td:nth-of-type(9):before { content: "Cancel"; }
        }
    </style>
</head>
<body>

<h2>🛒 Admin Order Management</h2>

<table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>User</th>
            <th>Email</th>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Status</th>
            <th>Cancel</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="<?= strtolower($row['status']) === 'cancelled' ? 'cancelled-row' : '' ?>">
                    <td><?= $row['order_id'] ?></td>
                    <td><?= $row['order_date'] ?></td>
                    <td><?= htmlspecialchars($row['user_name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td>$<?= number_format($row['price'], 2) ?></td>
                    <td>
                        <form method="POST" action="update_order_status.php">
                            <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                            <select name="status" onchange="this.form.submit()">
                                <option value="pending" <?= strtolower($row['status']) == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="processing" <?= strtolower($row['status']) == 'processing' ? 'selected' : '' ?>>Processing</option>
                                <option value="shipped" <?= strtolower($row['status']) == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="delivered" <?= strtolower($row['status']) == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="cancelled" <?= strtolower($row['status']) == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <?php if (strtolower($row['status']) !== 'cancelled'): ?>
                        <form action="cancel_order.php" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                            <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                            <button type="submit" class="cancel-btn">Cancel</button>
                        </form>
                        <?php else: ?>
                            Cancelled
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="9">No orders found.</td></tr>
        <?php endif; ?>
        
    </tbody>
</table>
<!-- <?php foreach ($orders as $order_id => $order): ?>
<div class="order-card" style="border:1px solid #ccc; padding:20px; margin-bottom:20px;">
    <h3>Order ID: <?= $order_id ?></h3>
    <p><strong>Date:</strong> <?= $order['order_date'] ?></p>
    <p><strong>Status:</strong> <?= $order['status'] ?></p>
    <p><strong>User:</strong> <?= $order['user_name'] ?> (<?= $order['user_email'] ?>)</p>

    <h4>Items:</h4>
    <ul>
        <?php foreach ($order['items'] as $item): ?>
        <li>
            <?= $item['product_name'] ?> - <?= $item['quantity'] ?> x ₹<?= $item['price'] ?> = ₹<?= $item['quantity'] * $item['price'] ?>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endforeach; ?> -->

</body>
</html>
