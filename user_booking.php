<?php
session_start();
include 'db.php';
include 'Navbar.php';

// Check if user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

// Fetch user details
$user_result = $conn->prepare("SELECT * FROM users WHERE id = ?");
$user_result->bind_param("i", $user_id);
$user_result->execute();
$user = $user_result->get_result()->fetch_assoc();

// Fetch bookings for this user
$booking_stmt = $conn->prepare("SELECT b.*, s.title 
                                FROM bookings b 
                                JOIN services s ON b.service_id = s.id 
                                WHERE b.user_id = ? 
                                ORDER BY b.date ASC");
$booking_stmt->bind_param("i", $user_id);
$booking_stmt->execute();
$booking_result = $booking_stmt->get_result();

?>
<!DOCTYPE html>
<html>
<head>
  <title>User Dashboard</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f3f3f3;
      padding:10px;
      color: #333;
    }
    .dashboard {
      max-width: 900px;
      margin: 50px auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    h2 { color: #00796b; }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ccc;
    }
    .status-pending { color: orange; }
    .status-confirmed { color: green; }
    .status-completed { color: gray; }
    .logout {
      text-align: right;
      margin-top: 20px;
    }
    .logout a {
      text-decoration: none;
      color: #00796b;
      font-weight: bold;
    }
    .logout a:hover {
      color: #004d40;
    }
  </style>
</head>
<body>
<div class="dashboard">
  <div class="logout">
    <a href="user_dashboard.php">Dashboard</a>
  </div>
<?php if (isset($_SESSION['message'])): ?>
    <p style="color: green;"><?= $_SESSION['message'] ?></p>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

  <h2>Welcome, <?= htmlspecialchars($user['username']) ?> 👋</h2>
  <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
  <!-- <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p> -->
  <hr>

  <h3>Your Upcoming & Past Bookings</h3>
  <table>
    <tr>
      <th>#</th>
      <th>Service</th>
      <th>Date</th>
      <th>Message</th>
      <th>Status</th>
    </tr>
   <?php
$i = 1;
while ($row = $booking_result->fetch_assoc()) {
    $statusClass = "status-" . htmlspecialchars($row['status']);
    echo "<tr>
            <td>{$i}</td>
            <td>" . htmlspecialchars($row['title']) . "</td>
            <td>" . htmlspecialchars($row['date']) . "</td>
            <td>" . htmlspecialchars($row['message']) . "</td>
            <td class='$statusClass'>";
    
    // Correctly output the status without using shorthand
    echo htmlspecialchars($row['status']);

    if ($row['status'] !== 'completed') {
        echo "<form method='post' action='cancel_booking.php' style='display:inline;'>
                <input type='hidden' name='booking_id' value='" . htmlspecialchars($row['id']) . "'>
                <button type='submit' style='margin-left:10px; padding: 5px 10px; background-color:#d9534f; color: white; border: none; border-radius: 4px; cursor: pointer;'>
                    Cancel
                </button>
              </form>";
    }
    
    echo "</td></tr>";
    
    $i++;
}
?>
  </table>

  <hr>
  <h3>Update Profile (Coming Soon...)</h3>
</div>
</body>
</html>
<?php include 'Footer.php'; ?>