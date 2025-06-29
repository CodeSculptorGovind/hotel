
-- Complete Restaurant Database Schema
-- Includes all tables for table menu, takeaway menu, orders, reservations, and customers

-- Create database
CREATE DATABASE IF NOT EXISTS restaurant_db;
USE restaurant_db;

-- ===== CATEGORIES TABLES =====

-- Table menu categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    description TEXT,
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_active (is_active),
    INDEX idx_sort_order (sort_order)
);

-- Takeaway menu categories
CREATE TABLE IF NOT EXISTS takeaway_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_display_order (display_order),
    INDEX idx_active (is_active)
);

-- ===== MENU ITEMS TABLES =====

-- Table menu items
CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT NOT NULL,
    image_url VARCHAR(500),
    is_available TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_category (category_id),
    INDEX idx_available (is_available)
);

-- Takeaway menu items
CREATE TABLE IF NOT EXISTS takeaway_menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_code VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    cost_price DECIMAL(10,2) DEFAULT 0.00,
    image_url VARCHAR(500),
    is_available TINYINT(1) DEFAULT 1,
    is_popular TINYINT(1) DEFAULT 0,
    display_order INT DEFAULT 0,
    allergens VARCHAR(255),
    nutritional_info JSON,
    preparation_time INT DEFAULT 15,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES takeaway_categories(id) ON DELETE CASCADE,
    INDEX idx_category (category_id),
    INDEX idx_available (is_available),
    INDEX idx_popular (is_popular),
    INDEX idx_item_code (item_code)
);

-- Item variations/modifiers for takeaway items
CREATE TABLE IF NOT EXISTS item_variations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    variation_type ENUM('size', 'extra', 'option') NOT NULL,
    name VARCHAR(100) NOT NULL,
    price_modifier DECIMAL(10,2) DEFAULT 0.00,
    is_default TINYINT(1) DEFAULT 0,
    is_available TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES takeaway_menu_items(id) ON DELETE CASCADE,
    INDEX idx_item_type (item_id, variation_type)
);

-- ===== ORDERS TABLES =====

-- Main orders table (unified for all order types)
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(50) UNIQUE NOT NULL,
    tracking_token VARCHAR(100) UNIQUE NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_address TEXT,
    postcode VARCHAR(10),
    special_instructions TEXT,
    subtotal DECIMAL(10,2) NOT NULL,
    delivery_fee DECIMAL(10,2) DEFAULT 0.00,
    discount_amount DECIMAL(10,2) DEFAULT 0.00,
    tax DECIMAL(10,2) NOT NULL DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    order_type ENUM('takeaway', 'delivery', 'dine_in') DEFAULT 'takeaway',
    payment_method ENUM('cash', 'card', 'online') DEFAULT 'cash',
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    status ENUM('pending', 'confirmed', 'preparing', 'ready', 'out_for_delivery', 'delivered', 'completed', 'cancelled') DEFAULT 'pending',
    estimated_pickup DATETIME,
    actual_delivery_time DATETIME,
    expires_at DATETIME,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_order_id (order_id),
    INDEX idx_tracking_token (tracking_token),
    INDEX idx_customer_phone (customer_phone),
    INDEX idx_status (status),
    INDEX idx_order_type (order_type),
    INDEX idx_created_at (created_at)
);

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    item_id INT,
    item_code VARCHAR(20),
    item_name VARCHAR(255) NOT NULL,
    item_description TEXT,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    variations JSON,
    subtotal DECIMAL(10,2) NOT NULL,
    special_requests TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    INDEX idx_order_id (order_id),
    INDEX idx_item_code (item_code)
);

-- ===== RESERVATIONS TABLE =====

CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    party_size INT NOT NULL,
    special_requests TEXT,
    table_preference VARCHAR(100),
    occasion VARCHAR(100),
    status ENUM('pending', 'confirmed', 'seated', 'completed', 'cancelled', 'no_show') DEFAULT 'pending',
    confirmation_sent TINYINT(1) DEFAULT 0,
    reminder_sent TINYINT(1) DEFAULT 0,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_reservation_id (reservation_id),
    INDEX idx_date (date),
    INDEX idx_phone (phone),
    INDEX idx_status (status),
    INDEX idx_date_time (date, time)
);

-- ===== CUSTOMERS TABLE =====

CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(255),
    default_address TEXT,
    postcode VARCHAR(10),
    total_orders INT DEFAULT 0,
    total_spent DECIMAL(10,2) DEFAULT 0.00,
    last_order_date DATETIME,
    preferences JSON,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_phone (phone),
    INDEX idx_email (email),
    INDEX idx_active (is_active)
);

-- ===== ADMIN USERS TABLE =====

CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    full_name VARCHAR(255),
    role ENUM('admin', 'manager', 'staff') DEFAULT 'staff',
    is_active TINYINT(1) DEFAULT 1,
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_active (is_active)
);

-- ===== SETTINGS TABLE =====

CREATE TABLE IF NOT EXISTS restaurant_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_setting_key (setting_key)
);

-- ===== INSERT SAMPLE DATA =====

