# 👗 Cloth Rental System

<div align="center">

![Cloth Rental System](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

**A modern, full-featured web-based cloth rental management system**

Perfect for boutiques, fashion rental businesses, and event costume shops

[Features](#-features) • [Installation](#-installation) • [Usage](#-usage) • [Screenshots](#-screenshots) • [Contributing](#-contributing)

</div>

---

## 📖 About

A complete web-based cloth rental management system built with **PHP**, **MySQL**, **HTML**, **CSS**, and **JavaScript**. This elegant system allows customers to browse and rent clothes for specific dates, manage their orders seamlessly, while administrators can efficiently manage inventory, process orders, and track rentals in real-time.

## ✨ Features

### 🛍️ Customer Features
- 🔐 **Secure Authentication** - User registration and login with password encryption
- 📱 **Responsive Catalog** - Browse clothes by categories (Men, Women, Ethnic, Party, Formal)
- 🔍 **Detailed Product Views** - High-quality images, descriptions, sizes, and pricing
- 📅 **Smart Date Selection** - Choose rental periods with real-time price calculation
- 🛒 **Dynamic Shopping Cart** - Add multiple items with different rental durations
- 💳 **Multiple Payment Options** - Cash on Delivery, eSewa integration
- 📦 **Order Tracking** - Complete order history with real-time status updates
- 🔔 **Status Notifications** - Track your rental from confirmation to return

### ⚙️ Admin Features
- 📊 **Comprehensive Dashboard** - Real-time statistics for products, orders, and revenue
- 📦 **Inventory Management** - Add, edit, delete products with image uploads
- 👔 **Product Availability** - Toggle product status and manage stock
- 📋 **Order Management** - View, filter, and process all customer orders
- 🔄 **Status Workflow** - Update orders: Pending → Confirmed → Rented → Returned
- 👥 **User Overview** - Track customer accounts and rental history

## 🛠️ Technology Stack

<table>
<tr>
<td align="center" width="50%">

**Frontend**
```
HTML5
CSS3
JavaScript (ES6+)
Responsive Design
```

</td>
<td align="center" width="50%">

**Backend**
```
PHP 7.4+
MySQL 5.7+
Apache Server
Session Management
```

</td>
</tr>
</table>

### 🔧 Key Technologies
| Component | Technology | Purpose |
|-----------|------------|---------|
| **Frontend** | HTML5, CSS3, JavaScript | User interface and interactions |
| **Backend** | PHP 7.4+ | Server-side logic and processing |
| **Database** | MySQL 5.7+ | Data storage and management |
| **Server** | Apache (XAMPP) | Web server environment |
| **Payment** | eSewa API | Payment gateway integration |
| **Security** | Password Hashing, SQL Injection Prevention | Data protection |

## 📁 Project Structure

```
Cloth_rental_system/
│
├── 📁 assets/
│   ├── 📁 css/
│   │   └── style.css              # Main stylesheet with modern design
│   ├── 📁 js/
│   │   └── rental-calculator.js   # Real-time date & price calculator
│   └── 📁 images/                 # Product images and assets
│
├── 📁 config/
│   ├── db.example.php             # Database config template (COMMIT THIS)
│   ├── db.php                     # Database connection (DO NOT COMMIT)
│   ├── esewa-config.example.php   # Payment config template (COMMIT THIS)
│   ├── esewa-config.php           # Payment gateway config (DO NOT COMMIT)
│   └── session.php                # Session helper functions
│
├── 📁 auth/
│   ├── login.php                  # User authentication
│   ├── register.php               # New user registration
│   └── logout.php                 # Session termination
│
├── 📁 admin/
│   ├── dashboard.php              # Admin control panel
│   ├── add-product.php            # Product creation
│   ├── edit-product.php           # Product editing
│   ├── manage-products.php        # Product inventory management
│   └── orders.php                 # Order processing
│
├── 📁 cart/
│   ├── add-to-cart.php            # Cart management handler
│   ├── view-cart.php              # Shopping cart interface
│   └── checkout.php               # Order processing
│
├── 📁 database/
│   ├── setup.sql                  # Database schema
│   └── update_esewa.sql           # Payment table updates
│
├── 📁 payment/
│   ├── esewa-payment.php          # Payment processing
│   ├── esewa-success.php          # Success callback
│   └── esewa-failure.php          # Failure handling
│
├── 🏠 index.php                   # Homepage (product catalog)
├── 👔 product.php                 # Product details page
├── 📋 orders.php                  # User order history
├── 📄 order-details.php           # Detailed order view
├── 🔒 generate_hash.php           # eSewa hash generator
├── 📝 README.md                   # This documentation
└── 🚫 .gitignore                  # Git exclusions
```

## 🚀 Installation

### Prerequisites
- ✅ PHP 7.4 or higher
- ✅ MySQL 5.7 or higher
- ✅ Apache server (XAMPP/WAMP/LAMP recommended)
- ✅ Modern web browser (Chrome, Firefox, Safari, Edge)

### 📥 Setup Steps

#### 1️⃣ Clone the repository
```bash
git clone https://github.com/yourusername/cloth-rental-system.git
cd Cloth_rental_system
```

#### 2️⃣ Create configuration files from templates
```bash
cp config/db.example.php config/db.php
cp config/esewa-config.example.php config/esewa-config.php
```

#### 3️⃣ Configure database connection
Edit `config/db.php` and update your database credentials:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
define('DB_NAME', 'cloth_rental');
```

#### 4️⃣ Configure eSewa payment gateway (Optional)
Edit `config/esewa-config.php` and add your eSewa credentials:
```php
define('ESEWA_MERCHANT_ID', 'your_merchant_id');
define('ESEWA_SECRET', 'your_secret_key');
```

#### 5️⃣ Set up the database
Create a MySQL database and import the schema:
```bash
mysql -u root -p
CREATE DATABASE cloth_rental;
USE cloth_rental;
SOURCE database/setup.sql;
```

Or via phpMyAdmin:
- Create database `cloth_rental`
- Import `database/setup.sql`

#### 6️⃣ Configure your web server
**For XAMPP:** Place project in `htdocs` folder
```
C:\xampp\htdocs\Cloth_rental_system\
```
Access via: `http://localhost/Cloth_rental_system/`

**Or use PHP built-in server:**
```bash
php -S localhost:8000
```

#### 7️⃣ Default admin credentials
```
📧 Email: admin@admin.com
🔑 Password: admin123
```
⚠️ **Important:** Change these credentials after first login!

## 🔐 Security Notes

**Important:** This project uses `.gitignore` to protect sensitive files:
- ✋ `config/db.php` - Contains database credentials
- ✋ `config/esewa-config.php` - Contains payment gateway keys

⚠️ **Never commit these files to version control!**

✅ Always use the `.example.php` templates and copy them locally with your actual credentials.

### 🛡️ Security Features Implemented
- 🔒 Password hashing using PHP `password_hash()`
- 🔐 SQL injection prevention using `mysqli_real_escape_string()`
- 🔑 Session-based authentication
- 👤 Role-based access control (User/Admin)
- 🚫 Protected admin routes
- ✅ Input validation and sanitization

## 📊 Database Schema

<details>
<summary><b>👥 Users Table</b></summary>

```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
</details>

<details>
<summary><b>👔 Products Table</b></summary>

```sql
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    category VARCHAR(50) NOT NULL,
    size VARCHAR(20) NOT NULL,
    price_per_day DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    available BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
</details>

<details>
<summary><b>📦 Orders Table</b></summary>

```sql
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending','confirmed','rented','returned','cancelled') DEFAULT 'pending',
    payment_method VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```
</details>

<details>
<summary><b>📋 Order Items Table</b></summary>

```sql
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    days INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```
</details>

## 📖 Usage Guide

### 👤 For Customers

<table>
<tr><td>

**1️⃣ Register/Login**
- Create a new account or login to existing account
- Secure authentication with encrypted passwords

</td></tr>
<tr><td>

**2️⃣ Browse Products**
- View all available products on homepage
- Filter by categories (Men, Women, Ethnic, Party, Formal)
- Search for specific items

</td></tr>
<tr><td>

**3️⃣ Rent a Product**
- Click on product for details
- Select rental start and end dates
- View automatically calculated price
- Add to shopping cart

</td></tr>
<tr><td>

**4️⃣ Checkout**
- Review all cart items
- Proceed to checkout
- Choose payment method (COD/eSewa)
- Confirm and place order

</td></tr>
<tr><td>

**5️⃣ Track Orders**
- View complete order history
- Real-time order status tracking
- Download order details

</td></tr>
</table>

### ⚙️ For Administrators

<table>
<tr><td>

**1️⃣ Access Admin Panel**
- Login with admin credentials
- Secure dashboard access

</td></tr>
<tr><td>

**2️⃣ View Dashboard**
- Real-time statistics
- Product inventory overview
- Recent orders summary
- Revenue analytics

</td></tr>
<tr><td>

**3️⃣ Manage Products**
- Add new clothing items
- Edit product details
- Upload/update product images
- Toggle product availability
- Delete outdated items

</td></tr>
<tr><td>

**4️⃣ Process Orders**
- View all customer orders
- Filter orders by status
- Update order workflow
- View customer details
- Manage returns

</td></tr>
</table>

## 🎨 Features Showcase

### 💫 User Interface Highlights
- ✨ Modern and clean design
- 📱 Fully responsive layout
- 🎨 Beautiful color scheme with purple accents
- 🖼️ Product image galleries
- 🌊 Smooth animations and transitions
- 📊 Interactive date picker
- 💰 Real-time price calculator

### 🔄 Order Status Workflow
```
📝 Pending → ✅ Confirmed → 👔 Rented → ↩️ Returned
```

## 🤝 Contributing

We welcome contributions! Here's how you can help:

1. 🍴 Fork the repository
2. 🌿 Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. 💾 Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. 📤 Push to the branch (`git push origin feature/AmazingFeature`)
5. 🎉 Open a Pull Request

### 💡 Ideas for Contributions
- Add more payment gateway integrations
- Implement email notifications
- Add product reviews and ratings
- Create mobile app version
- Add advanced search and filters
- Implement wishlist functionality
- Add discount coupons system

## 🐛 Known Issues & Troubleshooting

<details>
<summary><b>Database Connection Issues</b></summary>

**Problem:** Cannot connect to database

**Solution:**
- Check XAMPP/MySQL is running
- Verify credentials in `config/db.php`
- Ensure database `cloth_rental` exists
- Check user permissions
</details>

<details>
<summary><b>Image Upload Not Working</b></summary>

**Problem:** Product images not uploading

**Solution:**
- Check `assets/images/` folder exists
- Verify folder has write permissions (chmod 755)
- Check PHP `upload_max_filesize` in php.ini
- Ensure image size is under limit
</details>

<details>
<summary><b>Session Errors</b></summary>

**Problem:** Session not starting or working

**Solution:**
- Check PHP session directory has write permissions
- Verify `session.php` is included in files
- Clear browser cookies
- Restart Apache server
</details>

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**Your Name**
- 📧 Email: your.email@example.com
- 🐙 GitHub: [@yourusername](https://github.com/yourusername)
- 💼 LinkedIn: [Your Name](https://linkedin.com/in/yourprofile)

## 🙏 Acknowledgments

- Icons by [Font Awesome](https://fontawesome.com/)
- Fonts from [Google Fonts](https://fonts.google.com/)
- Inspiration from modern e-commerce platforms
- Thanks to all contributors

## 📞 Support

For support, email your.email@example.com or create an issue in the GitHub repository.

---

<div align="center">

**⭐ Star this repo if you find it helpful! ⭐**

Made with ❤️ by [Your Name]

</div>
- Input validation and sanitization

## Customization

### Change Colors
Edit `assets/css/style.css` and modify color values:
```css
/* Primary color */
.btn-primary { background-color: #3498db; }

/* Navigation */
.navbar { background-color: #2c3e50; }
```

### Add Categories
1. Modify dropdown in `admin/add-product.php`
2. Add category button in `index.php`

### Payment Gateway Integration
Replace the payment form in `cart/checkout.php` with your payment gateway integration (Stripe, PayPal, etc.)

## Troubleshooting

### Database Connection Error
- Verify MySQL is running in XAMPP
- Check database credentials in `config/db.php`
- Ensure database exists

### Images Not Displaying
- Check file permissions on `assets/images/` folder
- Verify image paths are correct
- Ensure images are in supported formats (JPG, PNG, GIF)

### Session Issues
- Check that session is started in PHP
- Verify session storage permissions

### Blank Pages
- Enable PHP error reporting in `php.ini`:
  ```ini
  display_errors = On
  error_reporting = E_ALL
  ```

## License

This project is open-source and available for educational purposes.

## Developer Notes

- Built with procedural PHP for simplicity
- Can be converted to OOP for better structure
- Database uses transactions for order placement
- Responsive design using CSS Grid and Flexbox
- JavaScript used for real-time price calculation

## Acknowledgments

Built as a learning project for PHP/MySQL web development.

---

**Happy Coding!**
