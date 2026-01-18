<?php
session_start();
require_once 'config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user orders
$sql = "SELECT o.*, COUNT(oi.id) as item_count 
        FROM orders o 
        LEFT JOIN order_items oi ON o.id = oi.order_id 
        WHERE o.user_id = '$user_id' 
        GROUP BY o.id 
        ORDER BY o.created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Cloth Rental</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <h1>Cloth Rental</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="orders.php" class="active">My Orders</a></li>
                <li><a href="cart/view-cart.php">Cart</a></li>
                <li><a href="auth/logout.php">Logout (<?php echo $_SESSION['user_name']; ?>)</a></li>
            </ul>
        </div>
    </nav>

    <!-- Orders Section -->
    <section class="orders-section">
        <div class="container">
            <h2>My Orders</h2>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="orders-list">
                    <?php while ($order = mysqli_fetch_assoc($result)): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <div>
                                    <h3>Order #<?php echo $order['id']; ?></h3>
                                    <p class="order-date">Placed on: <?php echo date('M d, Y', strtotime($order['created_at'])); ?></p>
                                </div>
                                <div>
                                    <span class="status-badge status-<?php echo $order['status']; ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="order-details">
                                <p><strong>Items:</strong> <?php echo $order['item_count']; ?></p>
                                <p><strong>Rental Period:</strong> <?php echo date('M d, Y', strtotime($order['start_date'])); ?> to <?php echo date('M d, Y', strtotime($order['end_date'])); ?></p>
                                <p><strong>Payment Method:</strong> <?php echo str_replace('_', ' ', ucwords($order['payment_method'])); ?></p>
                                <p><strong>Total Amount:</strong> ₹<?php echo number_format($order['total_price'], 2); ?></p>
                            </div>

                            <div class="order-actions">
                                <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-primary btn-small">View Details</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-orders">
                    <h3>No orders yet</h3>
                    <p>Start renting clothes to see your orders here!</p>
                    <a href="index.php" class="btn btn-primary">Browse Products</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Cloth Rental System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
