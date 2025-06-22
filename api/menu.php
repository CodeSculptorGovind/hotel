<?php
session_start();
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Check if database connection was successful
if ($db === null) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$request = json_decode(file_get_contents('php://input'), true);

switch($method) {
    case 'GET':
        if(isset($_GET['type']) && $_GET['type'] === 'combos') {
            getAllCombos($db);
        } elseif(isset($_GET['type']) && $_GET['type'] === 'categories') {
            getAllCategories($db);
        } else {
            getAllMenuItems($db);
        }
        break;

    case 'POST':
        // Check admin authentication for adding items
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit();
        }

        if(isset($_GET['type']) && $_GET['type'] === 'combo') {
            createCombo($request, $db);
        } else {
            createMenuItem($request, $db);
        }
        break;

    case 'PUT':
        // Check admin authentication for updating items
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit();
        }

        if(isset($_GET['id'])) {
            if(isset($_GET['type']) && $_GET['type'] === 'combo') {
                updateCombo($_GET['id'], $request, $db);
            } else {
                updateMenuItem($_GET['id'], $request, $db);
            }
        }
        break;

    case 'DELETE':
        // Check admin authentication for deleting items
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit();
        }

        if(isset($_GET['id'])) {
            if(isset($_GET['type']) && $_GET['type'] === 'combo') {
                deleteCombo($_GET['id'], $db);
            } else {
                deleteMenuItem($_GET['id'], $db);
            }
        }
        break;
}

function getAllMenuItems($db) {
    $query = "SELECT m.*, c.name as category, c.display_name as category_display 
              FROM menu_items m 
              JOIN categories c ON m.category_id = c.id 
              WHERE m.is_available = 1 AND c.is_active = 1 
              ORDER BY c.sort_order, m.name";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($items);
}

function getAllCombos($db) {
    $query = "SELECT c.*, cat.name as category, cat.display_name as category_display,
              GROUP_CONCAT(CONCAT(m.name, ' (', cir.quantity, ')') SEPARATOR ', ') as combo_items
              FROM combo_items c
              JOIN categories cat ON c.category_id = cat.id
              LEFT JOIN combo_item_relations cir ON c.id = cir.combo_id
              LEFT JOIN menu_items m ON cir.menu_item_id = m.id
              WHERE c.is_available = 1 AND cat.is_active = 1
              GROUP BY c.id
              ORDER BY cat.sort_order, c.name";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $combos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($combos);
}

function getAllCategories($db) {
    $query = "SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($categories);
}

function createMenuItem($data, $db) {
    $query = "INSERT INTO menu_items (name, description, price, category_id, image_url, is_available) 
              VALUES (:name, :description, :price, :category_id, :image_url, :is_available)";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':price', $data['price']);
    $stmt->bindParam(':category_id', $data['category_id']);
    $stmt->bindParam(':image_url', $data['image_url']);
    $stmt->bindParam(':is_available', $data['is_available']);

    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Menu item created successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create menu item']);
    }
}

function createCombo($data, $db) {
    try {
        $db->beginTransaction();

        // Insert combo
        $query = "INSERT INTO combo_items (name, description, price, category_id, image_url, is_available) 
                  VALUES (:name, :description, :price, :category_id, :image_url, :is_available)";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':image_url', $data['image_url']);
        $stmt->bindParam(':is_available', $data['is_available']);

        $stmt->execute();
        $combo_id = $db->lastInsertId();

        // Insert combo items
        if(isset($data['combo_items']) && is_array($data['combo_items'])) {
            $query = "INSERT INTO combo_item_relations (combo_id, menu_item_id, quantity, is_optional) 
                      VALUES (:combo_id, :menu_item_id, :quantity, :is_optional)";
            $stmt = $db->prepare($query);

            foreach($data['combo_items'] as $item) {
                $stmt->bindParam(':combo_id', $combo_id);
                $stmt->bindParam(':menu_item_id', $item['menu_item_id']);
                $stmt->bindParam(':quantity', $item['quantity']);
                $stmt->bindParam(':is_optional', $item['is_optional']);
                $stmt->execute();
            }
        }

        $db->commit();
        echo json_encode(['success' => true, 'message' => 'Combo created successfully']);
    } catch(Exception $e) {
        $db->rollback();
        echo json_encode(['success' => false, 'message' => 'Failed to create combo: ' . $e->getMessage()]);
    }
}

function updateMenuItem($id, $data, $db) {
    $query = "UPDATE menu_items SET name = :name, description = :description, price = :price, 
              category_id = :category_id, image_url = :image_url, is_available = :is_available WHERE id = :id";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':price', $data['price']);
    $stmt->bindParam(':category_id', $data['category_id']);
    $stmt->bindParam(':image_url', $data['image_url']);
    $stmt->bindParam(':is_available', $data['is_available']);
    $stmt->bindParam(':id', $id);

    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Menu item updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update menu item']);
    }
}

function updateCombo($id, $data, $db) {
    try {
        $db->beginTransaction();

        // Update combo
        $query = "UPDATE combo_items SET name = :name, description = :description, price = :price, 
                  category_id = :category_id, image_url = :image_url, is_available = :is_available WHERE id = :id";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':image_url', $data['image_url']);
        $stmt->bindParam(':is_available', $data['is_available']);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Delete existing combo items
        $query = "DELETE FROM combo_item_relations WHERE combo_id = :combo_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':combo_id', $id);
        $stmt->execute();

        // Insert updated combo items
        if(isset($data['combo_items']) && is_array($data['combo_items'])) {
            $query = "INSERT INTO combo_item_relations (combo_id, menu_item_id, quantity, is_optional) 
                      VALUES (:combo_id, :menu_item_id, :quantity, :is_optional)";
            $stmt = $db->prepare($query);

            foreach($data['combo_items'] as $item) {
                $stmt->bindParam(':combo_id', $id);
                $stmt->bindParam(':menu_item_id', $item['menu_item_id']);
                $stmt->bindParam(':quantity', $item['quantity']);
                $stmt->bindParam(':is_optional', $item['is_optional']);
                $stmt->execute();
            }
        }

        $db->commit();
        echo json_encode(['success' => true, 'message' => 'Combo updated successfully']);
    } catch(Exception $e) {
        $db->rollback();
        echo json_encode(['success' => false, 'message' => 'Failed to update combo: ' . $e->getMessage()]);
    }
}

function deleteMenuItem($id, $db) {
    $query = "DELETE FROM menu_items WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);

    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Menu item deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete menu item']);
    }
}

function deleteCombo($id, $db) {
    $query = "DELETE FROM combo_items WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);

    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Combo deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete combo']);
    }
}
?>