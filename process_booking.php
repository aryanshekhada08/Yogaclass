<?php
session_start();
include 'db.php';
include 'send_mail.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = htmlspecialchars($_POST['name']);
    $email    = htmlspecialchars($_POST['email']);
    $phone    = htmlspecialchars($_POST['phone']);
    $service  = (int)$_POST['service']; // assume service_id is numeric
    $date     = htmlspecialchars($_POST['date']);
    $message  = htmlspecialchars($_POST['message']);
    $user_id  = $_SESSION['id'];

    // Get service title from DB
    $service_title = '';
    $svc_stmt = $conn->prepare("SELECT title FROM services WHERE id = ?");
    $svc_stmt->bind_param("i", $service);
    $svc_stmt->execute();
    $svc_stmt->bind_result($service_title);
    $svc_stmt->fetch();
    $svc_stmt->close();

    // Insert booking
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, name, email, phone, service_id, date, message, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("isssiss", $user_id, $name, $email, $phone, $service, $date, $message);
    $bookingSuccess = $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking Confirmation</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background-color: #f3fdfc; margin: 0; padding: 0; color: #333; }
    .container { max-width: 700px; margin: 60px auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
    h2 { text-align: center; color: #00796b; margin-bottom: 24px; font-size: 28px; }
    .details { background-color: #d4edda; color: #155724; padding: 20px; border-radius: 10px; line-height: 1.6; }
    .details p { margin: 10px 0; }
    .btn-back { display: inline-block; margin-top: 24px; padding: 12px 20px; background: #00796b; color: #fff; border-radius: 8px; text-decoration: none; width: 100%; text-align: center; font-weight: 500; transition: background 0.3s; }
    .btn-back:hover { background-color: #004d40; }
  </style>
</head>
<body>

<?php if ($bookingSuccess): ?>
  <div class="container">
    <h2>Booking Confirmed</h2>
    <div class="details">
      <p><strong>Full Name:</strong> <?= htmlspecialchars($name) ?></p>
      <p><strong>Email Address:</strong> <?= htmlspecialchars($email) ?></p>
      <p><strong>Phone Number:</strong> <?= htmlspecialchars($phone) ?></p>
      <p><strong>Selected Service:</strong> <?= htmlspecialchars($service_title) ?></p>
      <p><strong>Preferred Date:</strong> <?= htmlspecialchars($date) ?></p>
      <?php if (!empty($message)) : ?>
        <p><strong>Additional Message:</strong><br><?= nl2br(htmlspecialchars($message)) ?></p>
      <?php endif; ?>
      <p>✅ Your yoga service request has been successfully submitted. We’ll contact you shortly!</p>
    </div>
    <a href="./services.php" class="btn-back">← Book Another Service</a>
  </div>

  <?php
    $subject = "Booking Confirmation - Yoga Class";
    $body = "
      <h3>Hello $name,</h3>
      <p>Thank you for booking <strong>$service_title</strong> on <strong>$date</strong>.</p>
      <p>We will confirm your booking shortly. Stay healthy and peaceful! 🧘‍♀️</p>
      <p>— Yoga Class Team</p>
    ";

    if (sendConfirmationMail($email, $name, $subject, $body)) {
        echo "<script>console.log('Confirmation email sent successfully.');</script>";
    } else {
        echo "<script>console.log('Booking saved but email sending failed.');</script>";
    }
  ?>

<?php else: ?>
  <div class="container">
    <h2>Booking Failed</h2>
    <div class="details" style="background-color: #f8d7da; color: #721c24;">
      <p>⚠️ Sorry, there was an issue processing your booking. Please try again later.</p>
    </div>
    <a href="./services.php" class="btn-back">← Back to Services</a>
  </div>
<?php endif; ?>

</body>
</html>
