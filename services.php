<?php
session_start();
include 'db.php';
include 'Navbar.php';

$stmt = $conn->prepare("SELECT * FROM services ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$services = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Services - Yoga Class</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0fdf7;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
        }

        h2 {
            text-align: center;
            color: #2e8b72;
            margin-bottom: 2rem;
        }

        .services-wrapper {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .service-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.08);
            padding: 16px;
            display: flex;
            flex-direction: row;
            gap: 20px;
            flex-wrap: wrap;
        }

        .service-card img {
            flex-shrink: 0;
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
        }

        .service-info {
            flex: 1;
            min-width: 200px;
        }

        .service-info h3 {
            margin: 0 0 10px;
            color: #2e8b72;
        }

        .service-info p {
            margin: 4px 0;
        }

        .price {
            font-weight: bold;
            color: #333;
        }

        .duration {
            font-style: italic;
            color: #777;
        }

        .service-info form {
            margin-top: 10px;
        }

        .service-info button {
            background-color: #2e8b72;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .service-info button:hover {
            background-color: #256e59;
        }

        @media (max-width: 600px) {
            .service-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .service-card img {
                width: 100%;
                height: auto;
            }

            .service-info {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>All Services</h2>

    <div class="services-wrapper">
        <?php if (!empty($services)): ?>
            <?php foreach ($services as $service): ?>
                <div class="service-card">
                    <img src="<?= htmlspecialchars($service['image_url']) ?>" alt="<?= htmlspecialchars($service['title']) ?>">
                    <div class="service-info">
                        <h3><?= htmlspecialchars($service['title']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($service['description'])) ?></p>
                        <p class="price">$<?= number_format($service['price'], 2) ?></p>
                        <p class="duration">Duration: <?= htmlspecialchars($service['duration']) ?></p>
                        <form action="book_service.php" method="get">
                            <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
                            <button type="submit">Book Now</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No services found.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'Footer.php'; ?>
</body>
</html>
