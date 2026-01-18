<?php
// Generate password hash for admin123
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Password: admin123\n";
echo "Hash: " . $hash . "\n\n";

// Test verify
if (password_verify('admin123', $hash)) {
    echo "✓ Verification works!\n";
} else {
    echo "✗ Verification failed!\n";
}
?>
