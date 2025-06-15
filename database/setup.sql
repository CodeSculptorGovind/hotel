
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
