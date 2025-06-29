

<?php
session_start();
include 'db.php';
$message = ""; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user';

    $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $message = "<p class='error'>Username or email already exists. Try another one.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password, $role);
        if ($stmt->execute()) {
            // Save message to show in HTML
            $message = '<div class="redirect-msg">
                            <p class="success">Signup successful! Redirecting to login...</p>
                            <div class="spinner"></div>
                        </div>';
            // Redirect in 2 seconds
            header("refresh:2;url=login.php");
        } else {
            $message = "<p class='error'>Signup failed. Please try again.</p>";
        }
    }
}
?>

<?php include 'Navbar.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - Yoga Class</title>
    <link rel="stylesheet" href="SubStyle/auth.css">
    <style>
    .redirect-msg {
        text-align: center;
        margin-top: 20px;
    }

    .success {
        color: green;
        font-size: 1.2rem;
    }

    .error {
        color: red;
        text-align: center;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .spinner {
        margin: 10px auto;
        border: 6px solid #f3f3f3;
        border-top: 6px solid #2e8b72;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

</head>
<body>
<div class="auth-container">
    <h2>Create Your Account</h2>
    <?php if (!empty($message)) echo $message; ?>
    <form action="" method="POST" class="auth-form">
        <input type="text" name="username" placeholder="Choose Username" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <input type="password" name="password" placeholder="Choose Password" required>
        <input type="submit" value="Sign Up">
    </form>
    <a href="login.php">Already have an account? Login</a>
</div>
</body>
</html>
