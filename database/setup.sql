-- Create database
CREATE DATABASE IF NOT EXISTS mallroadhouse;
```

```sql
USE mallroadhouse;
```

```sql
-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

```sql
-- Create menu_items table
CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    image_url VARCHAR(500),
    is_available TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);
```

```sql
-- Create combo_items table
CREATE TABLE IF NOT EXISTS combo_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    image_url VARCHAR(500),
    is_available TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);
```

```sql
-- Create combo_item_relations table
CREATE TABLE IF NOT EXISTS combo_item_relations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    combo_id INT,
    menu_item_id INT,
    quantity INT DEFAULT 1,
    is_optional TINYINT(1) DEFAULT 0,
    FOREIGN KEY (combo_id) REFERENCES combo_items(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE
);
```

```sql
-- Insert categories
INSERT IGNORE INTO categories (id, name, display_name, sort_order) VALUES
(1, 'breakfast', 'Breakfast', 1),
(2, 'lunch', 'Lunch', 2),
(3, 'dinner', 'Dinner', 3),
(4, 'desserts', 'Desserts', 4),
(5, 'drinks', 'Drinks', 5),
(6, 'wine', 'Wine & Liquor', 6);
```

```sql
-- Insert sample menu items
INSERT IGNORE INTO menu_items (name, description, price, category_id, image_url) VALUES
-- Breakfast
('Classic Pancakes', 'Fluffy pancakes with maple syrup and butter', 299.00, 1, 'images/breakfast-1.jpg'),
('Avocado Toast', 'Sourdough toast with avocado and cherry tomatoes', 349.00, 1, 'images/breakfast-2.jpg'),
('English Breakfast', 'Eggs, bacon, sausages, beans, and toast', 499.00, 1, 'images/breakfast-3.jpg'),

-- Lunch
('Chicken Biryani', 'Aromatic basmati rice with tender chicken', 449.00, 2, 'images/lunch-1.jpg'),
('Paneer Butter Masala', 'Creamy paneer curry with butter naan', 399.00, 2, 'images/lunch-2.jpg'),
('Fish Curry', 'Traditional fish curry with rice', 499.00, 2, 'images/lunch-3.jpg'),

-- Dinner
('Grilled Salmon', 'Atlantic salmon with seasonal vegetables', 799.00, 3, 'images/dinner-1.jpg'),
('Mutton Rogan Josh', 'Tender mutton in rich curry sauce', 699.00, 3, 'images/dinner-2.jpg'),
('Dal Makhani', 'Creamy black lentils with butter naan', 449.00, 3, 'images/dinner-3.jpg'),

-- Desserts
('Chocolate Cake', 'Rich chocolate layer cake with berries', 299.00, 4, 'images/dessert-1.jpg'),
('Gulab Jamun', 'Traditional Indian sweet in syrup', 199.00, 4, 'images/dessert-2.jpg'),
('Ice Cream Sundae', 'Vanilla ice cream with chocolate sauce', 249.00, 4, 'images/dessert-3.jpg'),

-- Drinks
('Fresh Lime Soda', 'Refreshing lime drink', 149.00, 5, 'images/drink-1.jpg'),
('Mango Lassi', 'Traditional yogurt drink with mango', 179.00, 5, 'images/drink-2.jpg'),
('Masala Chai', 'Spiced Indian tea', 99.00, 5, 'images/drink-3.jpg'),

-- Wine
('Red Wine', 'House red wine bottle', 1299.00, 6, 'images/wine-1.jpg'),
('White Wine', 'Chardonnay wine bottle', 1199.00, 6, 'images/wine-2.jpg'),
('Beer', 'Chilled beer bottle', 199.00, 6, 'images/wine-3.jpg');
```

```sql
-- Table for reservations
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    guests INT NOT NULL,
    status ENUM('pending', 'approved', 'declined', 'rescheduled') DEFAULT 'pending',
    request_type ENUM('dine_in', 'takeaway') DEFAULT 'dine_in',
    special_requests TEXT,
    encoded_id VARCHAR(50) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

```sql
-- Table for admin users
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

```sql
-- Insert default admin user (password: admin123)
INSERT IGNORE INTO admin_users (username, password, email) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@mallroadhouse.com');
```

