# Cloth Rental System

A complete web-based cloth rental management system built with PHP, MySQL, HTML, CSS, and JavaScript. This system allows users to browse, rent clothes for specific dates, and manage their orders, while admins can manage products, view orders, and update order statuses.

## Features

### User Features
- **User Registration & Login** - Secure authentication system
- **Browse Products** - View clothes by categories (Men, Women, Ethnic, Party, Formal)
- **Product Details** - See detailed information with images and descriptions
- **Rental Date Selection** - Choose start and end dates with automatic price calculation
- **Shopping Cart** - Add multiple items with different rental periods
- **Checkout System** - Place orders with multiple payment options
- **Order History** - Track all past and current orders
- **Order Status Tracking** - View real-time order status updates

### Admin Features
- **Admin Dashboard** - Overview of products, orders, and users statistics
- **Product Management** - Add, edit, delete, and toggle product availability
- **Order Management** - View all orders and update their status
- **Status Updates** - Change order status (Pending → Confirmed → Rented → Returned)

## Technology Stack

| Component | Technology |
|-----------|------------|
| Frontend  | HTML5, CSS3, JavaScript |
| Backend   | PHP 7.4+ |
| Database  | MySQL 5.7+ |
| Server    | Apache (XAMPP) |

## Project Structure

```
Cloth_rental_system/
│
├── assets/
│   ├── css/
│   │   └── style.css          # Main stylesheet
│   ├── js/
│   │   └── rental-calculator.js  # Date & price calculator
│   └── images/                # Product images
│
├── config/
│   ├── db.example.php         # Database config template (COMMIT THIS)
│   ├── db.php                 # Database connection (DO NOT COMMIT)
│   ├── esewa-config.example.php  # Payment config template (COMMIT THIS)
│   ├── esewa-config.php       # Payment gateway config (DO NOT COMMIT)
│   └── session.php            # Session helper functions
│
├── auth/
│   ├── login.php              # User login
│   ├── register.php           # User registration
│   └── logout.php             # Logout handler
│
├── admin/
│   ├── dashboard.php          # Admin dashboard
│   ├── add-product.php        # Add new products
│   ├── manage-products.php    # View/edit/delete products
│   └── orders.php             # Manage orders
│
├── cart/
│   ├── add-to-cart.php        # Add to cart handler
│   ├── view-cart.php          # Shopping cart page
│   └── checkout.php           # Checkout process
│
├── database/
│   └── setup.sql              # Database schema
│
├── payment/
│   ├── esewa-payment.php      # eSewa payment handler
│   ├── esewa-success.php      # Payment success callback
│   └── esewa-failure.php      # Payment failure callback
│
├── .gitignore                 # Git ignore file
├── index.php                  # Homepage (product listing)
├── product.php                # Product details page
├── orders.php                 # User orders page
├── order-details.php          # Order details page
└── README.md                  # This file
```

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache server (XAMPP/WAMP/LAMP)
- Web browser

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <your-repository-url>
   cd Cloth_rental_system
   ```

2. **Create configuration files from templates**
   ```bash
   cp config/db.example.php config/db.php
   cp config/esewa-config.example.php config/esewa-config.php
   ```

3. **Configure database connection**
   - Edit `config/db.php`
   - Update database credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', 'your_password');
     define('DB_NAME', 'cloth_rental');
     ```

4. **Configure eSewa payment gateway**
   - Edit `config/esewa-config.php`
   - Add your eSewa credentials:
     ```php
     define('ESEWA_MERCHANT_ID', 'your_merchant_id');
     define('ESEWA_SECRET', 'your_secret_key');
     ```
   - Update success/failure URLs to match your domain

5. **Set up the database**
   - Create a MySQL database named `cloth_rental`
   - Import the SQL schema:
     ```bash
     mysql -u root -p cloth_rental < database/setup.sql
     ```

6. **Configure your web server**
   - For XAMPP: Place project in `htdocs` folder
   - Access via: `http://localhost/Cloth_rental_system/`
   - Or use PHP built-in server:
     ```bash
     php -S localhost:8000
     ```

7. **Default admin credentials**
   - Email: admin@example.com
   - Password: admin123
   - (Change these after first login)

## Security Notes

🔒 **Important:** This project uses `.gitignore` to protect sensitive files:

- `config/db.php` - Contains database credentials
- `config/esewa-config.php` - Contains payment gateway keys

**Never commit these files to version control!**

Always use the `.example.php` templates and copy them locally with your actual credentials.

## Database Schema

### Users Table
```sql
- id (INT, Primary Key)
- name (VARCHAR)
- email (VARCHAR, Unique)
- password (VARCHAR, Hashed)
- phone (VARCHAR)
- address (TEXT)
- role (ENUM: 'user', 'admin')
- created_at (TIMESTAMP)
```

### Products Table
```sql
- id (INT, Primary Key)
- name (VARCHAR)
- category (VARCHAR)
- size (VARCHAR)
- price_per_day (DECIMAL)
- image (VARCHAR)
- description (TEXT)
- available (BOOLEAN)
- created_at (TIMESTAMP)
```

### Orders Table
```sql
- id (INT, Primary Key)
- user_id (INT, Foreign Key)
- start_date (DATE)
- end_date (DATE)
- total_price (DECIMAL)
- status (ENUM: 'pending', 'confirmed', 'rented', 'returned', 'cancelled')
- payment_method (VARCHAR)
- created_at (TIMESTAMP)
```

### Order Items Table
```sql
- id (INT, Primary Key)
- order_id (INT, Foreign Key)
- product_id (INT, Foreign Key)
- price (DECIMAL)
- start_date (DATE)
- end_date (DATE)
- days (INT)
```

## Usage Guide

### For Users

1. **Register/Login**
   - Create an account or login

2. **Browse Products**
   - View all products on homepage
   - Filter by category

3. **Rent a Product**
   - Click on a product
   - Select start and end dates
   - View calculated price
   - Add to cart

4. **Checkout**
   - Review cart items
   - Proceed to checkout
   - Select payment method
   - Place order

5. **Track Orders**
   - View order history
   - Check order status
   - View order details

### For Admins

1. **Login as Admin**
   - Use admin credentials

2. **Dashboard**
   - View statistics
   - Recent orders overview

3. **Manage Products**
   - Add new products
   - Edit existing products
   - Delete products
   - Toggle availability

4. **Manage Orders**
   - View all orders
   - Filter by status
   - Update order status
   - View customer details

## Security Features

- Password hashing using PHP `password_hash()`
- SQL injection prevention using `mysqli_real_escape_string()`
- Session-based authentication
- Role-based access control (User/Admin)
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
