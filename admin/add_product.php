<?php
session_start();
include '../db.php';
include 'admin_navbar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['product_name']);
    $price = floatval($_POST['product_price']);
    $description = trim($_POST['product_description']);
    $image_url = trim($_POST['product_image_url']);

    if (!empty($image_url) && filter_var($image_url, FILTER_VALIDATE_URL)) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssds", $name, $description, $price, $image_url);
        if ($stmt->execute()) {
            $success = "Product added successfully!";
        } else {
            $error = "Database error: Unable to add product.";
        }
    } else {
        $error = "Please enter a valid image URL.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Product - Yoga Admin</title>
    <link rel="stylesheet" href="SubStyle/auth.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f3fbf8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 3rem auto;
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2e8b72;
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin: 1rem 0 0.3rem;
            font-weight: bold;
            color: #333;
        }

        input, textarea {
            width: 100%;
            padding: 0.7rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        textarea {
            resize: vertical;
        }

        button {
            background-color: #2e8b72;
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #256f5c;
        }

        .message {
            text-align: center;
            padding: 0.5rem;
            font-weight: bold;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Product</h2>
    <?php if (!empty($success)) echo '<div class="message success">' . $success . '</div>'; ?>
    <?php if (!empty($error)) echo '<div class="message error">' . $error . '</div>'; ?>

    <form action="" method="POST">
        <label for="product_name">Product Name</label>
        <input type="text" id="product_name" name="product_name" placeholder="Enter product name" required>

        <label for="product_price">Price ($)</label>
        <input type="number" id="product_price" name="product_price" step="0.01" placeholder="Enter price" required>

        <label for="product_image_url">Product Image URL</label>
        <input type="url" id="product_image_url" name="product_image_url" placeholder="https://example.com/image.jpg" required>

        <label for="product_description">Description</label>
        <textarea id="product_description" name="product_description" rows="4" placeholder="Enter product details"></textarea>

        <button type="submit">Add Product</button>
    </form>
</div>

</body>
</html>
