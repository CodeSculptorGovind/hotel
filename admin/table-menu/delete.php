
<?php
session_start();
include '../auth_check.php';
include '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get item ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    header('Location: index.php?error=Invalid item ID');
    exit();
}

try {
    // Get item name for confirmation
    $itemQuery = "SELECT name FROM menu_items WHERE id = ?";
    $itemStmt = $db->prepare($itemQuery);
    $itemStmt->execute([$id]);
    $item = $itemStmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        header('Location: index.php?error=Item not found');
        exit();
    }

    // Delete the item
    $deleteQuery = "DELETE FROM menu_items WHERE id = ?";
    $deleteStmt = $db->prepare($deleteQuery);
    $result = $deleteStmt->execute([$id]);

    if ($result) {
        header('Location: index.php?success=Menu item "' . urlencode($item['name']) . '" deleted successfully');
    } else {
        header('Location: index.php?error=Failed to delete menu item');
    }
} catch (PDOException $e) {
    header('Location: index.php?error=' . urlencode('Database error: ' . $e->getMessage()));
}
exit();
?>