```sql
-- Table for takeaway orders
CREATE TABLE IF NOT EXISTS takeaway_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT,
    menu_items JSON,
    total_amount DECIMAL(10,2),
    pickup_time TIME,
    status ENUM('pending', 'preparing', 'ready', 'completed') DEFAULT 'pending',
    FOREIGN KEY (reservation_id) REFERENCES reservations(id)
);
```

```sql
-- Insert sample combo items
INSERT INTO combo_items (name, description, price, category_id, image_url, is_available) VALUES
('Family Feast', 'Perfect meal for 4 people including appetizers, mains and desserts', 89.99, 7, 'images/combo-1.jpg', 1),
('Couple\'s Special', 'Romantic dinner for 2 with wine pairing', 59.99, 7, 'images/combo-2.jpg', 1);
```

```sql
-- Insert combo item relations
INSERT INTO combo_item_relations (combo_id, menu_item_id, quantity, is_optional) VALUES
(1, 1, 2, 0), -- 2 Grilled Salmon
(1, 5, 1, 0), -- 1 Caesar Salad
(1, 9, 2, 0), -- 2 Chocolate Cake
(2, 2, 2, 0), -- 2 Ribeye Steak
(2, 10, 1, 1), -- 1 Red Wine (optional)
(2, 8, 2, 0); -- 2 Tiramisu
```

```sql
-- Create orders table for managing takeaway orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(20) UNIQUE NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(15) NOT NULL,
    customer_email VARCHAR(100),
    customer_address TEXT NOT NULL,
    special_instructions TEXT,
    order_items JSON NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    delivery_fee DECIMAL(10,2) NOT NULL DEFAULT 40.00,
    total DECIMAL(10,2) NOT NULL,
    order_type ENUM('takeaway', 'dine_in') DEFAULT 'takeaway',
    order_status ENUM('pending', 'preparing', 'ready', 'out_for_delivery', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_order_id (order_id),
    INDEX idx_customer_phone (customer_phone),
    INDEX idx_order_status (order_status),
    INDEX idx_created_at (created_at)
);
```

```sql
-- Create customers table for optional customer profiles
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) UNIQUE NOT NULL,
    email VARCHAR(100),
    default_address TEXT,
    total_orders INT DEFAULT 0,
    total_spent DECIMAL(10,2) DEFAULT 0.00,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_phone (phone),
    INDEX idx_email (email)
);
```

```sql
-- Insert default categories
INSERT IGNORE INTO categories (name, display_name, description, sort_order) VALUES 
('breakfast', 'Breakfast', 'Morning meals and beverages', 1),
('lunch', 'Lunch', 'Midday meals and light dishes', 2),
('dinner', 'Dinner', 'Evening meals and hearty dishes', 3),
('desserts', 'Desserts', 'Sweet treats and desserts', 4),
('wine', 'Wine', 'Wine selection and bottles', 5),
('drinks', 'Drinks', 'Beverages and soft drinks', 6);
```

