<?php
session_start();
require_once '../config/db.php';
require_once '../config/esewa-config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Check if pending order exists
if (!isset($_SESSION['pending_order']) || !isset($_SESSION['cart'])) {
    header("Location: ../cart/view-cart.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$pending_order = $_SESSION['pending_order'];
$cart = $_SESSION['cart'];

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Insert order
    $sql = "INSERT INTO orders (user_id, start_date, end_date, total_price, status, payment_method, payment_status) 
            VALUES ('$user_id', '{$pending_order['start_date']}', '{$pending_order['end_date']}', '{$pending_order['total']}', 'pending', 'esewa', 'unpaid')";
    
    if (!mysqli_query($conn, $sql)) {
        throw new Exception("Failed to create order");
    }
    
    $order_id = mysqli_insert_id($conn);
    
    // Insert order items
    foreach ($cart as $item) {
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
    
    // Store order ID in session for verification
    $_SESSION['esewa_order_id'] = $order_id;
    
    // Calculate eSewa parameters
    $amount = $pending_order['total'];
    $tax_amount = ESEWA_TAX_AMOUNT;
    $service_charge = ESEWA_SERVICE_CHARGE;
    $delivery_charge = ESEWA_DELIVERY_CHARGE;
    $total_amount = $amount + $tax_amount + $service_charge + $delivery_charge;
    
    // Generate unique transaction UUID
    $transaction_uuid = "ORDER-" . $order_id . "-" . time();
    
    // Create message for signature (API v2)
    $message = "total_amount={$total_amount},transaction_uuid={$transaction_uuid},product_code=" . ESEWA_MERCHANT_ID;
    $signature = base64_encode(hash_hmac('sha256', $message, ESEWA_SECRET, true));
    
} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['error'] = "Failed to create order. Please try again.";
    header("Location: ../cart/checkout.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Payment - eSewa</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .payment-processing {
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .payment-box {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 500px;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007bff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .esewa-logo {
            width: 150px;
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <div class="payment-processing">
        <div class="payment-box">
            <h2>Redirecting to eSewa...</h2>
            <div class="spinner"></div>
            <p>Please wait while we redirect you to eSewa payment gateway.</p>
            <p><strong>Order ID:</strong> #<?php echo $order_id; ?></p>
            <p><strong>Amount:</strong> NPR <?php echo number_format($total_amount, 2); ?></p>
            
            <!-- eSewa Payment Form (API v2) -->
            <form id="esewaForm" action="<?php echo ESEWA_PAYMENT_URL; ?>" method="POST">
                <input type="hidden" name="amount" value="<?php echo $amount; ?>">
                <input type="hidden" name="tax_amount" value="<?php echo $tax_amount; ?>">
                <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">
                <input type="hidden" name="transaction_uuid" value="<?php echo $transaction_uuid; ?>">
                <input type="hidden" name="product_code" value="<?php echo ESEWA_MERCHANT_ID; ?>">
                <input type="hidden" name="product_service_charge" value="<?php echo $service_charge; ?>">
                <input type="hidden" name="product_delivery_charge" value="<?php echo $delivery_charge; ?>">
                <input type="hidden" name="success_url" value="<?php echo ESEWA_SUCCESS_URL; ?>">
                <input type="hidden" name="failure_url" value="<?php echo ESEWA_FAILURE_URL; ?>">
                <input type="hidden" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
                <input type="hidden" name="signature" value="<?php echo $signature; ?>">
                
                <noscript>
                    <button type="submit" class="btn btn-primary">Click here if not redirected automatically</button>
                </noscript>
            </form>
        </div>
    </div>

    <script>
        // Auto-submit form after 2 seconds
        setTimeout(function() {
            document.getElementById('esewaForm').submit();
        }, 2000);
    </script>
</body>
</html>
