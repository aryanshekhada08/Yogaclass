<?php include '../db.php'; ?>
<?php session_start(); ?>
<?php 
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<?php include 'admin_navbar.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Yoga Class</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../SubStyle/admin_dashboard.css">
    
</head>
<body>
    <div class="dashboard-header">
        <h1>Welcome to Admin Dashboard</h1>
    </div>

    <div class="stats">
        <div class="stat">
            <h3>24</h3>
            <p>Total Blogs</p>
        </div>
        <div class="stat">
            <h3>18</h3>
            <p>Products Available</p>
        </div>
        <div class="stat">
            <h3>129</h3>
            <p>Active Users</p>
        </div>
        <div class="stat">
            <h3>3.2K</h3>
            <p>Monthly Visitors</p>
        </div>
    </div>

    <div class="search-box">
        <input type="text" placeholder="Search blogs or products...">
    </div>

    <div class="dashboard-content">
        <div class="card">
            <h3>Services Oders</h3>
            <p>View New Oders Of Services.</p>
            <a href="../admin/booking_list.php">Order List</a>
            <!-- <a href="./admin/add_user.php">Add New User</a> -->
        </div>
        <div class="card">
            <h3></h3>
            <p>View New Oders Of Products.</p>
            <a href="../admin/admin_orders.php">View Logs</a>
        </div>
        <div class="card">
            <h3>Manage Blogs</h3>
            <p>Create, update, and delete yoga blogs.</p>
            <a href="../admin/view_blogs.php">Go to Blogs</a>
            <a href="../admin/add_blog.php">Add New Blog</a>
        </div>  
        
        <div class="card">
            <h3>Manage Services</h3>
            <p>Update or delete yoga services.</p>
            <a href="../admin/view_services.php">Go to Services</a>
            <a href="../admin/add_service.php">Add New Service</a>
        </div>
        
        <div class="card">
            <h3>Manage Products</h3>
            <p>Add or edit yoga-related products for your shop.</p>
            <a href="../admin/view_product.php">Go to Products</a>
            <a href="../admin/add_product.php">Add New Products</a>
        </div>
        <div class="card">
            <h3>User Activity</h3>
            <p>Monitor user engagement and actions.</p>
            <a href="#activity">View Logs</a>
        </div>
        <div class="card">
            <h3>Log Out</h3>
            <p>End admin session securely.</p>
            <a href="../logout.php">Logout</a>
        </div>
    </div>

    <div class="charts">
        <div class="chart-container">
            <h3>Product Sales Overview</h3>
            <canvas id="productSalesChart"></canvas>
        </div>
        <div class="chart-container">
            <h3>Blog Views Statistics</h3>
            <canvas id="blogViewsChart"></canvas>
        </div>
    </div>

    <script>
        const salesCtx = document.getElementById('productSalesChart').getContext('2d');
        const productSalesChart = new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: ['Yoga Mats', 'Blocks', 'Straps', 'Bottles', 'Shirts'],
                datasets: [{
                    label: 'Sales (This Month)',
                    data: [120, 90, 70, 55, 100],
                    backgroundColor: '#2e8b72'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const blogCtx = document.getElementById('blogViewsChart').getContext('2d');
        const blogViewsChart = new Chart(blogCtx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Blog Views',
                    data: [400, 550, 300, 620],
                    fill: true,
                    borderColor: '#2e8b72',
                    backgroundColor: 'rgba(46,139,114,0.1)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
