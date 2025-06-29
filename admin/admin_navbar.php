<?php
// session_start(); // Uncomment if needed
?>

<style>
    .admin-navbar {
        background-color:rgba(46, 139, 114, 0.82);
        color: white;
        font-family: 'Segoe UI', sans-serif;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 20px;
        position: sticky;
        top: 0;
        z-index: 999;
    }

    .admin-navbar .logo {
        font-size: 1.5rem;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .admin-navbar .menu-toggle {
        font-size: 1.5rem;
        display: none;
        cursor: pointer;
    }

    .admin-navbar .nav-links {
        display: flex;
        gap: 14px;
    }

    .admin-navbar .nav-links a {
        color: white;
        text-decoration: none;
        font-weight: 500;
        padding: 8px 12px;
        border-radius: 6px;
        transition: background 0.3s ease;
    }

    .admin-navbar .nav-links a:hover,
    .admin-navbar .nav-links a.active {
        background-color:rgb(37, 107, 90);
    }

    .admin-content {
        padding: 30px;
        margin-top: 70px;
    }

    /* Mobile Styles */
    @media (max-width: 768px) {
        .admin-navbar {
            flex-wrap: wrap;
        }

        .admin-navbar .menu-toggle {
            display: block;
        }

        .admin-navbar .nav-links {
            display: none;
            flex-direction: column;
            width: 100%;
            margin-top: 10px;
        }

        .admin-navbar .nav-links.show {
            display: flex;
        }

        .admin-navbar .nav-links a {
            padding: 10px;
            background: #3a9c81;
            margin-bottom: 5px;
        }
    }
</style>

<script>
    function toggleNav() {
        const navLinks = document.querySelector('.admin-navbar .nav-links');
        navLinks.classList.toggle('show');
    }
</script>

<div class="admin-navbar">
    <div class="logo">🧘 Admin Panel</div>
    <div class="menu-toggle" onclick="toggleNav()">☰</div>
    <div class="nav-links">
        <a href="../admin/admin_dashboard.php">Dashboard</a>
        <a href="../admin/admin_orders.php">Product Oders</a>
        <a href="../admin/view_product.php">View Products</a>
        <a href="../admin/add_blog.php">Add Blog</a>
        <a href="../admin/view_blogs.php">View Blogs</a>
        <a href="../admin/add_service.php">Add Service</a>
        <a href="../admin/view_services.php">View Services</a>
        <a href="../admin/booking_list.php">Bookings</a>
        <a href="logout.php">Logout</a>
    </div>
</div>
