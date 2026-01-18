<?php
session_start();
require_once '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Handle remove item
if (isset($_GET['remove'])) {
    $index = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index array
        $_SESSION['success'] = "Item removed from cart!";
    }
    header("Location: view-cart.php");
    exit();
}

// Calculate cart total
$cart_total = 0;
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_total += $item['total_price'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Cloth Rental</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <h1>Cloth Rental</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="../index.php">Home</a></li>
                <li><a href="../orders.php">My Orders</a></li>
                <li><a href="view-cart.php" class="active">Cart</a></li>
                <li><a href="../auth/logout.php">Logout (<?php echo $_SESSION['user_name']; ?>)</a></li>
            </ul>
        </div>
    </nav>

    <!-- Cart Section -->
    <section class="cart-section">
        <div class="container">
            <h2>Shopping Cart</h2>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                <div class="cart-items">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Details</th>
                                <th>Rental Period</th>
                                <th>Days</th>
                                <th>Price/Day</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                                <tr>
                                    <td>
                                        <div class="cart-product-image">
                                            <?php if ($item['product_image'] && file_exists("../assets/images/" . $item['product_image'])): ?>
                                                <img src="../assets/images/<?php echo $item['product_image']; ?>" alt="<?php echo $item['product_name']; ?>">
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
                                        <?php echo date('M d, Y', strtotime($item['start_date'])); ?><br>
                                        to<br>
                                        <?php echo date('M d, Y', strtotime($item['end_date'])); ?>
                                    </td>
                                    <td><?php echo $item['days']; ?></td>
                                    <td>₹<?php echo number_format($item['price_per_day'], 2); ?></td>
                                    <td><strong>₹<?php echo number_format($item['total_price'], 2); ?></strong></td>
                                    <td>
                                        <a href="?remove=<?php echo $index; ?>" class="btn btn-danger btn-small" onclick="return confirm('Remove this item?')">Remove</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="cart-summary">
                        <h3>Cart Summary</h3>
                        <p>Total Items: <?php echo count($_SESSION['cart']); ?></p>
                        <h2>Grand Total: ₹<?php echo number_format($cart_total, 2); ?></h2>
                        <a href="checkout.php" class="btn btn-primary btn-large">Proceed to Checkout</a>
                        <a href="../index.php" class="btn btn-secondary">Continue Shopping</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-cart">
                    <h3>Your cart is empty</h3>
                    <p>Start adding items to your cart!</p>
                    <a href="../index.php" class="btn btn-primary">Browse Products</a>
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
