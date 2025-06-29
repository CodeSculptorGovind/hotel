
-- Takeaway Menu Database Schema
-- Designed to work with invoice generation systems

-- Categories table for organizing menu items
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

-- Main takeaway menu items table
CREATE TABLE IF NOT EXISTS takeaway_menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_code VARCHAR(20) UNIQUE NOT NULL, -- For invoice systems
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    cost_price DECIMAL(10,2) DEFAULT 0.00, -- For profit tracking
    image_url VARCHAR(500),
    is_available TINYINT(1) DEFAULT 1,
    is_popular TINYINT(1) DEFAULT 0,
    display_order INT DEFAULT 0,
    allergens VARCHAR(255), -- Comma separated allergen codes
    nutritional_info JSON, -- Store calories, etc.
    preparation_time INT DEFAULT 15, -- Minutes
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES takeaway_categories(id) ON DELETE CASCADE,
    INDEX idx_category (category_id),
    INDEX idx_available (is_available),
    INDEX idx_popular (is_popular),
    INDEX idx_item_code (item_code)
);

-- Variations/modifiers for items (sizes, extras, etc.)
CREATE TABLE IF NOT EXISTS item_variations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    variation_type ENUM('size', 'extra', 'option') NOT NULL,
    name VARCHAR(100) NOT NULL,
    price_modifier DECIMAL(10,2) DEFAULT 0.00, -- Additional cost
    is_default TINYINT(1) DEFAULT 0,
    is_available TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES takeaway_menu_items(id) ON DELETE CASCADE,
    INDEX idx_item_type (item_id, variation_type)
);

-- Enhanced orders table for takeaway
CREATE TABLE IF NOT EXISTS takeaway_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(20) UNIQUE NOT NULL, -- Display order number
    invoice_id VARCHAR(50) UNIQUE, -- For invoice generation
    customer_name VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_email VARCHAR(255),
    delivery_address TEXT,
    postcode VARCHAR(10),
    order_type ENUM('collection', 'delivery') DEFAULT 'collection',
    special_instructions TEXT,
    subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    delivery_charge DECIMAL(10,2) DEFAULT 0.00,
    discount_amount DECIMAL(10,2) DEFAULT 0.00,
    tax_amount DECIMAL(10,2) DEFAULT 0.00,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'card', 'online') DEFAULT 'cash',
    payment_status ENUM('pending', 'paid', 'refunded') DEFAULT 'pending',
    order_status ENUM('received', 'confirmed', 'preparing', 'ready', 'out_for_delivery', 'delivered', 'cancelled') DEFAULT 'received',
    estimated_time DATETIME,
    actual_delivery_time DATETIME,
    notes TEXT, -- Internal notes
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_order_number (order_number),
    INDEX idx_phone (customer_phone),
    INDEX idx_status (order_status),
    INDEX idx_date (created_at),
    INDEX idx_type (order_type)
);

-- Order items with variations
CREATE TABLE IF NOT EXISTS takeaway_order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    item_id INT NOT NULL,
    item_code VARCHAR(20) NOT NULL, -- Duplicate for invoice systems
    item_name VARCHAR(255) NOT NULL, -- Snapshot at time of order
    base_price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    variations JSON, -- Store selected variations
    item_total DECIMAL(10,2) NOT NULL,
    special_requests TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES takeaway_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES takeaway_menu_items(id),
    INDEX idx_order (order_id),
    INDEX idx_item_code (item_code)
);

-- Insert sample categories
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

-- Insert sample menu items based on typical takeaway menu
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
