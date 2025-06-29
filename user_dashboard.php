<?php
session_start();

include 'db.php';

// Redirect if not logged in or not a user
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}
?>

<?php include 'Navbar.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard - Yoga Class</title>
    <link rel="stylesheet" href="SubStyle/dashboard.css">
</head>
<body>

<div class="dashboard-container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

    <div class="dashboard-grid">
        <div class="card">
            <h3>My Bookings</h3>
            <p>View and manage your class bookings.</p>
            <a href="./user_booking.php">View Bookings</a>
        </div>

        <div class="card">
            <h3>Orders</h3>
            <p>Check Orders List</p>
            <!-- <a href="#">View Schedule</a> -->
             <a href="my_orders.php">📦 My Orders</a>
        </div>

        <div class="card">
            <h3>Update Profile</h3>
            <p>Edit your account and contact details.</p>
            <a href="#">Edit Profile</a>
        </div>

        <div class="card">
            <h3>Log Out</h3>
            <p>End your session securely.</p>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</div>

</body>
</html>
