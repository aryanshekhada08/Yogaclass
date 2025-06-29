<?php
// mark_as_delivered.php

require '../db.php'; // your DB connection
require 'generate_invoice.php'; // your custom invoice generator
require '../send_mail.php'; // your email sending function (using PHPMailer or similar)

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Update status in database
    $stmt = $conn->prepare("UPDATE orders SET status = 'Delivered' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    if ($stmt->execute()) {
        // Fetch order & user details
        $stmt = $conn->prepare("SELECT o.*, u.email, u.name FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();

        // Generate invoice PDF
        $pdfPath = generateInvoice($order_id); // This should return full path to saved PDF

        // Send email to user
        $subject = "Your Invoice for Order #$order_id";
        $message = "Hello " . htmlspecialchars($order['name']) . ",\n\nYour order has been delivered. Please find the invoice attached.\n\nThank you!";
        sendEmailWithAttachment($order['email'], $subject, $message, $pdfPath);

        header("Location: orders_admin.php?success=delivered");
        exit();
    } else {
        echo "Failed to update order.";
    }
}
?>
