<?php
session_start();
include 'db.php';
include 'Navbar.php'; 


// Fetch all products
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Yoga Shop</title>
    <link rel="stylesheet" href="SubStyle/auth.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f3fbf8;
            margin: 0;
            padding: 0;
        }

        .shop-container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 2rem;
        }

        h2 {
            text-align: center;
            color: #2e8b72;
            margin-bottom: 2rem;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            text-align: center;
        }

        .product-card img {
            max-width: 100%;
            border-radius: 10px;
            height: 200px;
            object-fit: cover;
        }

        .product-name {
            font-size: 1.2rem;
            margin: 10px 0;
            color: #333;
        }

        .product-price {
            color: #2e8b72;
            font-weight: bold;
        }

        .product-actions {
            margin-top: 10px;
        }

        .product-actions a {
            text-decoration: none;
            background: #2e8b72;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .product-actions a:hover {
            background: #256f5e;
        }
    </style>
</head>
<body>
<div class="shop-container">
    <h2>Welcome to the Yoga Shop</h2>
    <div class="product-grid">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                    <div class="product-name"><?= htmlspecialchars($row['name']) ?></div>
                    <div class="product-price">$<?= number_format($row['price'], 2) ?></div>
                    <div class="product-actions">
                        <a href="product_details.php?id=<?= $row['id'] ?>">View</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products available.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
<?php include 'Footer.php'; ?>