<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $user_id = $_SESSION['id'];
    $order_id = (int)$_POST['order_id'];

    // Step 1: Verify the order belongs to the user and is cancelable
    $verify = $conn->prepare("SELECT id, status FROM orders WHERE id = ? AND user_id = ?");
    $verify->bind_param("ii", $order_id, $user_id);
    $verify->execute();
    $order = $verify->get_result()->fetch_assoc();

    if (!$order) {
        $_SESSION['error'] = "Order not found or access denied.";
        header("Location: my_orders.php");
        exit();
    }

    if ($order['status'] === 'Cancelled' || $order['status'] === 'Delivered') {
        $_SESSION['error'] = "Order cannot be cancelled.";
        header("Location: my_orders.php");
        exit();
    }

    // Step 2: Get order items
    $items = $conn->prepare("SELECT product_id, quantity FROM order_items WHERE order_id = ?");
    $items->bind_param("i", $order_id);
    $items->execute();
    $result = $items->get_result();

    // Step 3: Restore stock
    while ($row = $result->fetch_assoc()) {
        $updateStock = $conn->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
        $updateStock->bind_param("ii", $row['quantity'], $row['product_id']);
        $updateStock->execute();
    }

    // Step 4: Cancel the order
    $cancel = $conn->prepare("UPDATE orders SET status = 'Cancelled' WHERE id = ?");
    $cancel->bind_param("i", $order_id);
    if ($cancel->execute()) {
        $_SESSION['success'] = "Order cancelled successfully.";
    } else {
        $_SESSION['error'] = "Failed to cancel order.";
    }

    header("Location: my_orders.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: my_orders.php");
    exit();
}
