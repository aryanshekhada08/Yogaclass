<?php
session_start();
include '../db.php';
include 'admin_navbar.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $image_url = trim($_POST['image_url']);
    $price = (float)$_POST['price'];
    $duration = trim($_POST['duration']);

    $stmt = $conn->prepare("INSERT INTO services (title, description, image_url, price, duration) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssds", $title, $description, $image_url, $price, $duration);

    if ($stmt->execute()) {
        echo "<p class='success'>Service added successfully!</p>";
    } else {
        echo "<p class='error'>Failed to add service. Try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Service - Yoga Class</title>
    <link rel="stylesheet" href="SubStyle/auth.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4fdfb;
        }
        .service-form-container {
            max-width: 600px;
            margin: 3rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .service-form-container h2 {
            text-align: center;
            color: #2e8b72;
        }
        .service-form input, .service-form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .service-form input[type="submit"] {
            background-color: #2e8b72;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        .service-form input[type="submit"]:hover {
            background-color: #256b59;
        }
        .success {
            text-align: center;
            color: green;
            margin-bottom: 10px;
        }
        .error {
            text-align: center;
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="service-form-container">
        <h2>Add New Service</h2>
        <form action="" method="POST" class="service-form">
            <input type="text" name="title" placeholder="Service Title" required>
            <textarea name="description" placeholder="Service Description" required></textarea>
            <input type="text" name="image_url" placeholder="Image URL" required>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <input type="text" name="duration" placeholder="Duration (e.g. 1 Hour)" required>
            <input type="submit" value="Add Service">
        </form>
    </div>
</body>
</html>