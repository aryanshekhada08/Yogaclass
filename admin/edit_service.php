<?php
session_start();
include '../db.php';
include 'admin_navbar.php';

if (!isset($_GET['id'])) {
    echo "<p class='error'>No service selected.</p>";
    exit();
}

$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $image_url = trim($_POST['image_url']);
    $price = (float)$_POST['price'];
    $duration = trim($_POST['duration']);

    $stmt = $conn->prepare("UPDATE services SET title=?, description=?, image_url=?, price=?, duration=? WHERE id=?");
    $stmt->bind_param("sssdsi", $title, $description, $image_url, $price, $duration, $id);

    if ($stmt->execute()) {
        echo "<p class='success'>Service updated successfully!</p>";
    } else {
        echo "<p class='error'>Failed to update service.</p>";
    }
}

// Get service info
$stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$service = $result->fetch_assoc();

if (!$service) {
    echo "<p class='error'>Service not found.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Service</title>
    <link rel="stylesheet" href="SubStyle/auth.css">
    <style>
        .service-form-container { max-width: 600px; margin: 3rem auto; padding: 2rem; background: white; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
        .service-form-container h2 { text-align: center; color: #2e8b72; }
        .service-form input, .service-form textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 6px; }
        .service-form input[type="submit"] { background-color: #2e8b72; color: white; border: none; cursor: pointer; font-size: 1rem; }
        .success { text-align: center; color: green; }
        .error { text-align: center; color: red; }
    </style>
</head>
<body>
    <div class="service-form-container">
        <h2>Edit Service</h2>
        <form method="POST" class="service-form">
            <input type="text" name="title" value="<?= htmlspecialchars($service['title']) ?>" required>
            <textarea name="description" required><?= htmlspecialchars($service['description']) ?></textarea>
            <input type="text" name="image_url" value="<?= htmlspecialchars($service['image_url']) ?>" required>
            <input type="number" name="price" value="<?= $service['price'] ?>" step="0.01" required>
            <input type="text" name="duration" value="<?= htmlspecialchars($service['duration']) ?>" required>
            <input type="submit" value="Update Service">
        </form>
    </div>
</body>
</html>