```sql
-- Insert sample menu items (using category_id)
INSERT IGNORE INTO menu_items (name, description, price, category_id, image_url, is_available) VALUES 
-- Breakfast Items (category_id = 1)
('Classic Pancakes', 'Fluffy pancakes with maple syrup and butter', 12.99, 1, 'images/breakfast-1.jpg', 1),
('English Breakfast', 'Eggs, bacon, sausages, beans, and toast', 15.99, 1, 'images/breakfast-2.jpg', 1),
('Avocado Toast', 'Sourdough toast with avocado and cherry tomatoes', 11.99, 1, 'images/breakfast-3.jpg', 1),
('Breakfast Burrito', 'Scrambled eggs, cheese, peppers in tortilla', 13.99, 1, 'images/breakfast-4.jpg', 1),

-- Lunch Items (category_id = 2)
('Grilled Chicken', 'Perfectly grilled chicken breast with herbs', 15.99, 2, 'images/lunch-1.jpg', 1),
('Caesar Salad', 'Fresh romaine lettuce with caesar dressing', 8.99, 2, 'images/lunch-2.jpg', 1),
('French Fries', 'Golden crispy french fries', 4.99, 2, 'images/lunch-3.jpg', 1),
('Garlic Bread', 'Homemade garlic bread', 3.99, 2, 'images/lunch-4.jpg', 1),
('Beef Burger', 'Juicy beef burger with lettuce and tomato', 12.99, 2, 'images/lunch-5.jpg', 1),
('Fish & Chips', 'Beer battered cod with chunky chips', 19.99, 2, 'images/lunch-6.jpg', 1),

-- Dinner Items (category_id = 3)
('Pasta Carbonara', 'Creamy pasta with bacon and eggs', 14.99, 3, 'images/dinner-1.jpg', 1),
('Grilled Salmon', 'Atlantic salmon with seasonal vegetables', 26.99, 3, 'images/dinner-2.jpg', 1),
('Ribeye Steak', 'Prime ribeye with mashed potatoes', 32.99, 3, 'images/dinner-3.jpg', 1),
('Lobster Thermidor', 'Fresh lobster with creamy sauce', 39.99, 3, 'images/dinner-4.jpg', 1),

-- Desserts (category_id = 4)
('Chocolate Cake', 'Rich chocolate cake slice', 6.99, 4, 'images/dessert-1.jpg', 1),
('Tiramisu', 'Classic Italian dessert', 7.99, 4, 'images/dessert-2.jpg', 1),
('Cheesecake', 'New York style cheesecake', 6.99, 4, 'images/dessert-3.jpg', 1),
('Ice Cream Sundae', 'Vanilla ice cream with toppings', 5.99, 4, 'images/dessert-4.jpg', 1),

-- Wine (category_id = 5)
('Red Wine Glass', 'House red wine', 8.99, 5, 'images/wine-1.jpg', 1),
('White Wine Glass', 'House white wine', 8.99, 5, 'images/wine-2.jpg', 1),
('Champagne', 'Premium champagne bottle', 89.99, 5, 'images/wine-3.jpg', 1),
('Wine Bottle - Cabernet', 'Full bottle of Cabernet Sauvignon', 45.99, 5, 'images/wine-4.jpg', 1),

-- Drinks (category_id = 6)
('Coffee', 'Fresh brewed coffee', 2.99, 6, 'images/drink-1.jpg', 1),
('Fresh Orange Juice', 'Freshly squeezed orange juice', 4.99, 6, 'images/drink-2.jpg', 1),
('Iced Tea', 'House iced tea', 3.99, 6, 'images/drink-3.jpg', 1),
('Soda', 'Assorted soft drinks', 2.99, 6, 'images/drink-4.jpg', 1);
```

```sql
-- Insert sample combo items (using category_id)
INSERT IGNORE INTO combo_items (name, description, price, category_id) VALUES 
('Lunch Special', 'Grilled chicken with salad and fries', 19.99, 2),
('Dinner Combo', 'Pasta with garlic bread and dessert', 21.99, 3),
('Burger Meal', 'Beef burger with fries and drink', 16.99, 2);
```

```sql
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(20) UNIQUE NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(15) NOT NULL,
    customer_email VARCHAR(100),
    customer_address TEXT NOT NULL,
    special_instructions TEXT,
    order_items JSON NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    delivery_fee DECIMAL(10,2) NOT NULL DEFAULT 40.00,
    total DECIMAL(10,2) NOT NULL,
    order_type ENUM('takeaway', 'dine_in') DEFAULT 'takeaway',
    order_status ENUM('pending', 'preparing', 'ready', 'out_for_delivery', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_order_id (order_id),
    INDEX idx_customer_phone (customer_phone),
    INDEX idx_order_status (order_status),
    INDEX idx_created_at (created_at)
);
```

```sql
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) UNIQUE NOT NULL,
    email VARCHAR(100),
    default_address TEXT,
    total_orders INT DEFAULT 0,
    total_spent DECIMAL(10,2) DEFAULT 0.00,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_phone (phone),
    INDEX idx_email (email)
);
```

```sql
CREATE TABLE IF NOT EXISTS takeaway_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT,
    menu_items JSON,
    total_amount DECIMAL(10,2),
    pickup_time TIME,
    status ENUM('pending', 'preparing', 'ready', 'completed') DEFAULT 'pending',
    FOREIGN KEY (reservation_id) REFERENCES reservations(id)
);
```

```sql
CREATE TABLE IF NOT EXISTS combo_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    image_url VARCHAR(500),
    is_available TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);
```

```sql
CREATE TABLE IF NOT EXISTS combo_item_relations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    combo_id INT,
    menu_item_id INT,
    quantity INT DEFAULT 1,
    is_optional TINYINT(1) DEFAULT 0,
    FOREIGN KEY (combo_id) REFERENCES combo_items(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE
);
```

