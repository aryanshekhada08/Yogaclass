<?php
// view_blogs.php
include '../db.php';
include 'admin_navbar.php';

$result = $conn->query("SELECT * FROM blogs ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Blogs - Admin Dashboard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
      margin: 0;
      padding: 40px;
    }
    h1 {
      text-align: center;
      color: #00796b;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      border-radius: 10px;
      overflow: hidden;
    }
    th, td {
      padding: 15px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }
    th {
      background-color: #00796b;
      color: white;
    }
    img {
      max-height: 80px;
      border-radius: 8px;
    }
    .actions a {
      text-decoration: none;
      padding: 8px 16px;
      margin-right: 5px;
      color: white;
      background-color: #00796b;
      border-radius: 5px;
      font-size: 0.9rem;
      display: inline-block;
     transition: background-color 0.3s;
     margin-top:5px;

    
    }
    .actions a.delete {
      background-color: #d32f2f;
    }
  </style>
</head>
<body>
  <h1>All Blog Posts</h1>
  <table>
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Short Info</th>
      <th>Image</th>
      <th>Date</th>
      <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
     <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['title']); ?></td>
        <td><?php echo htmlspecialchars($row['short_info']); ?></td>
        <td>
            <!-- <img src="<?php echo '../uploads/blogs/' . basename($row['images']); ?>" alt="Blog Image"> -->
             <img src="<?php echo htmlspecialchars($row['images']); ?>"
              onerror="this.src='https://via.placeholder.com/80x80?text=No+Image';"
              alt="Blog Image">
        </td>
        <td><?php echo $row['created_at']; ?></td>
        <td class="actions">
            <a href="edit_blog.php?id=<?php echo $row['id']; ?>">Edit</a>
            <a href="delete_blog.php?id=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>

    <?php endwhile; ?>
  </table>
</body>
</html>
