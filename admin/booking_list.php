<?php
include '../db.php';
include 'admin_navbar.php';

$query = "
    SELECT b.*, s.title AS service_name 
    FROM bookings b
    JOIN services s ON b.service_id = s.id
    ORDER BY b.date DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bookings Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f8f5;
            padding: 40px;
            margin: 0;
        }

        h2 {
            text-align: center;
            color: #00796b;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #00796b;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .status-pending {
            color: #f39c12;
            font-weight: bold;
        }

        .status-completed {
            color: #2ecc71;
            font-weight: bold;
        }

        .action-btn {
            background-color: #004d40;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .action-btn:hover {
            background-color: #00695c;
        }
    </style>
</head>
<body>

<h2>Bookings Dashboard</h2>

<table>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Service</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Date</th>
        <th>Status</th>
        <th>Change</th>
    </tr>
    <?php
    $i = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$i}</td>
            <td>" . htmlspecialchars($row['name']) . "</td>
            <td>" . htmlspecialchars($row['service_name']) . "</td>
            <td>" . htmlspecialchars($row['email']) . "</td>
            <td>" . htmlspecialchars($row['phone']) . "</td>
            <td>" . htmlspecialchars($row['date']) . "</td>
            
            <td class='status-{$row['status']}'>
    " . ucfirst($row['status']) . "
    " . (($row['status'] !== 'completed' && $row['status'] !== 'canceled') ? "
    <form method='post' action='admin_cancel_booking.php' style='display:inline; margin-left:10px;'>
        <input type='hidden' name='booking_id' value='{$row['id']}'>
        <button type='submit' onclick='return confirm(\"Are you sure you want to cancel this booking?\")' 
            style='background-color:#d9534f; color:white; border:none; padding:3px 8px; border-radius:4px; cursor:pointer;'>
            Cancel
        </button>
    </form>" : "") . "
</td>

            <td><a class='action-btn' href='update_status.php?id={$row['id']}'>Mark as Completed</a></td>
        </tr>";
        $i++;
    }
    ?>
</table>

</body>
</html>
