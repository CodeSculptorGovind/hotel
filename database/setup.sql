
-- Database setup for Mall Road House
CREATE DATABASE IF NOT EXISTS mall_road_house;
USE mall_road_house;

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

-- Table for menu items
CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category ENUM('breakfast', 'lunch', 'dinner', 'desserts', 'wine', 'drinks') NOT NULL,
    image_url VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for admin users
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO admin_users (username, password, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@mallroadhouse.com');

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

-- Table for combo items
CREATE TABLE IF NOT EXISTS combo_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category ENUM('breakfast', 'lunch', 'dinner', 'desserts', 'wine', 'drinks') NOT NULL,
    image_url VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    is_combo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

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

-- Insert some sample menu items
INSERT IGNORE INTO menu_items (name, description, price, category, image_url) VALUES 
('Grilled Chicken', 'Perfectly grilled chicken breast with herbs', 15.99, 'lunch', 'images/lunch-1.jpg'),
('Caesar Salad', 'Fresh romaine lettuce with caesar dressing', 8.99, 'lunch', 'images/lunch-2.jpg'),
('French Fries', 'Golden crispy french fries', 4.99, 'lunch', 'images/lunch-3.jpg'),
('Garlic Bread', 'Homemade garlic bread', 3.99, 'lunch', 'images/lunch-4.jpg'),
('Chocolate Cake', 'Rich chocolate cake slice', 6.99, 'desserts', 'images/dessert-1.jpg'),
('Coffee', 'Fresh brewed coffee', 2.99, 'drinks', 'images/drink-1.jpg'),
('Beef Burger', 'Juicy beef burger with lettuce and tomato', 12.99, 'lunch', 'images/lunch-5.jpg'),
('Pasta Carbonara', 'Creamy pasta with bacon and eggs', 14.99, 'dinner', 'images/dinner-1.jpg');

-- Insert sample combo items
INSERT IGNORE INTO combo_items (name, description, price, category) VALUES 
('Lunch Special', 'Grilled chicken with salad and fries', 19.99, 'lunch'),
('Dinner Combo', 'Pasta with garlic bread and dessert', 21.99, 'dinner'),
('Burger Meal', 'Beef burger with fries and drink', 16.99, 'lunch');
