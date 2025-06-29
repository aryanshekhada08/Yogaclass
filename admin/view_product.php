
<?php
session_start();
include  '../db.php';
include 'admin_navbar.php';

// Fetch all products
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Products - Yoga Admin</title>
    <link rel="stylesheet" href="SubStyle/auth.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f3fbf8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 3rem auto;
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2e8b72;
            margin-bottom: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2e8b72;
            color: white;
        }

        img {
            max-width: 100px;
            border-radius: 8px;
        }

        .action-links a {
            margin-right: 10px;
            color:rgb(255, 255, 255);
            text-decoration: none;
            /* font-weight: bold; */
            border-radius: 5px;
            margin: 8px 5px;
            padding: 6px 10px;

            /* display: block; */
        }

        .action-links a:hover {
            text-decoration: underline;
        }
         .edit-btn {
            background-color:rgba(0, 123, 255, 0.84);
            /* margin:10px 0px 10px 0px */

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
<div class="container">
    <h2>All Products</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Image</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>$<?= number_format($row['price'], 2) ?></td>
                        <td><img src="<?= htmlspecialchars($row['image']) ?>" alt="Product Image"></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <td class="action-links">
                            <a href="../product_details.php?id=<?= $row['id'] ?>" class="view-btn">View</a>
                            <a href="edit_product.php?id=<?= $row['id'] ?>"class="edit-btn">Edit</a>
                            <a href="delete_product.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this product?');"class="delete-btn">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">No products found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html