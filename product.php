<?php
session_start();
require_once 'config/db.php';

// Get product ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$product_id = mysqli_real_escape_string($conn, $_GET['id']);
$sql = "SELECT * FROM products WHERE id = '$product_id' AND available = 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    header("Location: index.php");
    exit();
}

$product = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?> - Cloth Rental</title>
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
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li><a href="admin/dashboard.php">Admin Panel</a></li>
                    <?php else: ?>
                        <li><a href="orders.php">My Orders</a></li>
                        <li><a href="cart/view-cart.php">Cart</a></li>
                    <?php endif; ?>
                    <li><a href="auth/logout.php">Logout (<?php echo $_SESSION['user_name']; ?>)</a></li>
                <?php else: ?>
                    <li><a href="auth/login.php">Login</a></li>
                    <li><a href="auth/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Product Details -->
    <section class="product-detail">
        <div class="container">
            <div class="product-detail-grid">
                <!-- Product Image -->
                <div class="product-detail-image">
                    <?php if ($product['image'] && file_exists("assets/images/" . $product['image'])): ?>
                        <img src="assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                    <?php else: ?>
                        <div class="no-image-large">No Image Available</div>
                    <?php endif; ?>
                </div>

                <!-- Product Info -->
                <div class="product-detail-info">
                    <h1><?php echo $product['name']; ?></h1>
                    <p class="category-badge"><?php echo ucfirst($product['category']); ?></p>
                    
                    <div class="product-meta">
                        <p><strong>Size:</strong> <?php echo $product['size']; ?></p>
                        <p><strong>Price:</strong> ₹<?php echo number_format($product['price_per_day'], 2); ?> per day</p>
                    </div>

                    <div class="product-description">
                        <h3>Description</h3>
                        <p><?php echo $product['description']; ?></p>
                    </div>

                    <!-- Rental Form -->
                    <form action="cart/add-to-cart.php" method="POST" class="rental-form" id="rentalForm">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="price_per_day" id="pricePerDay" value="<?php echo $product['price_per_day']; ?>">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Start Date</label>
                                <input type="date" name="start_date" id="startDate" required min="<?php echo date('Y-m-d'); ?>" onchange="calculateTotal()">
                            </div>
                            
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="date" name="end_date" id="endDate" required min="<?php echo date('Y-m-d'); ?>" onchange="calculateTotal()">
                            </div>
                        </div>

                        <div class="rental-summary">
                            <p><strong>Rental Duration:</strong> <span id="days">0</span> day(s)</p>
                            <p><strong>Total Price:</strong> ₹<span id="totalPrice">0.00</span></p>
                        </div>

                        <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] !== 'admin'): ?>
                            <button type="submit" class="btn btn-primary btn-large">Add to Cart</button>
                        <?php elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>
                            <p class="warning">Admin accounts cannot rent products.</p>
                        <?php else: ?>
                            <a href="auth/login.php" class="btn btn-primary btn-large">Login to Rent</a>
                        <?php endif; ?>
                    </form>
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

    <script src="assets/js/rental-calculator.js"></script>
</body>
</html>
