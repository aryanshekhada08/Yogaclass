<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "yoga";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully"; 
// function logActivity($username, $activity, $conn) {
//     $stmt = $conn->prepare("INSERT INTO user_activity_logs (username, activity) VALUES (?, ?)");
//     $stmt->bind_param("ss", $username, $activity);
//     $stmt->execute();
//     // $stmt->close();
// }

?>