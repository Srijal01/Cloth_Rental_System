<?php
session_start();
require_once '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Check if admin
if ($_SESSION['role'] === 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $price_per_day = floatval($_POST['price_per_day']);
    
    // Calculate days
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $diff = $end->diff($start);
    $days = $diff->days + 1; // +1 to include both start and end days
    
    // Validate dates
    if ($days <= 0) {
        $_SESSION['error'] = "Invalid rental dates!";
        header("Location: ../product.php?id=$product_id");
        exit();
    }
    
    // Get product details
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id = '$product_id' AND available = 1");
    if (mysqli_num_rows($result) === 0) {
        $_SESSION['error'] = "Product not available!";
        header("Location: ../index.php");
        exit();
    }
    
    $product = mysqli_fetch_assoc($result);
    
    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Add to cart
    $cart_item = [
        'product_id' => $product_id,
        'product_name' => $product['name'],
        'product_image' => $product['image'],
        'category' => $product['category'],
        'size' => $product['size'],
        'start_date' => $start_date,
        'end_date' => $end_date,
        'days' => $days,
        'price_per_day' => $price_per_day,
        'total_price' => $days * $price_per_day
    ];
    
    $_SESSION['cart'][] = $cart_item;
    $_SESSION['success'] = "Item added to cart successfully!";
    
    header("Location: view-cart.php");
    exit();
} else {
    header("Location: ../index.php");
    exit();
}
?>
