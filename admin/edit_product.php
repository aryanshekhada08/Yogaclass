<?php
session_start();
include '../db.php';
include 'admin_navbar.php';

// Redirect if no product ID
if (!isset($_GET['id'])) {
    header("Location: view_product.php");
    exit();
}

$id = intval($_GET['id']);
$message = "";

// Fetch existing product
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<p style='color:red; text-align:center;'>Product not found.</p>";
    exit();
}

// Update product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $image = trim($_POST['image']);

    $update = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
    $update->bind_param("ssdsi", $name, $description, $price, $image, $id);

    if ($update->execute()) {
        $message = "<p style='color:green;'>Product updated successfully!</p>";
        $product['name'] = $name;
        $product['description'] = $description;
        $product['price'] = $price;
        $product['image'] = $image;
    } else {
        $message = "<p style='color:red;'>Failed to update product.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product - Yoga Admin</title>
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
            margin-bottom: 2rem;
        }
        input[type="text"], textarea, input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        input[type="submit"] {
            background-color: #2e8b72;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #256c5a;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Product</h2>
    <?= $message ?>
    <form action="" method="POST">
        <input type="text" name="name" placeholder="Product Name" value="<?= htmlspecialchars($product['name']) ?>" required>
        <textarea name="description" placeholder="Product Description" required><?= htmlspecialchars($product['description']) ?></textarea>
        <input type="number" name="price" step="0.01" placeholder="Price" value="<?= htmlspecialchars($product['price']) ?>" required>
        <input type="text" name="image" placeholder="Image URL" value="<?= htmlspecialchars($product['image']) ?>" required>
        <input type="submit" value="Update Product">
    </form>
</div>
</body>
</html>
