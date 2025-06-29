<?php
session_start();
include '../db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<p class='success'>Service deleted successfully.</p>";
    } else {
        echo "<p class='error'>Failed to delete service.</p>";
    }
} else {
    echo "<p class='error'>Invalid request.</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Service</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            text-align: center;
            padding: 2rem;
        }
        .success {
            color: green;
            font-size: 1.2rem;
        }
        .error {
            color: red;
            font-size: 1.2rem;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background: #2e8b72;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
        }
        a:hover {
            background: #256b59;
        }
    </style>
</head>
<body>
    <a href="view_services.php">Back to Services List</a>
</body>
</html>
