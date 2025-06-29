<?php
// edit_blog.php
include '../db.php';
include 'admin_navbar.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Invalid Blog ID";
    exit;
}

// Fetch current data
$stmt = $conn->prepare("SELECT * FROM blogs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$blog = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $short_info = $_POST['short_info'];
    $content = $_POST['content'];
    $image = $_POST['images']; // URL-based image

    $updateStmt = $conn->prepare("UPDATE blogs SET title = ?, short_info = ?, content = ?, images = ? WHERE id = ?");
    $updateStmt->bind_param("ssssi", $title, $short_info, $content, $image, $id);

    if ($updateStmt->execute()) {
        header("Location: view_blogs.php");
        exit;
    } else {
        echo "Error updating blog.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Blog</title>
  <style>
    body {
      font-family: Arial;
      background: #f0f0f0;
      padding: 40px;
    }
    form {
      background: white;
      padding: 30px;
      border-radius: 8px;
      width: 500px;
      margin: auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    input, textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
    }
    button {
      padding: 10px 20px;
      background: #00796b;
      color: white;
      border: none;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <form method="POST">
    <h2>Edit Blog Post</h2>
    <input type="text" name="title" value="<?= htmlspecialchars($blog['title']) ?>" required>
    <textarea name="short_info" required><?= htmlspecialchars($blog['short_info']) ?></textarea>
    <textarea name="content" rows="6" required><?= htmlspecialchars($blog['content']) ?></textarea>
    <input type="text" name="image" value="<?= htmlspecialchars($blog['images']) ?>" placeholder="Paste image URL">
    <button type="submit">Update Blog</button>
  </form>
</body>
</html>
