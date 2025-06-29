<?php
session_start();
include '../db.php';
include 'admin_navbar.php';

// Fetch all services
$result = $conn->query("SELECT * FROM services ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Services - Yoga Class</title>
    <link rel="stylesheet" href="SubStyle/auth.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4fdfb;
        }
        .services-container {
            max-width: 1000px;
            margin: 3rem auto;
            background: white;
            padding: 2rem;
            /* display:inline-table; */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #2e8b72;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #2e8b72;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        img {
            width: 100px;
            height: auto;
            border-radius: 5px;
        }
        .actions a {
            text-decoration: none;
            margin: 0 5px;
            padding: 6px 10px;
            color: white;
            border-radius: 5px;
            margin: 8px 5px;
            display: block;
        }
        .edit-btn {
            background-color: #007bff;
            margin:10px 0px 10px 0px

        }
        .delete-btn {
            background-color: #dc3545;
        }
        .view-btn {
            background-color:rgba(6, 168, 125, 0.67);
        }
    </style>
</head>
<body>
    <div class="services-container">
        <h2>All Services</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><img src="<?= $row['image_url'] ?>" alt="Service Image"></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td>$<?= number_format($row['price'], 2) ?></td>
                    <td><?= htmlspecialchars($row['duration']) ?></td>
                    <td class="actions">
                        <a href="edit_service.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
                        <a href="../book_service.php?id=<?= $row['id'] ?>" class="view-btn">View</a>
                        <a href="delete_service.php?id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this service?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
