<?php
// update_status.php
include '../db.php';
include '../send_mail.php';   // our PHPMailer wrapper
session_start();

if (isset($_GET['id'])) {
    $booking_id = (int)$_GET['id'];

    // 1. Fetch booking + user info
    $stmt = $conn->prepare("
        SELECT b.*, s.title AS service_name, u.email, u.username 
        FROM bookings b
        JOIN services s ON b.service_id = s.id
        JOIN users u    ON b.user_id    = u.id
        WHERE b.id = ?
    ");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();

    if ($booking) {
        // 2. Update status to 'completed'
        $stmt = $conn->prepare("UPDATE bookings SET status = 'completed' WHERE id = ?");
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();

        // 3. Send completion email
        $to      = $booking['email'];
        $name    = $booking['name'];
        $service = $booking['service_name'];
        $date    = date("F j, Y", strtotime($booking['date']));
        
        $subject = "Your $service Booking Is Completed 🎉";
        
        // Build a clean, professional HTML email
        $body  = "<html><body style='font-family:Arial,sans-serif; line-height:1.6; color:#333'>";
        $body .= "<h2 style='color:#00796b;'>Hello, " . htmlspecialchars($name) . "</h2>";
        $body .= "<p>We’re happy to let you know that your booking for <strong>" 
               . htmlspecialchars($service) . "</strong> on <strong>$date</strong> has been <span style='color:#2ecc71;'>completed</span>.</p>";
        $body .= "<p>Thank you for choosing <strong>YogaClass</strong>! We hope you enjoyed your session and look forward to seeing you again soon.</p>";
        $body .= "<hr style='border:none; border-top:1px solid #eee'>";
        $body .= "<p style='font-size:0.9em; color:#777;'>If you have any feedback or need to book again, feel free to reply to this email or visit our <a href='https://yourdomain.com/services'>Services page</a>.</p>";
        $body .= "<p style='font-size:0.9em; color:#777;'>Namaste,<br>The YogaClass Team 🧘‍♀️</p>";
        $body .= "</body></html>";

        sendConfirmationMail($to, $subject, $body, true);  // true = send as HTML

        header("Location: booking_list.php?updated=1");
        exit;
    }
}
