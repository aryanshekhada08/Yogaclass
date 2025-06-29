<?php session_start(); ?>
<?php include '../db.php'; ?>
<?php include 'p_nav.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Delivery Address</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    /* Strictly scoped wrapper to isolate styling */
    .delivery-wrapper * {
      all: unset;
      box-sizing: border-box;
    }

    .delivery-wrapper {
      font-family: 'Segoe UI', sans-serif;
      max-width: 600px;
      margin: 50px auto;
      background: #ffffff;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .delivery-wrapper h2 {
      text-align: center;
      color: #2e8b72;
      margin-bottom: 2rem;
      font-size: 24px;
      font-weight: bold;
    }

    .delivery-wrapper label {
      display: block;
      margin-bottom: 6px;
      font-size: 14px;
      font-weight: 600;
      color: #333;
    }

    .delivery-wrapper input,
    .delivery-wrapper textarea {
      width: 100%;
      padding: 10px 12px;
      margin-bottom: 1.2rem;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
    }

    .delivery-wrapper textarea {
      resize: vertical;
      min-height: 100px;
    }

    .delivery-wrapper button {
      width: 100%;
      background-color: #2e8b72;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .delivery-wrapper button:hover {
      background-color: #256b59;
    }

    @media (max-width: 600px) {
      .delivery-wrapper {
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>

<div class="delivery-wrapper">
  <form method="POST" action="payment.php">
    <h2>Enter Delivery Address</h2>

    <label for="name">Full Name</label>
    <input type="text" id="name" name="name" placeholder="John Doe" required>

    <label for="phone">Phone Number</label>
    <input type="tel" id="phone" name="phone" placeholder="+91 9876543210" required>

    <label for="email">Email Address</label>
    <input type="email" id="email" name="email" placeholder="you@example.com" required>

    <label for="address">Full Address</label>
    <textarea id="address" name="address" placeholder="House no., Street, Landmark..." required></textarea>

    <label for="pincode">Pincode</label>
    <input type="text" id="pincode" name="pincode" placeholder="123456" required>

    <label for="city">City</label>
    <input type="text" id="city" name="city" placeholder="Mumbai" required>

    <label for="state">State</label>
    <input type="text" id="state" name="state" placeholder="Maharashtra" required>

    <button type="submit">Continue to Payment</button>
  </form>
</div>

</body>
</html>
<?php include 'p_footer.php'; ?>
