<?php
include '../db.php';
include 'admin_navbar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $short_info = $_POST["short_info"];
    $content = $_POST["content"];
    $image = $_POST["image"]; // This is the URL

    $stmt = $conn->prepare("INSERT INTO blogs (title, short_info, content, images) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $short_info, $content, $image);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Blog added successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Add Blog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 40px;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            background: white;
            margin-top: 50px;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        input, textarea {
            width: 100%;
            margin-bottom: 15px;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            background-color: #00796b;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #004d40;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Add New Blog</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Blog Title" required>
        <textarea name="short_info" placeholder="Short Description" rows="3" required></textarea>
        <textarea name="content" placeholder="Blog Content" rows="6" required></textarea>
        <!-- Image URL input -->
            <label for="image">Image URL:</label>
            <input type="url" id="image" name="image" placeholder="https://example.com/image.jpg" required>

        <button type="submit">Add Blog</button>
    </form>
</div>
</body>
</html>
