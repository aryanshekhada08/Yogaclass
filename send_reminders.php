<?php
include 'db.php';
include 'send_mail.php';

$dateTomorrow = date('Y-m-d', strtotime('+1 day'));

// Get bookings for tomorrow that are still pending or confirmed
$stmt = $conn->prepare("
    SELECT b.name, b.email, b.date, s.title 
    FROM bookings b
    JOIN services s ON b.service_id = s.id
    WHERE b.date = ? AND (b.status = 'pending' OR b.status = 'confirmed')
");
$stmt->bind_param("s", $dateTomorrow);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $to = $row['email'];
    $name = $row['name'];
    $service_title = $row['title'];
    $date = $row['date'];

    $subject = "⏰ Reminder: Your Yoga Session is Tomorrow";
    $body = "
        <h3>Hello $name,</h3>
        <p>This is a reminder that your <strong>$service_title</strong> session is scheduled for <strong>$date</strong>.</p>
        <p>Please be on time and bring anything you need for the session.</p>
        <br>— Yoga Class Team
    ";

    sendConfirmationMail($to, $name, $subject, $body);
}

$stmt->close();
?>
