<?php include 'Navbar.php'; ?>
<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Book a Yoga Service</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f3fdfc;
      color: #333;
    }

    .contact-wrapper * {
      all: unset;
      box-sizing: border-box;
    }

    .contact-wrapper {
      max-width: 700px;
      margin: 60px auto;
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      padding: 30px;
    }

    .contact-wrapper h2 {
      text-align: center;
      color: #00796b;
      margin-bottom: 20px;
      font-size: 26px;
      font-weight: bold;
    }

    .contact-wrapper label {
      display: block;
      margin-bottom: 6px;
      color: #555;
      font-weight: 600;
    }

    .contact-wrapper input,
    .contact-wrapper textarea,
    .contact-wrapper select {
      width: 100%;
      padding: 10px 12px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
      background-color: white;
    }

    .contact-wrapper textarea {
      resize: vertical;
      min-height: 120px;
    }

    .contact-wrapper button {
      width: 100%;
      background-color: #00796b;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .contact-wrapper button:hover {
      background-color: #004d40;
    }

    .success-message {
      display: none;
      text-align: center;
      margin-bottom: 15px;
      background-color: #d4edda;
      color: #155724;
      padding: 10px 16px;
      border-radius: 6px;
      animation: fadeInDown 1s ease forwards;
    }
  </style>
</head>
<body>

  <div class="contact-wrapper">
    <h2>Book a Yoga Service</h2>
    <form action="process_booking.php" method="POST">

      <label for="name">Full Name</label>
      <input type="text" id="name" name="name" placeholder="Your full name" required />

      <label for="email">Email Address</label>
      <input type="email" id="email" name="email" placeholder="you@example.com" required />

      <label for="phone">Phone Number</label>
      <input type="tel" id="phone" name="phone" placeholder="e.g., +1234567890" required />

      <label for="service">Select Service</label>
       <select name="service" required>
      <option value="">-- Choose a service --</option>
       <?php
         $result = $conn->query("SELECT id, title FROM services");
        while ($row = $result->fetch_assoc()) {
          echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['title']) . '</option>';
        }
           ?>
       </select>

      <label for="date">Preferred Date</label>
      <input type="date" id="date" name="date" required />

      <label for="message">Additional Notes</label>
      <textarea id="message" name="message" placeholder="Any specific requests or information?"></textarea>
      <button type="submit">Book Now</button>
    </form>
  </div>

</body>
</html>
<?php include 'Footer.php'; ?>
