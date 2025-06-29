\<?php
session_start();
include 'db.php';
include 'Navbar.php';
// Check if user is logged in

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$message = "";

if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
}

// Handle add to cart
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if ($quantity < 1) {
        $message = "<p class='error-msg'>Quantity must be at least 1.</p>";
    } else {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
        $message = "<p class='success-msg'>Added to cart successfully!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - Product Details</title>
    <link rel="stylesheet" href="SubStyle/auth.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .product-container {
            max-width: 1200px;
            margin: 5rem auto;
            padding: 3rem;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 2rem;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            transition: transform 0.3s ease;
        }

        .product-container:hover {
            transform: translateY(-10px);
        }

        img {
            max-width: 350px;
            width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }

        .product-info {
            flex: 1;
            max-width: 550px;
        }

        h2 {
            color: #2e8b72;
            font-size: 2rem;
            margin-bottom: 1rem;
            font-weight: bold;
        }

        p {
            font-size: 1rem;
            line-height: 1.6;
            color: #555;
            margin-bottom: 1rem;
        }

        .price {
            color: #333;
            font-weight: bold;
            font-size: 1.4rem;
            margin-bottom: 2rem;
        }

        form {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        input[type="number"] {
            width: 80px;
            padding: 10px;
            font-size: 1rem;
            border-radius: 8px;
            border: 1px solid #ccc;
            transition: all 0.3s ease;
        }

        input[type="number"]:focus {
            border-color: #2e8b72;
            outline: none;
        }

        input[type="submit"] {
            background-color: #2e8b72;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        input[type="submit"]:hover {
            background-color: #256b59;
            transform: scale(1.05);
        }

        .success-msg, .error-msg {
            margin-top: 20px;
            font-weight: bold;
            font-size: 1rem;
        }

        .success-msg {
            color: green;
        }

        .error-msg {
            color: red;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            font-size: 1.1rem;
            color: #2e8b72;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #256b59;
            text-decoration: underline;
        }

        /* Media Queries for Mobile Devices */
        @media (max-width: 768px) {
            .product-container {
                flex-direction: column;
                padding: 1.5rem;
                align-items: center;
            }

            .product-info {
                text-align: center;
            }

            img {
                max-width: 100%;
            }

            .price {
                font-size: 1.2rem;
            }

            input[type="number"] {
                width: 60px;
            }

            input[type="submit"] {
                width: 100%;
                margin-top: 10px;
            }
        }

        @media (max-width: 480px) {
            h2 {
                font-size: 1.8rem;
            }

            .price {
                font-size: 1rem;
            }

            .back-link {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<div class="product-container">
    <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
    <div class="product-info">
        <h2><?= htmlspecialchars($product['name']) ?></h2>
        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        <p class="price">$<?= number_format($product['price'], 2) ?></p>

        <form action="" method="POST">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <label for="quantity" style="font-weight: bold;">Quantity:</label>
            <input type="number" name="quantity" id="quantity" min="1" value="1" required>
            <input type="submit" value="Add to Cart">
        </form>

        <?= $message ?>

        <a href="shop.php" class="back-link">&#8592; Back to Shop</a>
    </div>
</div>

</body>
</html>
<?php
      include 'Footer.php'; ?>