```sql
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

```sql
CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    image_url VARCHAR(500),
    is_available TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);
```

```sql
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

```sql
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    guests INT NOT NULL,
    status ENUM('pending', 'approved', 'declined', 'rescheduled') DEFAULT 'pending',
    request_type ENUM('dine_in', 'takeaway') DEFAULT 'dine_in',
    special_requests TEXT,
    encoded_id VARCHAR(50) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

```sql
INSERT IGNORE INTO categories (id, name, display_name, sort_order) VALUES
(1, 'breakfast', 'Breakfast', 1),
(2, 'lunch', 'Lunch', 2),
(3, 'dinner', 'Dinner', 3),
(4, 'desserts', 'Desserts', 4),
(5, 'drinks', 'Drinks', 5),
(6, 'wine', 'Wine & Liquor', 6);
```

```sql
INSERT IGNORE INTO menu_items (name, description, price, category_id, image_url) VALUES
-- Breakfast
('Classic Pancakes', 'Fluffy pancakes with maple syrup and butter', 299.00, 1, 'images/breakfast-1.jpg'),
('Avocado Toast', 'Sourdough toast with avocado and cherry tomatoes', 349.00, 1, 'images/breakfast-2.jpg'),
('English Breakfast', 'Eggs, bacon, sausages, beans, and toast', 499.00, 1, 'images/breakfast-3.jpg'),

-- Lunch
('Chicken Biryani', 'Aromatic basmati rice with tender chicken', 449.00, 2, 'images/lunch-1.jpg'),
('Paneer Butter Masala', 'Creamy paneer curry with butter naan', 399.00, 2, 'images/lunch-2.jpg'),
('Fish Curry', 'Traditional fish curry with rice', 499.00, 2, 'images/lunch-3.jpg'),

-- Dinner
('Grilled Salmon', 'Atlantic salmon with seasonal vegetables', 799.00, 3, 'images/dinner-1.jpg'),
('Mutton Rogan Josh', 'Tender mutton in rich curry sauce', 699.00, 3, 'images/dinner-2.jpg'),
('Dal Makhani', 'Creamy black lentils with butter naan', 449.00, 3, 'images/dinner-3.jpg'),

-- Desserts
('Chocolate Cake', 'Rich chocolate layer cake with berries', 299.00, 4, 'images/dessert-1.jpg'),
('Gulab Jamun', 'Traditional Indian sweet in syrup', 199.00, 4, 'images/dessert-2.jpg'),
('Ice Cream Sundae', 'Vanilla ice cream with chocolate sauce', 249.00, 4, 'images/dessert-3.jpg'),

-- Drinks
('Fresh Lime Soda', 'Refreshing lime drink', 149.00, 5, 'images/drink-1.jpg'),
('Mango Lassi', 'Traditional yogurt drink with mango', 179.00, 5, 'images/drink-2.jpg'),
('Masala Chai', 'Spiced Indian tea', 99.00, 5, 'images/drink-3.jpg'),

