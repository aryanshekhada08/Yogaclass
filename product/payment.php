<?php
session_start();
include '../db.php';
include 'p_nav.php';

if (!isset($_SESSION['id']) || !isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: ../shop.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];
    $city = $_POST['city'];
    $state = $_POST['state'];

    // Combine full shipping address
    $shipping_address = "$address, $city, $state - $pincode";

    // Calculate total price
    $total_price = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $total_price += $row['price'] * $quantity;
        }
        $stmt->close();
    }

    // Insert into orders table
    $stmt = $conn->prepare("INSERT INTO orders (user_id,total_price, name, phone, email, shipping_address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("idssss", $userId, $total_price, $name, $phone, $email, $shipping_address);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Insert into order_items
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $price = $row['price'];
                $itemStmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $itemStmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
                $itemStmt->execute();
                $itemStmt->close();
            }
            $stmt->close();
        }

        // Clear cart
        unset($_SESSION['cart']);

        // Redirect to order success page
         $_SESSION['message'] = "Your order has been placed successfully!";
        // header("Location: order_success.php?order_id=".$order_id);
        header("Location: order_success.php?order_id=$order_id");
        exit();
    } else {
        $_SESSION['message'] = "Failed to place order. Please try again.";
        header("Location: delivery_address.php");
        exit();
    }
} else {
    header("Location:address.php");
    exit();
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Payment Method</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 1rem;
            background: #f3fbf8;
        }

        .payment-container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            color: #2e8b72;
            margin-bottom: 2rem;
            font-size: 1.5rem;
            text-align: center;
        }

        .payment-option {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .option {
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .option img {
            height: 40px;
        }

        .option input {
            accent-color: #2e8b72;
        }

        .option:hover, .option input:checked + label {
            border-color: #2e8b72;
            background-color: #e6f7f2;
        }

        .option label {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 16px;
            color: #333;
            cursor: pointer;
        }

        .pay-btn {
            background-color: #2e8b72;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s ease;
        }

        .pay-btn:hover {
            background-color: #256b59;
        }

        @media (max-width: 600px) {
            .option {
                flex-direction: row;
            }
        }
    </style>
</head>
<body>

<form class="payment-container" method="POST" action="order_confirm.php">
    <h2>Select Payment Method</h2>

    <div class="payment-option">

        <div class="option">
            <input type="radio" name="payment" id="upi" value="UPI" required>
            <label for="upi">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fc/UPI-Logo-vector.svg/512px-UPI-Logo-vector.svg.png" alt="UPI">
                UPI
            </label>
        </div>

        <div class="option">
            <input type="radio" name="payment" id="cod" value="Cash on Delivery">
            <label for="cod">
                <img src="https://cdn-icons-png.flaticon.com/512/483/483356.png" alt="COD">
                Cash on Delivery
            </label>
        </div>

        <div class="option">
            <input type="radio" name="payment" id="debit" value="Debit Card">
            <label for="debit">
                <img src="https://cdn-icons-png.flaticon.com/512/196/196561.png" alt="Debit Card">
                Debit Card
            </label>
        </div>

        <div class="option">
            <input type="radio" name="payment" id="credit" value="Credit Card">
            <label for="credit">
                <img src="https://cdn-icons-png.flaticon.com/512/633/633611.png" alt="Credit Card">
                Credit Card
            </label>
        </div>

    </div>

    <button class="pay-btn" type="submit">Make Payment</button>
</form>

</body>
</html>
<?php include '../footer.php'; ?>
