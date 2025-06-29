<?php
// delete_blog.php
include '../db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Invalid Blog ID";
    exit;
}

$stmt = $conn->prepare("DELETE FROM blogs WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: view_blogs.php");
    exit;
} else {
    echo "Error deleting blog.";
}
