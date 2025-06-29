
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Yoga Class</title> -->
    <link rel="icon" type="image/png" href="../bgremove_img/place_your_logo_here_doub.png">
    <link rel="stylesheet" href="../SubStyle/navbar.css">
</head>
<body>
<header>
    <nav class="nav">
        <div class="logo">
            <img src="../bgremove_img/place_your_logo_here_doub.png" alt="Logo">
        </div>
        <button class="menu-icon" onclick="toggleMenu()" aria-label="Open menu">&#9776;</button>
        <ul class="ul" id="menu">
            <button class="close-icon" onclick="closeMenu()" aria-label="Close menu">&times;</button>
            <li class="li"><a href="../index.php">Home</a></li>
            <li class="li"><a href="../about.php">About Us</a></li>
            <li class="li"><a href="../services.php">Services</a></li>
            <li class="li"><a href="../blog.php">Blog</a></li>
            <li class="li"><a href="../shop.php">Shop</a></li>
            <li class="li"><a href="../cart.php">Cart</a></li>
            <li class="li"><a href="../contact.php">Contact Us</a></li>

            <?php if (!empty($_SESSION['username'])): ?>
                <li class="li push-right"><a href="../user_dashboard.php">Dashboard</a></li>
                <li class="li"><a href="../logout.php">Logout</a></li>
            <?php else: ?>
                <li class="li push-right"><a href="../login.php">Login</a></li>
                <li class="li"><a href="../signup.php">Sign Up</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const menu = document.getElementById("menu");
    const menuIcon = document.querySelector(".menu-icon");
    const closeIcon = document.querySelector(".close-icon");

    // Hide icons by default
    menuIcon.style.display = "none";
    closeIcon.style.display = "none";

    // Set menu visibility based on screen size at start
    function adjustMenuVisibility() {
        if (window.innerWidth > 768) {
            menu.style.display = "flex"; // Keep menu visible on desktop
            menu.classList.add("show");
            menuIcon.style.display = "none";
            closeIcon.style.display = "none"; // Hide icons on desktop
        } else {
            menu.style.display = "none"; // Hide menu initially on mobile
            menu.classList.remove("show");
            menuIcon.style.display = "block"; // Show menu icon
            closeIcon.style.display = "none"; // Hide close icon until menu opens
        }
    }

    // Open menu function (mobile only)
    function toggleMenu() {
        if (window.innerWidth <= 768) {
            menu.style.display = "flex";
            menu.classList.add("show");
            menuIcon.style.display = "none"; // Hide menu icon when open
            closeIcon.style.display = "block"; // Show close button
        }
    }

    // Close menu function (mobile only)
    function closeMenu() {
        if (window.innerWidth <= 768) {
            menu.style.display = "none";
            menu.classList.remove("show");
            menuIcon.style.display = "block"; // Show menu icon again
            closeIcon.style.display = "none"; // Hide close button
        }
    }

    // Event listeners for menu toggle
    menuIcon.addEventListener("click", toggleMenu);
    closeIcon.addEventListener("click", function (event) {
        event.stopPropagation();
        closeMenu();
    });

    // Close menu when clicking outside (mobile only)
    document.addEventListener("click", function (event) {
        if (window.innerWidth <= 768 && menu.classList.contains("show")) {
            if (!menu.contains(event.target) && !menuIcon.contains(event.target)) {
                closeMenu();
            }
        }
    });

    // Adjust menu visibility on window resize
    window.addEventListener("resize", adjustMenuVisibility);

    // Run function once on page load
    adjustMenuVisibility();
});
</script>
</body>
</html>
