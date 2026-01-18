<?php
session_start();
require_once '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Get order ID if exists
$order_id = $_SESSION['esewa_order_id'] ?? null;

// Update order status to failed if order was created
if ($order_id) {
    $sql = "UPDATE orders SET payment_status = 'failed', status = 'cancelled' WHERE id = '$order_id'";
    mysqli_query($conn, $sql);
    
    unset($_SESSION['esewa_order_id']);
}

// Clear pending order but keep cart
unset($_SESSION['pending_order']);

$_SESSION['error'] = "Payment cancelled or failed. Your cart items are still saved. Please try again.";
header("Location: ../cart/view-cart.php");
exit();
?>
