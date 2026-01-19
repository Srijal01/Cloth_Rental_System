<?php
/**
 * Homepage - Product Catalog
 * 
 * Displays all available products with category filtering
 * Users can browse and view products by category
 * 
 * @package ClothRentalSystem
 * @author Your Name
 */

session_start();
require_once 'config/db.php';

// ============================================
// GET CATEGORY FILTER FROM URL
// ============================================
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';

// ============================================
// BUILD PRODUCT QUERY
// ============================================
$sql = "SELECT * FROM products WHERE available = 1";

// Add category filter if specified
if ($category) {
    $sql .= " AND category = '$category'";
}

// Sort by newest first
$sql .= " ORDER BY created_at DESC";

// Execute query
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Rent beautiful clothes for any occasion. Browse our collection of ethnic wear, party outfits, formal attire and more.">
    <meta name="keywords" content="cloth rental, dress rental, clothing rental system, rent clothes online">
    <title>Cloth Rental System - Rent Beautiful Clothes for Every Occasion</title>
    
    <!-- Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
</head>
<body class="fade-in">
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h2>Rent Your Perfect Outfit</h2>
            <p>Discover amazing clothes for every occasion</p>
        </div>
    </section>

    <!-- Category Filter -->
    <section class="categories">
        <div class="container">
            <div class="category-buttons">
                <a href="index.php" class="btn <?php echo !$category ? 'btn-active' : ''; ?>">All</a>
                <a href="?category=men" class="btn <?php echo $category === 'men' ? 'btn-active' : ''; ?>">Men</a>
                <a href="?category=women" class="btn <?php echo $category === 'women' ? 'btn-active' : ''; ?>">Women</a>
                <a href="?category=ethnic" class="btn <?php echo $category === 'ethnic' ? 'btn-active' : ''; ?>">Ethnic</a>
                <a href="?category=party" class="btn <?php echo $category === 'party' ? 'btn-active' : ''; ?>">Party</a>
            </div>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="products-section">
        <div class="container">
            <div class="products-grid">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($product = mysqli_fetch_assoc($result)): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <?php if ($product['image'] && file_exists("assets/images/" . $product['image'])): ?>
                                    <img src="assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                                <?php else: ?>
                                    <div class="no-image">No Image</div>
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <h3><?php echo $product['name']; ?></h3>
                                <p class="category"><?php echo ucfirst($product['category']); ?> | Size: <?php echo $product['size']; ?></p>
                                <p class="description"><?php echo substr($product['description'], 0, 80); ?>...</p>
                                <div class="product-footer">
                                    <span class="price">₹<?php echo number_format($product['price_per_day'], 2); ?> / day</span>
                                    <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">Rent Now</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="no-products">No products available in this category.</p>
                <?php endif; ?>
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
