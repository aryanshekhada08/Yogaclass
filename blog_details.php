<?php
include 'db.php';
include 'Navbar.php'; // Assuming you want the Navbar at the top

// Check for blog ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p style='text-align:center;color:red;'>Invalid blog ID.</p>";
    exit();
}

$blog_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT title, content, images, created_at FROM blogs WHERE id = ?");
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p style='text-align:center;color:red;'>Blog not found.</p>";
    exit();
}

$blog = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($blog['title']); ?> - Blog Details</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background-color: #f0fdf5;
        }
        .blog-container {
            max-width: 900px;
            margin: 3rem auto;
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .blog-title {
            font-size: 2rem;
            color: #2e8b72;
            margin-bottom: 1rem;
        }
        .blog-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        .blog-meta {
            font-size: 0.9rem;
            color: #888;
            margin-bottom: 1.5rem;
        }
        .blog-content {
            line-height: 1.8;
            font-size: 1.1rem;
            color: #333;
        }
        .back-link {
            display: inline-block;
            margin-top: 2rem;
            text-decoration: none;
            color: #2e8b72;
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="blog-container">
    <h1 class="blog-title"><?php echo htmlspecialchars($blog['title']); ?></h1>
    
    <?php if (!empty($blog['images'])): ?>
    <img src="<?php echo htmlspecialchars($blog['images']); ?>" alt="Blog Image" class="blog-image">
<?php endif; ?>


    <p class="blog-meta">Posted on <?php echo date("F j, Y", strtotime($blog['created_at'])); ?></p>

    <div class="blog-content">
        <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
    </div>

    <a href="blog.php" class="back-link">← Back to All Blogs</a>
</div>

</body>
</html>
