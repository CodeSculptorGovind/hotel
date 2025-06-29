
<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($db === null) {
    die("Could not connect to database");
}

try {
    echo "<h2>Setting up Database from setup.sql...</h2>";
    
    // Read the setup.sql file
    $sqlFile = 'database/setup.sql';
    
    if (!file_exists($sqlFile)) {
        die("Error: setup.sql file not found in database/ directory");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Split by semicolons and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement) && !preg_match('/^\s*--/', $statement)) {
            try {
                $db->exec($statement);
                echo "<p style='color: green;'>✓ Executed: " . substr(str_replace(["\n", "\r"], ' ', $statement), 0, 80) . "...</p>";
            } catch (PDOException $e) {
                echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
                echo "<p style='color: orange;'>Statement: " . substr($statement, 0, 100) . "...</p>";
            }
        }
    }
    
    echo "<h3 style='color: green;'>✅ Database Setup Complete!</h3>";
    echo "<p>Database tables have been created successfully.</p>";
    echo "<p>You can now:</p>";
    echo "<ul>";
    echo "<li>Access the <a href='admin/'>Admin Panel</a> to manage your menu</li>";
    echo "<li>Visit <a href='menu.php'>menu.php</a> to see the menu</li>";
    echo "<li>Test the reservation and ordering system</li>";
    echo "</ul>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>Error setting up database: " . $e->getMessage() . "</p>";
}
?>
