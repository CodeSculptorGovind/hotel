
<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $method = $_SERVER['REQUEST_METHOD'];
    $action = $_GET['action'] ?? '';
    
    switch ($method) {
        case 'GET':
            if ($action === 'categories') {
                getCategoriesWithItems($db);
            } elseif ($action === 'category' && isset($_GET['id'])) {
                getCategoryItems($db, $_GET['id']);
            } elseif ($action === 'item' && isset($_GET['id'])) {
                getItem($db, $_GET['id']);
            } else {
                getAllMenuItems($db);
            }
            break;
            
        case 'POST':
            if ($action === 'category') {
                createCategory($db);
            } elseif ($action === 'item') {
                createMenuItem($db);
            }
            break;
            
        case 'PUT':
            if ($action === 'category' && isset($_GET['id'])) {
                updateCategory($db, $_GET['id']);
            } elseif ($action === 'item' && isset($_GET['id'])) {
                updateMenuItem($db, $_GET['id']);
            }
            break;
            
        case 'DELETE':
            if ($action === 'category' && isset($_GET['id'])) {
                deleteCategory($db, $_GET['id']);
            } elseif ($action === 'item' && isset($_GET['id'])) {
                deleteMenuItem($db, $_GET['id']);
            }
            break;
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}

function getAllMenuItems($db) {
    $query = "SELECT tm.*, tc.name as category_name 
              FROM takeaway_menu_items tm 
              LEFT JOIN takeaway_categories tc ON tm.category_id = tc.id 
              WHERE tm.is_available = 1 
              ORDER BY tc.display_order, tm.display_order";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'items' => $items
    ]);
}

function getCategoriesWithItems($db) {
    // Get categories
    $categoryQuery = "SELECT * FROM takeaway_categories WHERE is_active = 1 ORDER BY display_order";
    $categoryStmt = $db->prepare($categoryQuery);
    $categoryStmt->execute();
    $categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get items for each category
    $itemQuery = "SELECT * FROM takeaway_menu_items WHERE category_id = ? AND is_available = 1 ORDER BY display_order";
    $itemStmt = $db->prepare($itemQuery);
    
    foreach ($categories as &$category) {
        $itemStmt->execute([$category['id']]);
        $category['items'] = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    echo json_encode([
        'success' => true,
        'categories' => $categories
    ]);
}

function getCategoryItems($db, $categoryId) {
    $query = "SELECT * FROM takeaway_menu_items WHERE category_id = ? AND is_available = 1 ORDER BY display_order";
    $stmt = $db->prepare($query);
    $stmt->execute([$categoryId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'items' => $items
    ]);
}

function getItem($db, $itemId) {
    $query = "SELECT tm.*, tc.name as category_name 
              FROM takeaway_menu_items tm 
              LEFT JOIN takeaway_categories tc ON tm.category_id = tc.id 
              WHERE tm.id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$itemId]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($item) {
        // Get variations
        $varQuery = "SELECT * FROM item_variations WHERE item_id = ? AND is_available = 1";
        $varStmt = $db->prepare($varQuery);
        $varStmt->execute([$itemId]);
        $item['variations'] = $varStmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    echo json_encode([
        'success' => $item ? true : false,
        'item' => $item
    ]);
}

function createCategory($db) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $query = "INSERT INTO takeaway_categories (name, display_order) VALUES (?, ?)";
    $stmt = $db->prepare($query);
    $result = $stmt->execute([
        $input['name'],
        $input['display_order'] ?? 0
    ]);
    
    echo json_encode([
        'success' => $result,
        'message' => $result ? 'Category created successfully' : 'Failed to create category',
        'id' => $db->lastInsertId()
    ]);
}

function createMenuItem($db) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $query = "INSERT INTO takeaway_menu_items (item_code, name, description, category_id, price, display_order, allergens, preparation_time) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $result = $stmt->execute([
        $input['item_code'],
        $input['name'],
        $input['description'],
        $input['category_id'],
        $input['price'],
        $input['display_order'] ?? 0,
        $input['allergens'] ?? '',
        $input['preparation_time'] ?? 15
    ]);
    
    echo json_encode([
        'success' => $result,
        'message' => $result ? 'Menu item created successfully' : 'Failed to create menu item',
        'id' => $db->lastInsertId()
    ]);
}

function updateMenuItem($db, $itemId) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $query = "UPDATE takeaway_menu_items SET 
              name = ?, description = ?, category_id = ?, price = ?, 
              display_order = ?, allergens = ?, preparation_time = ?, is_available = ?
              WHERE id = ?";
    $stmt = $db->prepare($query);
    $result = $stmt->execute([
        $input['name'],
        $input['description'],
        $input['category_id'],
        $input['price'],
        $input['display_order'] ?? 0,
        $input['allergens'] ?? '',
        $input['preparation_time'] ?? 15,
        $input['is_available'] ?? 1,
        $itemId
    ]);
    
    echo json_encode([
        'success' => $result,
        'message' => $result ? 'Menu item updated successfully' : 'Failed to update menu item'
    ]);
}

function deleteMenuItem($db, $itemId) {
    $query = "DELETE FROM takeaway_menu_items WHERE id = ?";
    $stmt = $db->prepare($query);
    $result = $stmt->execute([$itemId]);
    
    echo json_encode([
        'success' => $result,
        'message' => $result ? 'Menu item deleted successfully' : 'Failed to delete menu item'
    ]);
}

function updateCategory($db, $categoryId) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $query = "UPDATE takeaway_categories SET name = ?, display_order = ?, is_active = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $result = $stmt->execute([
        $input['name'],
        $input['display_order'] ?? 0,
        $input['is_active'] ?? 1,
        $categoryId
    ]);
    
    echo json_encode([
        'success' => $result,
        'message' => $result ? 'Category updated successfully' : 'Failed to update category'
    ]);
}

function deleteCategory($db, $categoryId) {
    $query = "DELETE FROM takeaway_categories WHERE id = ?";
    $stmt = $db->prepare($query);
    $result = $stmt->execute([$categoryId]);
    
    echo json_encode([
        'success' => $result,
        'message' => $result ? 'Category deleted successfully' : 'Failed to delete category'
    ]);
}
?>