-- Insert default categories for table menu
INSERT IGNORE INTO categories (id, name, display_name, sort_order) VALUES
(1, 'breakfast', 'Breakfast', 1),
(2, 'lunch', 'Lunch', 2),
(3, 'dinner', 'Dinner', 3),
(4, 'desserts', 'Desserts', 4),
(5, 'wine', 'Wine & Beverages', 5),
(6, 'drinks', 'Drinks', 6);

-- Insert default categories for takeaway menu
INSERT IGNORE INTO takeaway_categories (id, name, display_order) VALUES
(1, 'Starters & Appetizers', 1),
(2, 'Burgers', 2),
(3, 'Main Courses', 3),
(4, 'Curry Dishes', 4),
(5, 'Rice & Biryani', 5),
(6, 'Naan & Breads', 6),
(7, 'Sides', 7),
(8, 'Desserts', 8),
(9, 'Beverages', 9);

-- Insert sample table menu items
INSERT IGNORE INTO menu_items (id, name, description, price, category_id, image_url, is_available) VALUES
(1, 'Classic Pancakes', 'Fluffy pancakes with maple syrup and butter', 12.99, 1, 'images/breakfast-1.jpg', 1),
(2, 'English Breakfast', 'Eggs, bacon, sausages, beans, and toast', 15.99, 1, 'images/breakfast-2.jpg', 1),
(3, 'Avocado Toast', 'Sourdough toast with avocado and cherry tomatoes', 11.99, 1, 'images/breakfast-3.jpg', 1),
(4, 'Grilled Chicken', 'Perfectly grilled chicken breast with herbs', 18.99, 2, 'images/lunch-1.jpg', 1),
(5, 'Caesar Salad', 'Fresh romaine lettuce with caesar dressing', 12.99, 2, 'images/lunch-2.jpg', 1),
(6, 'Ribeye Steak', 'Premium ribeye steak grilled to perfection', 32.99, 3, 'images/dinner-1.jpg', 1),
(7, 'Grilled Salmon', 'Atlantic salmon with seasonal vegetables', 26.99, 3, 'images/dinner-2.jpg', 1),
(8, 'Chocolate Cake', 'Rich chocolate cake with vanilla ice cream', 8.99, 4, 'images/dessert-1.jpg', 1),
(9, 'Tiramisu', 'Classic Italian coffee-flavored dessert', 9.99, 4, 'images/dessert-2.jpg', 1);

-- Insert sample takeaway menu items
INSERT IGNORE INTO takeaway_menu_items (item_code, name, description, category_id, price, display_order) VALUES
-- Starters
('ST001', 'Chicken Wings', '6 pieces of marinated chicken wings', 1, 8.99, 1),
('ST002', 'Onion Rings', 'Crispy beer battered onion rings', 1, 5.99, 2),
('ST003', 'Garlic Bread', 'Fresh baked garlic bread with herbs', 1, 4.99, 3),
-- Burgers
('BG001', 'Classic Beef Burger', 'Quarter pound beef patty with lettuce, tomato, onion', 2, 12.99, 1),
('BG002', 'Chicken Burger', 'Grilled chicken breast with mayo and salad', 2, 11.99, 2),
('BG003', 'Cheese Burger', 'Beef patty with melted cheese', 2, 13.99, 3),
-- Main Courses
('MC001', 'Fish & Chips', 'Fresh cod in beer batter with chunky chips', 3, 14.99, 1),
('MC002', 'Grilled Chicken', 'Half roasted chicken with herbs', 3, 16.99, 2),
('MC003', 'Mixed Grill', 'Chicken, lamb chops, and seekh kebab', 3, 19.99, 3),
-- Beverages
('BV001', 'Coca Cola', '330ml can', 9, 1.99, 1),
('BV002', 'Fresh Orange Juice', 'Freshly squeezed orange juice', 9, 3.99, 2),
('BV003', 'Lassi', 'Traditional yogurt drink - sweet or salty', 9, 2.99, 3);

-- Insert default admin user (password: admin123 - remember to change this!)
INSERT IGNORE INTO admin_users (id, username, password, email, full_name, role) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@mallroadhouse.com', 'Admin User', 'admin');

-- Insert default restaurant settings
INSERT IGNORE INTO restaurant_settings (setting_key, setting_value, setting_type, description) VALUES
('restaurant_name', 'Mallroad House', 'string', 'Restaurant name'),
('delivery_fee', '40.00', 'number', 'Standard delivery fee'),
('min_order_amount', '20.00', 'number', 'Minimum order amount for delivery'),
('tax_rate', '0.00', 'number', 'Tax rate percentage'),
('preparation_time', '30', 'number', 'Average preparation time in minutes'),
('max_party_size', '12', 'number', 'Maximum party size for reservations'),
('booking_advance_days', '30', 'number', 'How many days in advance can customers book'),
('opening_hours', '{"mon":{"open":"11:00","close":"23:00"},"tue":{"open":"11:00","close":"23:00"},"wed":{"open":"11:00","close":"23:00"},"thu":{"open":"11:00","close":"23:00"},"fri":{"open":"11:00","close":"23:00"},"sat":{"open":"11:00","close":"23:00"},"sun":{"open":"11:00","close":"23:00"}}', 'json', 'Restaurant opening hours');
