-- Create database
CREATE DATABASE IF NOT EXISTS restaurant_db;
```

```sql
USE restaurant_db;
```

```sql
-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

```sql
-- Menu items table
CREATE TABLE IF NOT EXISTS menu_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    image_url VARCHAR(500),
    is_available TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);
```

```sql
-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id VARCHAR(50) UNIQUE NOT NULL,
    tracking_token VARCHAR(100) UNIQUE NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    special_instructions TEXT,
    subtotal DECIMAL(10,2) NOT NULL,
    tax DECIMAL(10,2) NOT NULL DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    order_type ENUM('takeaway', 'delivery', 'dine_in') DEFAULT 'takeaway',
    status ENUM('pending', 'preparing', 'ready', 'completed', 'cancelled') DEFAULT 'pending',
    estimated_pickup DATETIME,
    expires_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

```sql
-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    item_id INT NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);
```

```sql
-- Reservations table
CREATE TABLE IF NOT EXISTS reservations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    party_size INT NOT NULL,
    special_requests TEXT,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

```sql
-- Insert sample categories
INSERT IGNORE INTO categories (id, name, description) VALUES
(1, 'breakfast', 'Morning breakfast items'),
(2, 'lunch', 'Lunch specialties'),
(3, 'dinner', 'Evening dinner options'),
(4, 'desserts', 'Sweet treats and desserts'),
(5, 'wine', 'Wine and alcoholic beverages'),
(6, 'drinks', 'Non-alcoholic beverages');
```

```sql
-- Insert sample menu items
INSERT IGNORE INTO menu_items (id, name, description, price, category_id, image_url, is_available) VALUES
(1, 'Classic Pancakes', 'Fluffy pancakes with maple syrup and butter', 12.99, 1, 'images/breakfast-1.jpg', 1),
(2, 'English Breakfast', 'Eggs, bacon, sausages, beans, and toast', 15.99, 1, 'images/breakfast-2.jpg', 1),
(3, 'Avocado Toast', 'Sourdough toast with avocado and cherry tomatoes', 11.99, 1, 'images/breakfast-3.jpg', 1),
(4, 'Breakfast Burrito', 'Scrambled eggs, cheese, peppers in tortilla', 13.99, 1, 'images/breakfast-4.jpg', 1),
(5, 'Grilled Chicken', 'Perfectly grilled chicken breast with herbs', 18.99, 2, 'images/lunch-1.jpg', 1),
(6, 'Caesar Salad', 'Fresh romaine lettuce with caesar dressing', 12.99, 2, 'images/lunch-2.jpg', 1),
(7, 'Ribeye Steak', 'Premium ribeye steak grilled to perfection', 32.99, 3, 'images/dinner-1.jpg', 1),
(8, 'Grilled Salmon', 'Atlantic salmon with seasonal vegetables', 26.99, 3, 'images/dinner-2.jpg', 1),
(9, 'Chocolate Cake', 'Rich chocolate cake with vanilla ice cream', 8.99, 4, 'images/dessert-1.jpg', 1),
(10, 'Tiramisu', 'Classic Italian coffee-flavored dessert', 9.99, 4, 'images/dessert-2.jpg', 1);