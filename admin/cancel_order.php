<?php
include '../db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = (int)$_POST['order_id'];

    // Update status to Cancelled
    $stmt = $conn->prepare("UPDATE orders SET status = 'Cancelled' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Order marked as Cancelled.";
    } else {
        $_SESSION['error'] = "Failed to cancel order.";
    }
} else {
    $_SESSION['error'] = "Invalid request.";
}

header("Location: admin_orders.php");
exit;
