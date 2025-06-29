<?php
session_start();
include 'db.php';
include 'send_mail.php'; // Make sure this exists and works

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'])) {
    $bookingId = intval($_POST['booking_id']);
    $userId = $_SESSION['id'];

    // Get booking info before deleting
    $stmt = $conn->prepare("SELECT name, email, service_id, date FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $bookingId, $userId);
    $stmt->execute();
    $stmt->bind_result($name, $email, $service_id, $date);
    $stmt->fetch();
    $stmt->close();

    // Get service title
    $service_title = '';
    $svc_stmt = $conn->prepare("SELECT title FROM services WHERE id = ?");
    $svc_stmt->bind_param("i", $service_id);
    $svc_stmt->execute();
    $svc_stmt->bind_result($service_title);
    $svc_stmt->fetch();
    $svc_stmt->close();

    // Delete booking
    $delete_stmt = $conn->prepare("DELETE FROM bookings WHERE id = ? AND user_id = ?");
    $delete_stmt->bind_param("ii", $bookingId, $userId);

    if ($delete_stmt->execute()) {
        $_SESSION['message'] = "Booking canceled successfully.";

        // Send email
        $subject = "Booking Cancellation - Yoga Class";
        $body = "
            <h3>Hello $name,</h3>
            <p>Your booking for <strong>$service_title</strong> on <strong>$date</strong> has been successfully cancelled.</p>
            <p>If this was a mistake, feel free to book again anytime.</p>
            <br>— Yoga Class Team
        ";

        sendConfirmationMail($email, $name, $subject, $body);
    } else {
        $_SESSION['message'] = "Failed to cancel booking.";
    }

    $delete_stmt->close();
}

header("Location: user_booking.php");
exit();
?>
