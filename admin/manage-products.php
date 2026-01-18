<?php
session_start();
require_once '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM products WHERE id = '$id'");
    $_SESSION['success'] = "Product deleted successfully!";
    header("Location: manage-products.php");
    exit();
}

// Handle toggle availability
if (isset($_GET['toggle'])) {
    $id = mysqli_real_escape_string($conn, $_GET['toggle']);
    mysqli_query($conn, "UPDATE products SET available = NOT available WHERE id = '$id'");
    header("Location: manage-products.php");
    exit();
}

// Fetch all products
$result = mysqli_query($conn, "SELECT * FROM products ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin</title>
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
                <li><a href="manage-products.php" class="active">Products</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="../index.php">View Site</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Manage Products Section -->
    <section class="admin-section">
        <div class="container">
            <div class="section-header">
                <h2>Manage Products</h2>
                <a href="add-product.php" class="btn btn-primary">+ Add New Product</a>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Size</th>
                            <th>Price/Day</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($product = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $product['id']; ?></td>
                                <td>
                                    <?php if ($product['image'] && file_exists("../assets/images/" . $product['image'])): ?>
                                        <img src="../assets/images/<?php echo $product['image']; ?>" class="table-image" alt="<?php echo $product['name']; ?>">
                                    <?php else: ?>
                                        <div class="no-image-tiny">No Image</div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $product['name']; ?></td>
                                <td><?php echo ucfirst($product['category']); ?></td>
                                <td><?php echo $product['size']; ?></td>
                                <td>₹<?php echo number_format($product['price_per_day'], 2); ?></td>
                                <td>
                                    <?php if ($product['available']): ?>
                                        <span class="status-badge status-available">Available</span>
                                    <?php else: ?>
                                        <span class="status-badge status-unavailable">Unavailable</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="btn btn-small btn-primary">Edit</a>
                                    <a href="?toggle=<?php echo $product['id']; ?>" class="btn btn-small btn-warning">Toggle</a>
                                    <a href="?delete=<?php echo $product['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Delete this product?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No products found. <a href="add-product.php">Add your first product</a></p>
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
