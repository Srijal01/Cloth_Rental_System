<?php
session_start();
require_once 'config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = mysqli_real_escape_string($conn, $_GET['id']);

// Check if user owns this order or is admin
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$sql = "SELECT o.*, u.name as user_name, u.email as user_email, u.phone, u.address 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        WHERE o.id = '$order_id'";

if ($role !== 'admin') {
    $sql .= " AND o.user_id = '$user_id'";
}

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    header("Location: orders.php");
    exit();
}

$order = mysqli_fetch_assoc($result);

// Get order items
$items_sql = "SELECT oi.*, p.name as product_name, p.image, p.category, p.size 
              FROM order_items oi 
              JOIN products p ON oi.product_id = p.id 
              WHERE oi.order_id = '$order_id'";
$items_result = mysqli_query($conn, $items_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details #<?php echo $order['id']; ?> - Cloth Rental</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar <?php echo $role === 'admin' ? 'admin-navbar' : ''; ?>">
        <div class="container">
            <div class="nav-brand">
                <h1>Cloth Rental<?php echo $role === 'admin' ? ' - Admin' : ''; ?></h1>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
                <?php if ($role === 'admin'): ?>
                    <li><a href="admin/dashboard.php">Dashboard</a></li>
                    <li><a href="admin/orders.php">Orders</a></li>
                <?php else: ?>
                    <li><a href="orders.php">My Orders</a></li>
                    <li><a href="cart/view-cart.php">Cart</a></li>
                <?php endif; ?>
                <li><a href="auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Order Details Section -->
    <section class="orders-section">
        <div class="container">
            <h2>Order Details #<?php echo $order['id']; ?></h2>
            
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <h3>Order Information</h3>
                        <p class="order-date">Placed on: <?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></p>
                    </div>
                    <div>
                        <span class="status-badge status-<?php echo $order['status']; ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </div>
                </div>

                <div class="order-details">
                    <h4>Customer Details</h4>
                    <p><strong>Name:</strong> <?php echo $order['user_name']; ?></p>
                    <p><strong>Email:</strong> <?php echo $order['user_email']; ?></p>
                    <p><strong>Phone:</strong> <?php echo $order['phone'] ?: 'N/A'; ?></p>
                    <p><strong>Address:</strong> <?php echo $order['address'] ?: 'N/A'; ?></p>
                </div>

                <div class="order-details" style="margin-top: 1.5rem;">
                    <h4>Order Details</h4>
                    <p><strong>Rental Period:</strong> <?php echo date('M d, Y', strtotime($order['start_date'])); ?> to <?php echo date('M d, Y', strtotime($order['end_date'])); ?></p>
                    <p><strong>Payment Method:</strong> <?php echo str_replace('_', ' ', ucwords($order['payment_method'])); ?></p>
                    <p><strong>Total Amount:</strong> ₹<?php echo number_format($order['total_price'], 2); ?></p>
                </div>

                <div style="margin-top: 2rem;">
                    <h4>Items</h4>
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Details</th>
                                <th>Rental Period</th>
                                <th>Days</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
                                <tr>
                                    <td>
                                        <div class="cart-product-image">
                                            <?php if ($item['image'] && file_exists("assets/images/" . $item['image'])): ?>
                                                <img src="assets/images/<?php echo $item['image']; ?>" alt="<?php echo $item['product_name']; ?>">
                                            <?php else: ?>
                                                <div class="no-image-small">No Image</div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <strong><?php echo $item['product_name']; ?></strong><br>
                                        <small><?php echo ucfirst($item['category']); ?> | Size: <?php echo $item['size']; ?></small>
                                    </td>
                                    <td>
                                        <?php echo date('M d', strtotime($item['start_date'])); ?> - 
                                        <?php echo date('M d, Y', strtotime($item['end_date'])); ?>
                                    </td>
                                    <td><?php echo $item['days']; ?></td>
                                    <td>₹<?php echo number_format($item['price'], 2); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="order-actions">
                    <?php if ($role === 'admin'): ?>
                        <a href="admin/orders.php" class="btn btn-secondary">Back to Orders</a>
                    <?php else: ?>
                        <a href="orders.php" class="btn btn-secondary">Back to My Orders</a>
                    <?php endif; ?>
                </div>
            </div>
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
