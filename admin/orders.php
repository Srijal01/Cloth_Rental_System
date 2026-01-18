<?php
session_start();
require_once '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Handle status update
if (isset($_POST['update_status'])) {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    
    mysqli_query($conn, "UPDATE orders SET status = '$new_status' WHERE id = '$order_id'");
    $_SESSION['success'] = "Order status updated!";
    header("Location: orders.php");
    exit();
}

// Get filter
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';

// Fetch orders
$sql = "SELECT o.*, u.name as user_name, u.email as user_email 
        FROM orders o 
        JOIN users u ON o.user_id = u.id";

if ($status_filter) {
    $sql .= " WHERE o.status = '$status_filter'";
}

$sql .= " ORDER BY o.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Admin</title>
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
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage-products.php">Products</a></li>
                <li><a href="orders.php" class="active">Orders</a></li>
                <li><a href="../index.php">View Site</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Manage Orders Section -->
    <section class="admin-section">
        <div class="container">
            <h2>Manage Orders</h2>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <!-- Filter -->
            <div class="filter-buttons">
                <a href="orders.php" class="btn <?php echo !$status_filter ? 'btn-active' : ''; ?>">All</a>
                <a href="?status=pending" class="btn <?php echo $status_filter === 'pending' ? 'btn-active' : ''; ?>">Pending</a>
                <a href="?status=confirmed" class="btn <?php echo $status_filter === 'confirmed' ? 'btn-active' : ''; ?>">Confirmed</a>
                <a href="?status=rented" class="btn <?php echo $status_filter === 'rented' ? 'btn-active' : ''; ?>">Rented</a>
                <a href="?status=returned" class="btn <?php echo $status_filter === 'returned' ? 'btn-active' : ''; ?>">Returned</a>
            </div>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Rental Period</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td>
                                    <?php echo $order['user_name']; ?><br>
                                    <small><?php echo $order['user_email']; ?></small>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <?php echo date('M d', strtotime($order['start_date'])); ?> - 
                                    <?php echo date('M d, Y', strtotime($order['end_date'])); ?>
                                </td>
                                <td>₹<?php echo number_format($order['total_price'], 2); ?></td>
                                <td>
                                    <form method="POST" action="" style="display:inline;">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <select name="status" onchange="this.form.submit()">
                                            <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="confirmed" <?php echo $order['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                            <option value="rented" <?php echo $order['status'] === 'rented' ? 'selected' : ''; ?>>Rented</option>
                                            <option value="returned" <?php echo $order['status'] === 'returned' ? 'selected' : ''; ?>>Returned</option>
                                            <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                        <input type="hidden" name="update_status" value="1">
                                    </form>
                                </td>
                                <td>
                                    <a href="../order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-small btn-primary">View Details</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No orders found.</p>
            <?php endif; ?>
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
