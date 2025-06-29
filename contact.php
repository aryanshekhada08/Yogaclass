<?php include 'Navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us - Yoga Class</title>
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
    .contact-wrapper textarea {
      width: 100%;
      padding: 10px 12px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
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

    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

   .contact-info {
  flex: 1 1 300px;
  background-color: #ffffff;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  max-width: 600px;
  margin: 20px auto;
}

.contact-info h3 {
  color: #00796b;
  margin-bottom: 20px;
  font-size: 1.4rem;
  border-bottom: 2px solid #e0f2f1;
  padding-bottom: 10px;
}

.info-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.info-list li {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
  font-size: 1rem;
  color: #444;
  transition: transform 0.2s ease;
}

.info-list li span {
  font-size: 1.2rem;
  margin-right: 10px;
  color: #004d40;
}

.info-list li:hover {
  transform: translateX(5px);
}


    .contact-image {
      text-align: center;
      margin-top: 40px;
    }

    .contact-image img {
      max-width: 100%;
      border-radius: 12px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
  </style>
</head>
<body>
  <div class="contact-wrapper">
    <div id="success" class="success-message">Message sent successfully!</div>
    <h2>Contact Us</h2>
    <form id="contactForm">
      <label for="name">Your Name</label>
      <input type="text" id="name" name="name" placeholder="Enter your name" required />

      <label for="email">Your Email</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" required />

      <label for="message">Message</label>
      <textarea id="message" name="message" placeholder="Type your message..." required></textarea>

      <button type="submit">Send Message</button>
    </form>

    
  </div>
<div class="contact-info">
  <h3>Visit Our Studio</h3>
  <ul class="info-list">
    <li><span>📍</span> 123 Peaceful Street, Wellness City, YO 45678</li>
    <li><span>✉️</span> contact@yogaclass.com</li>
    <li><span>📞</span> +1 (555) 123-4567</li>
    <li><span>⏰</span> Mon - Sat: 7am - 7pm</li>
  </ul>
</div>
  <div class="contact-image">
    <img src="https://images.unsplash.com/photo-1687783615494-b4a1f1af8b58?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NTh8fHlvZ2ElMjBzdHVkaW9lc3xlbnwwfHwwfHx8MA%3D%3D" alt="Yoga Studio" />
  </div>

  <script>
    const form = document.getElementById('contactForm');
    const success = document.getElementById('success');

    form.addEventListener('submit', function(e) {
      e.preventDefault();
      success.style.display = 'block';
      form.reset();

      setTimeout(() => {
        success.style.display = 'none';
      }, 3000);
    });
  </script>
</body>
</html>
<?php include 'Footer.php'; ?>
