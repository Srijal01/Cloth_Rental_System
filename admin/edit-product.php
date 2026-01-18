<?php
session_start();
require_once '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$error = '';
$success = '';

// Get product ID
if (!isset($_GET['id'])) {
    header("Location: manage-products.php");
    exit();
}

$product_id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch product
$result = mysqli_query($conn, "SELECT * FROM products WHERE id = '$product_id'");
if (mysqli_num_rows($result) === 0) {
    header("Location: manage-products.php");
    exit();
}

$product = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $price = floatval($_POST['price_per_day']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Handle image upload
    $image = $product['image']; // Keep existing image by default
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
        $filename = $_FILES['image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            $newname = uniqid() . '.' . $filetype;
            $upload_path = '../assets/images/' . $newname;
            
            // Create directory if not exists
            if (!file_exists('../assets/images/')) {
                mkdir('../assets/images/', 0777, true);
            }
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // Delete old image if exists
                if ($product['image'] && file_exists('../assets/images/' . $product['image'])) {
                    unlink('../assets/images/' . $product['image']);
                }
                $image = $newname;
            } else {
                $error = "Failed to upload image. Check folder permissions.";
            }
        } else {
            $error = "Invalid file type. Allowed: JPG, JPEG, PNG, GIF, SVG, WEBP";
        }
    }
    
    // Update product
    $sql = "UPDATE products 
            SET name = '$name', 
                category = '$category', 
                size = '$size', 
                price_per_day = '$price', 
                image = '$image', 
                description = '$description' 
            WHERE id = '$product_id'";
    
    if (mysqli_query($conn, $sql)) {
        $success = "Product updated successfully!";
        // Refresh product data
        $result = mysqli_query($conn, "SELECT * FROM products WHERE id = '$product_id'");
        $product = mysqli_fetch_assoc($result);
    } else {
        $error = "Failed to update product!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Admin</title>
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
                <li><a href="manage-products.php">Products</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="../index.php">View Site</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Edit Product Section -->
    <section class="admin-section">
        <div class="container">
            <h2>Edit Product</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data" class="admin-form">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category" required>
                        <option value="men" <?php echo $product['category'] === 'men' ? 'selected' : ''; ?>>Men</option>
                        <option value="women" <?php echo $product['category'] === 'women' ? 'selected' : ''; ?>>Women</option>
                        <option value="ethnic" <?php echo $product['category'] === 'ethnic' ? 'selected' : ''; ?>>Ethnic</option>
                        <option value="party" <?php echo $product['category'] === 'party' ? 'selected' : ''; ?>>Party</option>
                        <option value="formal" <?php echo $product['category'] === 'formal' ? 'selected' : ''; ?>>Formal</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Size</label>
                    <input type="text" name="size" value="<?php echo htmlspecialchars($product['size']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Price Per Day (₹)</label>
                    <input type="number" name="price_per_day" step="0.01" value="<?php echo $product['price_per_day']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Current Image</label>
                    <?php if ($product['image'] && file_exists("../assets/images/" . $product['image'])): ?>
                        <div>
                            <img src="../assets/images/<?php echo $product['image']; ?>" style="max-width: 200px; border-radius: 5px;">
                        </div>
                    <?php else: ?>
                        <p>No image uploaded</p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Change Image (optional)</label>
                    <input type="file" name="image" accept="image/*">
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="manage-products.php" class="btn btn-secondary">Cancel</a>
            </form>
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
