
  <?php include 'Navbar.php'; ?>
<?php
include 'db.php';

// Fetch all blog posts
$sql = "SELECT * FROM blogs ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Yoga Blog</title>
    <link rel="stylesheet" href="SubStyle/blog.css" />
</head>
<body>

    <section class="section blog-section">
        <h2>Latest Blog Posts</h2>
        <div class="blog-container">
            
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="blog-card">
                    <img src="<?php echo htmlspecialchars($row['images']); ?>" alt="Blog Image">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p><?php echo htmlspecialchars($row['short_info']); ?></p>
                    <a href="blog_details.php?id=<?php echo $row['id']; ?>" class="read-more">Read More →</a>
                </div>
            <?php } ?>
            
        </div>
    </section>

</body>
</html>

<?php include 'Footer.php'; ?>