-- Wine
('Red Wine', 'House red wine bottle', 1299.00, 6, 'images/wine-1.jpg'),
('White Wine', 'Chardonnay wine bottle', 1199.00, 6, 'images/wine-2.jpg'),
('Beer', 'Chilled beer bottle', 199.00, 6, 'images/wine-3.jpg');
```

```sql
INSERT IGNORE INTO admin_users (username, password, email) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@mallroadhouse.com');
```

```sql
INSERT INTO combo_item_relations (combo_id, menu_item_id, quantity, is_optional) VALUES
(1, 1, 2, 0), -- 2 Grilled Salmon
(1, 5, 1, 0), -- 1 Caesar Salad
(1, 9, 2, 0), -- 2 Chocolate Cake
(2, 2, 2, 0), -- 2 Ribeye Steak
(2, 10, 1, 1), -- 1 Red Wine (optional)
(2, 8, 2, 0); -- 2 Tiramisu
```

```sql
INSERT INTO combo_items (name, description, price, category_id, image_url, is_available) VALUES
('Family Feast', 'Perfect meal for 4 people including appetizers, mains and desserts', 89.99, 7, 'images/combo-1.jpg', 1),
('Couple\'s Special', 'Romantic dinner for 2 with wine pairing', 59.99, 7, 'images/combo-2.jpg', 1);
```

```sql
-- Insert default admin user (password: admin123)
INSERT INTO admin_users (username, password, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@mallroadhouse.com');
```

```sql
-- Table for combo items
CREATE TABLE IF NOT EXISTS combo_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT NOT NULL,
    image_url VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    is_combo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
);
```

```sql
-- Table for combo item relationships
CREATE TABLE IF NOT EXISTS combo_item_relations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    combo_id INT NOT NULL,
    menu_item_id INT NOT NULL,
    quantity INT DEFAULT 1,
    is_optional BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (combo_id) REFERENCES combo_items(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE
);
```

```sql
-- Create orders table for managing takeaway orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(20) UNIQUE NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(15) NOT NULL,
    customer_email VARCHAR(100),
    customer_address TEXT NOT NULL,
    special_instructions TEXT,
    order_items JSON NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    delivery_fee DECIMAL(10,2) NOT NULL DEFAULT 40.00,
    total DECIMAL(10,2) NOT NULL,
    order_type ENUM('takeaway', 'dine_in') DEFAULT 'takeaway',
    order_status ENUM('pending', 'preparing', 'ready', 'out_for_delivery', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_order_id (order_id),
    INDEX idx_customer_phone (customer_phone),
    INDEX idx_order_status (order_status),
    INDEX idx_created_at (created_at)
);
```

```sql
-- Create customers table for optional customer profiles
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) UNIQUE NOT NULL,
    email VARCHAR(100),
    default_address TEXT,
    total_orders INT DEFAULT 0,
    total_spent DECIMAL(10,2) DEFAULT 0.00,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_phone (phone),
    INDEX idx_email (email)
);
```

```sql
-- Insert sample combo items (using category_id)
INSERT IGNORE INTO combo_items (name, description, price, category_id) VALUES 
('Lunch Special', 'Grilled chicken with salad and fries', 19.99, 2),
('Dinner Combo', 'Pasta with garlic bread and dessert', 21.99, 3),
('Burger Meal', 'Beef burger with fries and drink', 16.99, 2);
```

```sql
-- Insert sample menu items (using category_id)
INSERT IGNORE INTO menu_items (name, description, price, category_id, image_url, is_available) VALUES 
-- Breakfast Items (category_id = 1)
('Classic Pancakes', 'Fluffy pancakes with maple syrup and butter', 12.99, 1, 'images/breakfast-1.jpg', 1),
('English Breakfast', 'Eggs, bacon, sausages, beans, and toast', 15.99, 1, 'images/breakfast-2.jpg', 1),
('Avocado Toast', 'Sourdough toast with avocado and cherry tomatoes', 11.99, 1, 'images/breakfast-3.jpg', 1),
('Breakfast Burrito', 'Scrambled eggs, cheese, peppers in tortilla', 13.99, 1, 'images/breakfast-4.jpg', 1),

-- Lunch Items (category_id = 2)
('Grilled Chicken', 'Perfectly grilled chicken breast with herbs', 15.99, 2, 'images/lunch-1.jpg', 1),
('Caesar Salad', 'Fresh romaine lettuce with caesar dressing', 8.99, 2, 'images/lunch-2.jpg', 1),
('French Fries', 'Golden crispy french fries', 4.99, 2, 'images/lunch-3.jpg', 1),
('Garlic Bread', 'Homemade garlic bread', 3.99, 2, 'images/lunch-4.jpg', 1),
('Beef Burger', 'Juicy beef burger with lettuce and tomato', 12.99, 2, 'images/lunch-5.jpg', 1),
('Fish & Chips', 'Beer battered cod with chunky chips', 19.99, 2, 'images/lunch-6.jpg', 1),

-- Dinner Items (category_id = 3)
('Pasta Carbonara', 'Creamy pasta with bacon and eggs', 14.99, 3, 'images/dinner-1.jpg', 1),
('Grilled Salmon', 'Atlantic salmon with seasonal vegetables', 26.99, 3, 'images/dinner-2.jpg', 1),
('Ribeye Steak', 'Prime ribeye with mashed potatoes', 32.99, 3, 'images/dinner-3.jpg', 1),
('Lobster Thermidor', 'Fresh lobster with creamy sauce', 39.99, 3, 'images/dinner-4.jpg', 1),

