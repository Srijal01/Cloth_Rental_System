-- Cloth Rental System Database Setup
-- Run this script in phpMyAdmin or MySQL command line

-- Create Database
CREATE DATABASE IF NOT EXISTS cloth_rental;
USE cloth_rental;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  address TEXT,
  role ENUM('user','admin') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products (Clothes) Table
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  category VARCHAR(50) NOT NULL,
  size VARCHAR(20),
  price_per_day DECIMAL(10,2) NOT NULL,
  image VARCHAR(255),
  description TEXT,
  available BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  total_price DECIMAL(10,2) NOT NULL,
  status ENUM('pending','confirmed','rented','returned','cancelled') DEFAULT 'pending',
  payment_method VARCHAR(50) DEFAULT 'cash_on_delivery',
  payment_status ENUM('unpaid','paid','failed') DEFAULT 'unpaid',
  transaction_id VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  days INT NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insert default admin user (password: admin123)
INSERT INTO users (name, email, password, role) VALUES 
('Admin', 'admin@clothrental.com', '$2y$10$YourHashedPasswordHere', 'admin');

-- Sample Products
INSERT INTO products (name, category, size, price_per_day, image, description) VALUES
('Red Party Dress', 'women', 'M', 500.00, 'red-party-dress.svg', 'Elegant red party dress perfect for special occasions'),
('Black Tuxedo', 'men', 'L', 800.00, 'black-tuxedo.svg', 'Classic black tuxedo for formal events'),
('Blue Saree', 'ethnic', 'Free Size', 600.00, 'blue-saree.svg', 'Traditional blue silk saree'),
('White Shirt', 'men', 'L', 200.00, 'white-shirt.svg', 'Formal white shirt for business meetings'),
('Floral Lehenga', 'ethnic', 'M', 1200.00, 'floral-lehenga.svg', 'Beautiful floral lehenga for weddings');
