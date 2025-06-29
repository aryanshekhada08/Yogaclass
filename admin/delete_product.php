<?php
session_start();
include '../db.php';

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Prepare delete statement
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        // Redirect back to the product list
        header("Location: view_product.php?deleted=success");
        exit();
    } else {
        echo "<p style='color:red; text-align:center;'>Failed to delete product.</p>";
    }
} else {
    echo "<p style='color:red; text-align:center;'>Invalid request.</p>";
}
?>
