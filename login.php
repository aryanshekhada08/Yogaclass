<?php
session_start(); 
// echo "Session ID: " . session_id(); 
include 'db.php';
$message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $message = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // ✅ Set session only after verification
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header("Location: ./admin/admin_dashboard.php");
                } else {  
                    header("Location: user_dashboard.php");
                }
                exit();
            } else {
                $message = "<p class='error'>Incorrect username or password</p>";
            }
        } else {
            $message = "<p class='error'>User not found</p>";
        }

        $stmt->close();
    }
}
?>

<?php include 'Navbar.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Yoga Class</title>
    <link rel="stylesheet" href="SubStyle/auth.css">
</head>
<body>
<div class="auth-container">
    <h2>Login to Yoga Class</h2>
    <?php if (!empty($message)) echo $message; ?>
    <form method="POST" class="auth-form">
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <input type="submit" value="Login">
    </form>
    <a href="signup.php">Don't have an account? Sign up</a>
</div>
</body>
</html>
