<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="./bgremove_img/place_your_logo_here_doub.png">
    <link rel="stylesheet" href="SubStyle/navbar.css">
</head>
<body>
<header>
    <nav class="nav">
        <div class="logo">
            <img src="./bgremove_img/place_your_logo_here_doub.png" alt="Logo">
        </div>
        <button class="menu-icon" aria-label="Open menu">&#9776;</button>
        <ul class="ul" id="menu">
            <button class="close-icon" aria-label="Close menu">&times;</button>
            <li class="li"><a href="index.php">Home</a></li>
            <li class="li"><a href="about.php">About Us</a></li>
            <li class="li"><a href="services.php">Services</a></li>
            <li class="li"><a href="blog.php">Blog</a></li>
            <li class="li"><a href="shop.php">Shop</a></li>
            <li class="li"><a href="cart.php">Cart</a></li>
            <li class="li"><a href="contact.php">Contact Us</a></li>
            <?php if (!empty($_SESSION['username'])): ?>
                <li class="li push-right"><a href="user_dashboard.php">Dashboard</a></li>
                <li class="li"><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li class="li push-right"><a href="login.php">Login</a></li>
                <li class="li"><a href="signup.php">Sign Up</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<script>
    function toggleMenu() {
        const menu = document.getElementById("menu");
        const menuIcon = document.querySelector(".menu-icon");
        const closeIcon = document.querySelector(".close-icon");

        if (window.innerWidth <= 768) {
            menu.style.display = "flex";
            menu.classList.add("show");
            menuIcon.style.display = "none";
            closeIcon.style.display = "block";
        }
    }

    function closeMenu() {
        const menu = document.getElementById("menu");
        const menuIcon = document.querySelector(".menu-icon");
        const closeIcon = document.querySelector(".close-icon");

        if (window.innerWidth <= 768) {
            menu.style.display = "none";
            menu.classList.remove("show");
            menuIcon.style.display = "block";
            closeIcon.style.display = "none";
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        const menu = document.getElementById("menu");
        const menuIcon = document.querySelector(".menu-icon");
        const closeIcon = document.querySelector(".close-icon");

        menuIcon.addEventListener("click", toggleMenu);
        closeIcon.addEventListener("click", function (event) {
            event.stopPropagation();
            closeMenu();
        });

        document.addEventListener("click", function (event) {
            if (window.innerWidth <= 768 && menu.classList.contains("show")) {
                if (!menu.contains(event.target) && !menuIcon.contains(event.target)) {
                    closeMenu();
                }
            }
        });

        function adjustMenuVisibility() {
            if (window.innerWidth > 768) {
                menu.style.display = "flex";
                menu.classList.add("show");
                menuIcon.style.display = "none";
                closeIcon.style.display = "none";
            } else {
                menu.style.display = "none";
                menu.classList.remove("show");
                menuIcon.style.display = "block";
                closeIcon.style.display = "none";
            }
        }

        window.addEventListener("resize", adjustMenuVisibility);
        adjustMenuVisibility();
    });
</script>
</body>
</html>
