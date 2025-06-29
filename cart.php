<?php
session_start();
include 'db.php';
include 'Navbar.php';
// Initialize cart as associative array with quantity tracking
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle update quantity
if (isset($_POST['update'])) {
    foreach ($_POST['quantities'] as $product_id => $qty) {
        $qty = (int)$qty;
        if ($qty >= 1) {
            $_SESSION['cart'][$product_id] = $qty;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    header("Location: cart.php");
    exit();
}

// Handle remove from cart
if (isset($_GET['remove'])) {
    $product_id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php");
    exit();
}

$cart_items = [];
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $row['quantity'] = $_SESSION['cart'][$row['id']];
        $row['subtotal'] = $row['price'] * $row['quantity'];
        $cart_items[] = $row;
        $total_price += $row['subtotal'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="SubStyle/auth.css">
  <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f3fbf8;
        margin: 0;
        padding: 0;
    }

    .cart-container {
        max-width: 1000px;
        margin: 3rem auto;
        padding: 2rem;
        background: #ffffff;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #2e8b72;
        margin-bottom: 2rem;
    }

    /* Desktop Table */
    .cart-table {
        width: 100%;
        border-collapse: collapse;
        display: table;
    }

    .cart-table th, .cart-table td {
        padding: 1rem;
        border-bottom: 1px solid #ddd;
        text-align: center;
    }

    .cart-table th {
        background-color: #e8f6f1;
        color: #2e8b72;
    }

    .product-img {
        width: 80px;
        border-radius: 10px;
    }

    .remove-btn, .update-btn {
        background: #ff5c5c;
        color: white;
        padding: 6px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
    }

    .update-btn {
        background-color: #2e8b72;
    }

    .remove-btn:hover {
        background: #e64545;
    }

    .update-btn:hover {
        background-color: #256b59;
    }

    .total {
        text-align: right;
        font-size: 1.4rem;
        font-weight: bold;
        color: #2e8b72;
        margin-top: 1rem;
    }

    .empty-cart {
        text-align: center;
        color: #666;
        font-size: 1.2rem;
        margin-top: 15rem;
    }

    .back-btn {
        display: inline-block;
        background-color: #2e8b72;
        color: #fff;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 8px;
        margin-top: 20px;
    }

    .back-btn:hover {
        background-color: #256b59;
    }
    /* Default: show desktop table, hide mobile layout */
.cart-table {
    display: table;
}

.mobile-cart-item {
    display: none;
}
.checkout-btn {
    background-color: #ff9800;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    font-weight: bold;
    border-radius: 8px;
    transition: background 0.3s ease;
}

.checkout-btn:hover {
    background-color: #e68900;
}

/* Mobile View (max-width 768px) */
@media (max-width: 768px) {
    .cart-table {
        display: none;
    }

    .mobile-cart-item {
        display: flex;
    }
}

    /* Mobile-friendly styles */
    @media (max-width: 768px) {
        .cart-table {
            display: none; /* Hide desktop table on mobile */
        }

        .mobile-cart-item {
            background-color: #f9f9f9;
            margin-bottom: 1rem;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .mobile-cart-item img {
            width: 100px;
            border-radius: 10px;
            margin: 0 auto;
        }

        .mobile-cart-item h4 {
            margin: 0;
            text-align: center;
            color: #2e8b72;
        }

        .mobile-cart-info {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            font-size: 0.95rem;
        }

        .mobile-cart-info div {
            margin: 5px 0;
        }

        .mobile-cart-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mobile-cart-actions input[type="number"] {
            width: 60px;
            padding: 5px;
        }

        .remove-btn, .update-btn {
            padding: 6px 10px;
            font-size: 0.85rem;
        }

        .total {
            text-align: center;
            font-size: 1.2rem;
            margin-top: 2rem;
        }

        .back-btn {
            width: 100%;
            text-align: center;
        }
    }
</style>

</head>
<body>

<?php if (!empty($cart_items)): ?>
    <form method="POST">
        <!-- Desktop Table -->
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                 <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><img class="product-img" src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>"></td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td>
                            <input type="number" name="quantities[<?= $item['id'] ?>]" value="<?= $item['quantity'] ?>" min="1" style="width:60px; padding:5px;">
                        </td>
                        <td>$<?= number_format($item['subtotal'], 2) ?></td>
                        <td><a href="cart.php?remove=<?= $item['id'] ?>" class="remove-btn">Remove</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Mobile Layout -->
        <?php foreach ($cart_items as $item): ?>
            <div class="mobile-cart-item">
                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                <h4><?= htmlspecialchars($item['name']) ?></h4>
                <div class="mobile-cart-info">
                    <div>Price: $<?= number_format($item['price'], 2) ?></div>
                    <div>Subtotal: $<?= number_format($item['subtotal'], 2) ?></div>
                </div>
                <div class="mobile-cart-actions">
                    <input type="number" name="quantities[<?= $item['id'] ?>]" value="<?= $item['quantity'] ?>" min="1">
                    <a href="cart.php?remove=<?= $item['id'] ?>" class="remove-btn">Remove</a>
                </div>
            </div>
        <?php endforeach; ?>

        <div style="text-align: right; margin-top: 10px;">
            <input type="submit" name="update" value="Update Cart" class="update-btn">
        </div>
    </form>
   <div class="total">
    Total: $<?= number_format($total_price, 2) ?>
</div>

<div style="text-align: right; margin-top: 1.5rem;">
    <a href="./product/address.php" class="checkout-btn">🛒 Proceed to Checkout</a>
</div>

<?php else: ?>
    <p class="empty-cart">Your cart is empty.</p>
<?php endif; ?>

<div style="text-align: center;">
    <a href="shop.php" class="back-btn">&#8592; Back to Shop</a>
</div>

</body>
</html>
