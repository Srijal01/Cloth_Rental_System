<?php
session_start();
require_once '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Check if cart is empty
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    header("Location: view-cart.php");
    exit();
}

// Calculate cart total
$cart_total = 0;
foreach ($_SESSION['cart'] as $item) {
    $cart_total += $item['total_price'];
}

// Process checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    
    // Get earliest start and latest end date from cart
    $start_dates = array_column($_SESSION['cart'], 'start_date');
    $end_dates = array_column($_SESSION['cart'], 'end_date');
    $order_start = min($start_dates);
    $order_end = max($end_dates);
    
    // If eSewa payment, save order and redirect to eSewa
    if ($payment_method === 'esewa') {
        $_SESSION['pending_order'] = [
            'start_date' => $order_start,
            'end_date' => $order_end,
            'total' => $cart_total
        ];
        header("Location: ../payment/esewa-payment.php");
        exit();
    }
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Insert order
        $sql = "INSERT INTO orders (user_id, start_date, end_date, total_price, status, payment_method) 
                VALUES ('$user_id', '$order_start', '$order_end', '$cart_total', 'pending', '$payment_method')";
        
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Failed to create order");
        }
        
        $order_id = mysqli_insert_id($conn);
        
        // Insert order items
        foreach ($_SESSION['cart'] as $item) {
            $product_id = $item['product_id'];
            $price = $item['total_price'];
            $start = $item['start_date'];
            $end = $item['end_date'];
            $days = $item['days'];
            
            $sql = "INSERT INTO order_items (order_id, product_id, price, start_date, end_date, days) 
                    VALUES ('$order_id', '$product_id', '$price', '$start', '$end', '$days')";
            
            if (!mysqli_query($conn, $sql)) {
                throw new Exception("Failed to add order items");
            }
        }
        
        // Commit transaction
        mysqli_commit($conn);
        
        // Clear cart
        unset($_SESSION['cart']);
        
        // Set success message
        $_SESSION['success'] = "Order placed successfully! Order ID: #$order_id";
        header("Location: ../orders.php");
        exit();
        
    } catch (Exception $e) {
        // Rollback on error
        mysqli_rollback($conn);
        $_SESSION['error'] = "Failed to place order. Please try again.";
        header("Location: checkout.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Cloth Rental</title>
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
                <li><a href="view-cart.php">Cart</a></li>
                <li><a href="../auth/logout.php">Logout (<?php echo $_SESSION['user_name']; ?>)</a></li>
            </ul>
        </div>
    </nav>

    <!-- Checkout Section -->
    <section class="checkout-section">
        <div class="container">
            <h2>Checkout</h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <div class="checkout-grid">
                <!-- Order Summary -->
                <div class="order-summary">
                    <h3>Order Summary</h3>
                    <div class="summary-items">
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <div class="summary-item">
                                <div class="item-info">
                                    <strong><?php echo $item['product_name']; ?></strong>
                                    <p><?php echo date('M d', strtotime($item['start_date'])); ?> - <?php echo date('M d, Y', strtotime($item['end_date'])); ?> (<?php echo $item['days']; ?> days)</p>
                                </div>
                                <div class="item-price">
                                    ₹<?php echo number_format($item['total_price'], 2); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="summary-total">
                        <h3>Total Amount: ₹<?php echo number_format($cart_total, 2); ?></h3>
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="payment-form">
                    <h3>Payment Information</h3>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Payment Method</label>
                            <select name="payment_method" id="payment_method" required>
                                <option value="esewa">eSewa (Online Payment)</option>
                                <option value="cash_on_delivery">Cash on Delivery</option>
                                <option value="card">Credit/Debit Card</option>
                                <option value="upi">UPI Payment</option>
                                <option value="net_banking">Net Banking</option>
                            </select>
                        </div>

                        <div class="checkout-info">
                            <p><strong>Note:</strong> Payment will be collected upon delivery. Please keep the exact amount ready.</p>
                            <p>Your order will be confirmed within 24 hours.</p>
                        </div>

                        <button type="submit" class="btn btn-primary btn-large">Place Order</button>
                        <a href="view-cart.php" class="btn btn-secondary">Back to Cart</a>
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
</body>
</html>
