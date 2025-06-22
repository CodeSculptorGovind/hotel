
<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($db === null) {
    die("Could not connect to database");
}

try {
    // Create categories table
    $db->exec("CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        display_name VARCHAR(100) NOT NULL,
        description TEXT,
        is_active TINYINT(1) DEFAULT 1,
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");

    // Create menu_items table
    $db->exec("CREATE TABLE IF NOT EXISTS menu_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        category_id INT NOT NULL,
        image_url VARCHAR(255),
        is_available TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES categories(id)
    )");

    // Insert sample categories
    $db->exec("INSERT IGNORE INTO categories (id, name, display_name, sort_order) VALUES 
        (1, 'breakfast', 'Breakfast', 1),
        (2, 'lunch', 'Lunch', 2),
        (3, 'dinner', 'Dinner', 3),
        (4, 'drinks', 'Drinks', 4),
        (5, 'desserts', 'Desserts', 5)");

    // Insert sample menu items
    $db->exec("INSERT IGNORE INTO menu_items (id, name, description, price, category_id, image_url) VALUES 
        (1, 'Classic Pancakes', 'Fluffy pancakes with maple syrup and butter', 12.99, 1, 'images/breakfast-1.jpg'),
        (2, 'English Breakfast', 'Eggs, bacon, sausages, beans, and toast', 15.99, 1, 'images/breakfast-2.jpg'),
        (3, 'Avocado Toast', 'Sourdough toast with avocado and cherry tomatoes', 11.99, 1, 'images/breakfast-3.jpg'),
        (4, 'Breakfast Burrito', 'Scrambled eggs, cheese, peppers in tortilla', 13.99, 1, 'images/breakfast-4.jpg')");

    echo "Database setup completed successfully!";
} catch(PDOException $e) {
    echo "Error setting up database: " . $e->getMessage();
}
?>
