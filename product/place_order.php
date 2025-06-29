
<?php
session_start();
include '../db.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    exit();
}

$user_id = $_SESSION['id'] ?? 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect address details
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $pincode = trim($_POST['pincode']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);

    // Combine full address
    $full_address = "$address, $city, $state - $pincode";

    // Loop through cart and insert each item
    foreach ($_SESSION['cart'] as $item) {
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];
    // Process order logic here...

        if ($quantity >= 1) {
            $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity, total_price, name, phone, email, shipping_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiidssss", $user_id, $product_id, $quantity, $total_price, $name, $phone, $email, $full_address);
            $stmt->execute();

             $stockUpdate = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");
             $stockUpdate->bind_param("iii", $quantity, $product_id, $quantity);
             $stockUpdate->execute();
        }
    }

    unset($_SESSION['cart']); // Clear cart

    echo "<h2>✅ Order placed successfully!</h2>";
    echo "<a href='shop.php'>Back to Shop</a>";
} else {
    echo "<p>Error: Invalid request method.</p>";
}
?>
