-- Update Orders Table for eSewa Integration
-- Run this in phpMyAdmin to add payment tracking fields

USE cloth_rental;

-- Add payment_status and transaction_id columns to existing orders table
ALTER TABLE orders 
ADD COLUMN IF NOT EXISTS payment_status ENUM('unpaid','paid','failed') DEFAULT 'unpaid' AFTER payment_method,
ADD COLUMN IF NOT EXISTS transaction_id VARCHAR(100) AFTER payment_status;

-- Update existing orders to have 'paid' status if they were cash on delivery
UPDATE orders SET payment_status = 'paid' WHERE payment_method = 'cash_on_delivery' AND status != 'cancelled';

-- Show updated table structure
DESCRIBE orders;

-- Success message
SELECT 'eSewa payment integration database update completed successfully!' AS Status;
