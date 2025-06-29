
<?php
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    echo "<h2>Setting up Takeaway Menu Database...</h2>";
    
    // Read and execute the schema file
    $schema = file_get_contents('database/takeaway_menu_schema.sql');
    
    // Split by semicolons and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $schema)));
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            try {
                $db->exec($statement);
                echo "<p style='color: green;'>✓ Executed: " . substr($statement, 0, 50) . "...</p>";
            } catch (PDOException $e) {
                echo "<p style='color: orange;'>⚠ Warning: " . $e->getMessage() . "</p>";
            }
        }
    }
    
    echo "<h3 style='color: green;'>✅ Takeaway Menu Database Setup Complete!</h3>";
    echo "<p>You can now:</p>";
    echo "<ul>";
    echo "<li>Access the admin panel to manage your takeaway menu</li>";
    echo "<li>Visit <a href='takeaway-menu.php'>takeaway-menu.php</a> to see your customer-facing takeaway menu</li>";
    echo "<li>Use the API endpoints for menu management</li>";
    echo "</ul>";
    
    echo "<h4>Next Steps:</h4>";
    echo "<ol>";
    echo "<li>Go to <a href='admin/'>Admin Panel</a> and login</li>";
    echo "<li>Navigate to 'Takeaway Menu' section</li>";
    echo "<li>Add your actual menu categories and items</li>";
    echo "<li>Upload images for your menu items</li>";
    echo "<li>Configure pricing and availability</li>";
    echo "</ol>";
    
} catch(PDOException $e) {
    echo "<h3 style='color: red;'>❌ Error setting up database:</h3>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background: #f8f9fa;
}

h2, h3, h4 {
    color: #8B4513;
    font-family: Georgia, serif;
}

ul, ol {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

a {
    color: #8B4513;
    text-decoration: none;
    font-weight: bold;
}

a:hover {
    color: #D2691E;
}
</style>
