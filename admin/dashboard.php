<?php
session_start();
require_once '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Get statistics
$total_products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM products"))['count'];
$total_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders"))['count'];
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role='user'"))['count'];
$pending_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE status='pending'"))['count'];

// Recent orders
$recent_orders = mysqli_query($conn, "SELECT o.*, u.name as user_name FROM orders o 
                                       JOIN users u ON o.user_id = u.id 
                                       ORDER BY o.created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Cloth Rental</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Admin Navigation -->
    <nav class="navbar admin-navbar">
        <div class="container">
            <div class="nav-brand">
                <h1>Admin Panel</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="manage-products.php">Products</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="../index.php">View Site</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Dashboard Section -->
    <section class="admin-section">
        <div class="container">
            <h2>Dashboard</h2>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3><?php echo $total_products; ?></h3>
                    <p>Total Products</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $total_orders; ?></h3>
                    <p>Total Orders</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $total_users; ?></h3>
                    <p>Total Users</p>
                </div>
                <div class="stat-card stat-pending">
                    <h3><?php echo $pending_orders; ?></h3>
                    <p>Pending Orders</p>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="admin-panel">
                <h3>Recent Orders</h3>
                <?php if (mysqli_num_rows($recent_orders) > 0): ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = mysqli_fetch_assoc($recent_orders)): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo $order['user_name']; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                    <td>₹<?php echo number_format($order['total_price'], 2); ?></td>
                                    <td><span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                                    <td><a href="orders.php?id=<?php echo $order['id']; ?>" class="btn btn-primary btn-small">View</a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No orders yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Cloth Rental System - Admin Panel</p>
        </div>
    </footer>
</body>
</html>
