<?php

define('ESEWA_MERCHANT_ID', 'YOUR_MERCHANT_ID_HERE');
define('ESEWA_SECRET', 'YOUR_SECRET_KEY_HERE');

define('ESEWA_PAYMENT_URL', 'https://rc-epay.esewa.com.np/api/epay/main/v2/form');
define('ESEWA_PAYMENT_STATUS_CHECK_URL', 'https://rc.esewa.com.np/api/epay/transaction/status/');

define('ESEWA_SUCCESS_URL', 'http://localhost/Cloth_rental_system/payment/esewa-success.php');
define('ESEWA_FAILURE_URL', 'http://localhost/Cloth_rental_system/payment/esewa-failure.php');

define('ESEWA_SERVICE_CHARGE', 0);
define('ESEWA_DELIVERY_CHARGE', 0);
define('ESEWA_TAX_AMOUNT', 0);
?>
