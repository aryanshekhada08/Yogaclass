<?php
session_start();
include '../db.php';

if (!isset($_GET['order_id']) || !isset($_SESSION['id'])) {
    header("Location: shop.php");
    exit();
}

$orderId = intval($_GET['order_id']);
$userId = $_SESSION['id'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $orderId, $userId);
$stmt->execute();
$orderResult = $stmt->get_result();
$order = $orderResult->fetch_assoc();
$stmt->close();

$stmt = $conn->prepare("
    SELECT oi.*, p.name AS product_name 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$itemsResult = $stmt->get_result();
$stmt->close();

// Send Confirmation Mail
$to = $order['email'];
$subject = "Your Order is Confirmed - YogaClass";
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type:text/html;charset=UTF-8\r\n";
$headers .= "From: YogaClass <no-reply@yogaclass.com>\r\n";

$message = "<h2>Thank you, " . htmlspecialchars($order['name']) . "!</h2>";
$message .= "<p>Your order has been successfully placed.</p>";
$message .= "<p><strong>Order ID:</strong> #$orderId</p>";
$message .= "<p><strong>Total:</strong> ₹" . number_format($order['total_price'], 2) . "</p>";
$message .= "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width: 100%; font-family: Arial, sans-serif;'>
<tr><th>Product</th><th>Qty</th><th>Price</th></tr>";

$total = 0;
while ($item = $itemsResult->fetch_assoc()) {
    $message .= "<tr>
        <td>" . htmlspecialchars($item['product_name']) . "</td>
        <td>" . (int)$item['quantity'] . "</td>
        <td>₹" . number_format($item['price'], 2) . "</td>
    </tr>";
    $total += $item['price'] * $item['quantity'];
}
$message .= "<tr>
    <td colspan='2' align='right'><strong>Total</strong></td>
    <td><strong>₹" . number_format($total, 2) . "</strong></td>
</tr>
</table>";
$message .= "<p>We’ll notify you when your order is shipped.</p><br><strong>~ YogaClass Team</strong>";
mail($to, $subject, $message, $headers);

// Reset result pointer
mysqli_data_seek($itemsResult, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Confirmed</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9f9f9;
      color: #333;
      padding: 30px;
    }
    .confirmation-box {
      background: white;
      padding: 30px;
      max-width: 600px;
      margin: auto;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .confirmation-box img {
      width: 120px;
      margin-bottom: 20px;
    }
    h2 {
      color: #2e8b57;
    }
    p {
      font-size: 15px;
    }
    .btn {
      margin-top: 20px;
      padding: 10px 25px;
      background-color: #2e8b57;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
    }
    .btn:hover {
      background-color: #256b49;
    }
  </style>
</head>
<body>

<div class="confirmation-box">
  <img src="https://i.gifer.com/7efs.gif" alt="Order Confirmed">
  <h2>Order Confirmed!</h2>
  <p>Thank you <strong><?= htmlspecialchars($order['name']) ?></strong> for your order.</p>
  <p><strong>Order ID:</strong> #<?= $orderId ?><br>
     <strong>Total:</strong> ₹<?= number_format($order['total_price'], 2) ?></p>
  <p>A confirmation email has been sent to <strong><?= htmlspecialchars($order['email']) ?></strong>.</p>
  <a class="btn" href="../shop.php">🛒 Continue Shopping</a>
</div>

</body>
</html>
