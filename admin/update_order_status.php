<?php
include '../db.php';
include '../send_mail.php';
// include './product/generate_invoice.php'; // You'll create this
require_once '../product/generate_invoice.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = (int)$_POST['order_id'];
    $status = $_POST['status'];

    // Step 1: Get user's email
    $stmt = $conn->prepare("SELECT o.email, u.username FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $email = $order['email'];
    $username = $order['username'];

    // Step 2: Update order status
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();

    // Step 3: Handle email based on status
    if (strtolower($status) === 'delivered') {
        // Generate invoice
        $invoicePath = generateInvoice($order_id);
        if (!$invoicePath) {
            die("Failed to generate invoice.");
        }
        // Send final delivery email with invoice and return info
        $subject = "Your Order #$order_id Has Been Delivered 🎉";
        $message = "Hello $username,\n\nYour order #$order_id has been successfully delivered.\n\n"
                 . "We've attached your invoice with this email.\n\n"
                 . "**Return Policy**: If you're not fully satisfied, you can return the product within 10 days from the delivery date.\n\n"
                 . "Thank you for choosing YogaClass!\n\nStay fit and healthy! 🧘‍♀️";

        sendEmailWithAttachment($email, $subject, $message, $invoicePath);
    } else {
        // Generic status update mail
        $subject = "Order #$order_id Status Update";
        $message = "Hello $username,\n\nYour order status is now: " . strtoupper($status) . ".\n\n"
                 . "We'll keep you updated. Thank you!";
        $headers = "From: no-reply@yogaclass.com";

        mail($email, $subject, $message, $headers);
    }

    header("Location: admin_orders.php?status_updated=1");
    exit();
}
?>
