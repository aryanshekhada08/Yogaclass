<?php
session_start();
include '../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['booking_id'])) {
    $bookingId = intval($_POST['booking_id']);

    // Optional: Fetch the booking first for logging or confirmation
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $bookingId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Booking canceled successfully.";
    } else {
        $_SESSION['message'] = "Failed to cancel booking.";
    }

    $stmt->close();
}

header("Location: admin_dashboard.php");
exit();
?>
