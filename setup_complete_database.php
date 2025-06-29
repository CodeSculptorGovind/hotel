
<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($db === null) {
    die("Could not connect to database. Please check your database configuration.");
}

try {
    echo "<h2>Setting up Complete Restaurant Database...</h2>";
    
    // Read the complete schema file
    $sqlFile = 'database/complete_restaurant_schema.sql';
    
    if (!file_exists($sqlFile)) {
        die("Error: complete_restaurant_schema.sql file not found in database/ directory");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Split by semicolons and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($statements as $statement) {
        if (!empty($statement) && !preg_match('/^\s*--/', $statement)) {
            try {
                $db->exec($statement);
                $successCount++;
                
                // Show only important operations
                if (stripos($statement, 'CREATE TABLE') !== false) {
                    preg_match('/CREATE TABLE.*?(\w+)\s*\(/i', $statement, $matches);
                    $tableName = isset($matches[1]) ? $matches[1] : 'unknown';
                    echo "<p style='color: green;'>✓ Created table: <strong>{$tableName}</strong></p>";
                } elseif (stripos($statement, 'INSERT') !== false) {
                    echo "<p style='color: blue;'>✓ Inserted sample data</p>";
                }
                
            } catch (PDOException $e) {
                $errorCount++;
                if (strpos($e->getMessage(), 'already exists') === false) {
                    echo "<p style='color: orange;'>⚠ Warning: " . $e->getMessage() . "</p>";
                }
            }
        }
    }
    
    echo "<hr>";
    echo "<h3 style='color: green;'>✅ Database Setup Complete!</h3>";
    echo "<p><strong>Statistics:</strong></p>";
    echo "<ul>";
    echo "<li>Successful operations: {$successCount}</li>";
    echo "<li>Warnings/Errors: {$errorCount}</li>";
    echo "</ul>";
    
    echo "<h4>Database Tables Created:</h4>";
    echo "<ul>";
    echo "<li><strong>categories</strong> - Table menu categories</li>";
    echo "<li><strong>takeaway_categories</strong> - Takeaway menu categories</li>";
    echo "<li><strong>menu_items</strong> - Table menu items</li>";
    echo "<li><strong>takeaway_menu_items</strong> - Takeaway menu items</li>";
    echo "<li><strong>item_variations</strong> - Item modifiers/variations</li>";
    echo "<li><strong>orders</strong> - All types of orders</li>";
    echo "<li><strong>order_items</strong> - Order item details</li>";
    echo "<li><strong>reservations</strong> - Table reservations</li>";
    echo "<li><strong>customers</strong> - Customer information</li>";
    echo "<li><strong>admin_users</strong> - Admin panel users</li>";
    echo "<li><strong>restaurant_settings</strong> - System settings</li>";
    echo "</ul>";
    
    echo "<h4>What's Next:</h4>";
    echo "<ol>";
    echo "<li>Visit <a href='admin/' target='_blank'>Admin Panel</a> (login: admin / admin123)</li>";
    echo "<li>Go to <a href='admin/table-menu/' target='_blank'>Table Menu Management</a></li>";
    echo "<li>Go to <a href='admin/takeaway-menu/' target='_blank'>Takeaway Menu Management</a></li>";
    echo "<li>Test the <a href='takeaway-menu.php' target='_blank'>Takeaway Menu</a></li>";
    echo "<li>Test <a href='reservation.php' target='_blank'>Table Reservations</a></li>";
    echo "</ol>";
    
    echo "<p style='background: #f0f8ff; padding: 15px; border-left: 4px solid #0066cc;'>";
    echo "<strong>Important:</strong> Please change the default admin password after logging in!";
    echo "</p>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>Error setting up database: " . $e->getMessage() . "</p>";
    echo "<p>Please check your database configuration in config/database.php</p>";
}
?>