-- Desserts (category_id = 4)
('Chocolate Cake', 'Rich chocolate cake slice', 6.99, 4, 'images/dessert-1.jpg', 1),
('Tiramisu', 'Classic Italian dessert', 7.99, 4, 'images/dessert-2.jpg', 1),
('Cheesecake', 'New York style cheesecake', 6.99, 4, 'images/dessert-3.jpg', 1),
('Ice Cream Sundae', 'Vanilla ice cream with toppings', 5.99, 4, 'images/dessert-4.jpg', 1),

-- Wine (category_id = 5)
('Red Wine Glass', 'House red wine', 8.99, 5, 'images/wine-1.jpg', 1),
('White Wine Glass', 'House white wine', 8.99, 5, 'images/wine-2.jpg', 1),
('Champagne', 'Premium champagne bottle', 89.99, 5, 'images/wine-3.jpg', 1),
('Wine Bottle - Cabernet', 'Full bottle of Cabernet Sauvignon', 45.99, 5, 'images/wine-4.jpg', 1),

-- Drinks (category_id = 6)
('Coffee', 'Fresh brewed coffee', 2.99, 6, 'images/drink-1.jpg', 1),
('Fresh Orange Juice', 'Freshly squeezed orange juice', 4.99, 6, 'images/drink-2.jpg', 1),
('Iced Tea', 'House iced tea', 3.99, 6, 'images/drink-3.jpg', 1),
('Soda', 'Assorted soft drinks', 2.99, 6, 'images/drink-4.jpg', 1);
```

```sql
INSERT IGNORE INTO admin_users (username, password, email) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@mallroadhouse.com');
```

```sql
-- Insert default admin user (password: admin123)
INSERT INTO admin_users (username, password, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG```sql
-- Table for takeaway orders
CREATE TABLE IF NOT EXISTS takeaway_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT,
    menu_items JSON,
    total_amount DECIMAL(10,2),
    pickup_time TIME,
    status ENUM('pending', 'preparing', 'ready', 'completed') DEFAULT 'pending',
    FOREIGN KEY (reservation_id) REFERENCES reservations(id)
);
```

```sql
-- Table for combo items
CREATE TABLE IF NOT EXISTS combo_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT NOT NULL,
    image_url VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    is_combo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
);
```

```sql
-- Table for combo item relationships
CREATE TABLE IF NOT EXISTS combo_item_relations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    combo_id INT NOT NULL,
    menu_item_id INT NOT NULL,
    quantity INT DEFAULT 1,
    is_optional BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (combo_id) REFERENCES combo_items(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE
);
```

```sql
-- Insert some sample menu items (using category_id)
INSERT IGNORE INTO menu_items (name, description, price, category_id, image_url, is_available) VALUES 
-- Breakfast Items (category_id = 1)
('Classic Pancakes', 'Fluffy pancakes with maple syrup and butter', 12.99, 1, 'images/breakfast-1.jpg', 1),
('English Breakfast', 'Eggs, bacon, sausages, beans, and toast', 15.99, 1, 'images/breakfast-2.jpg', 1),
('Avocado Toast', 'Sourdough toast with avocado and cherry tomatoes', 11.99, 1, 'images/breakfast-3.jpg', 1),
('Breakfast Burrito', 'Scrambled eggs, cheese, peppers in tortilla', 13.99, 1, 'images/breakfast-4.jpg', 1),

-- Lunch Items (category_id = 2)
('Grilled Chicken', 'Perfectly grilled chicken breast with herbs', 15.99, 2, 'images/lunch-1.jpg', 1),
('Caesar Salad', 'Fresh romaine lettuce with caesar dressing', 8.99, 2, 'images/lunch-2.jpg', 1),
('French Fries', 'Golden crispy french fries', 4.99, 2, 'images/lunch-3.jpg', 1),
('Garlic Bread', 'Homemade garlic bread', 3.99, 2, 'images/lunch-4.jpg', 1),
('Beef Burger', 'Juicy beef burger with lettuce and tomato', 12.99, 2, 'images/lunch-5.jpg', 1),
('Fish & Chips', 'Beer battered cod with chunky chips', 19.99, 2, 'images/lunch-6.jpg', 1),

-- Dinner Items (category_id = 3)
('Pasta Carbonara', 'Creamy pasta with bacon and eggs', 14.99, 3, 'images/dinner-1.jpg', 1),
('Grilled Salmon', 'Atlantic salmon with seasonal vegetables', 26.99, 3, 'images/dinner-2.jpg', 1),
('Ribeye Steak', 'Prime ribeye with mashed potatoes', 32.99, 3, 'images/dinner-3.jpg', 1),
('Lobster Thermidor', 'Fresh lobster with creamy sauce', 39.99, 3, 'images/dinner-4.jpg', 1),

-- Desserts (category_id = 4)
('Chocolate Cake', 'Rich chocolate cake slice', 6.99, 4, 'images/dessert-1.jpg', 1),
('Tiramisu', 'Classic Italian dessert', 7.99, 4, 'images/dessert-2.jpg', 1),
('Cheesecake', 'New York style cheesecake', 6.99, 4, 'images/dessert-3.jpg', 1),
('Ice Cream Sundae', 'Vanilla ice cream with toppings', 5.99, 4, 'images/dessert-4.jpg', 1),

-- Wine (category_id = 5)
('Red Wine Glass', 'House red wine', 8.99, 5, 'images/wine-1.jpg', 1),
('White Wine Glass', 'House white wine', 8.99, 5, 'images/wine-2.jpg', 1),
('Champagne', 'Premium champagne bottle', 89.99, 5, 'images/wine-3.jpg', 1),
('Wine Bottle - Cabernet', 'Full bottle of Cabernet Sauvignon', 45.99, 5, 'images/wine-4.jpg', 1),

-- Drinks (category_id = 6)
('Coffee', 'Fresh brewed coffee', 2.99, 6, 'images/drink-1.jpg', 1),
('Fresh Orange Juice', 'Freshly squeezed orange juice', 4.99, 6, 'images/drink-2.jpg', 1),
('Iced Tea', 'House iced tea', 3.99, 6, 'images/drink-3.jpg', 1),
('Soda', 'Assorted soft drinks', 2.99, 6, 'images/drink-4.jpg', 1);
```

```sql
-- Insert sample combo items (using category_id)
INSERT IGNORE INTO combo_items (name, description, price, category_id) VALUES 
('Lunch Special', 'Grilled chicken with salad and fries', 19.99, 2),
('Dinner Combo', 'Pasta with garlic bread and dessert', 21.99, 3),
('Burger Meal', 'Beef burger with fries and drink', 16.99, 2);
```

```sql
-- Insert sample combo items
INSERT INTO combo_items (name, description, price, category_id, image_url, is_available) VALUES
('Family Feast', 'Perfect meal for 4 people including appetizers, mains and desserts', 89.99, 7, 'images/combo-1.jpg', 1),
('Couple\'s Special', 'Romantic dinner for 2 with wine pairing', 59.99, 7, 'images/combo-2.jpg', 1);
```

```sql
-- Insert combo item relations
INSERT INTO combo_item_relations (combo_id, menu_item_id, quantity, is_optional) VALUES
(1, 1, 2, 0), -- 2 Grilled Salmon
(1, 5, 1, 0), -- 1 Caesar Salad
(1, 9, 2, 0), -- 2 Chocolate Cake
(2, 2, 2, 0), -- 2 Ribeye Steak
(2, 10, 1, 1), -- 1 Red Wine (optional)
(2, 8, 2, 0); -- 2 Tiramisu
```

```sql
-- Create orders table for managing takeaway orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(20) UNIQUE NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(15) NOT NULL,
    customer_email VARCHAR(100),
    customer_address TEXT NOT NULL,
    special_instructions TEXT,
    order_items JSON NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    delivery_fee DECIMAL(10,2) NOT NULL DEFAULT 40.00,
    total DECIMAL(10,2) NOT NULL,
    order_type ENUM('takeaway', 'dine_in') DEFAULT 'takeaway',
    order_status ENUM('pending', 'preparing', 'ready', 'out_for_delivery', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_order_id (order_id),
    INDEX idx_customer_phone (customer_phone),
    INDEX idx_order_status (order_status),
    INDEX idx_created_at (created_at)
);
```

```sql
-- Create customers table for optional customer profiles
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) UNIQUE NOT NULL,
    email VARCHAR(100),
    default_address TEXT,
    total_orders INT DEFAULT 0,
    total_spent DECIMAL(10,2) DEFAULT 0.00,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_phone (phone),
    INDEX idx_email (email)
);