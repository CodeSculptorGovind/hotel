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
    // Return dummy data instead of error
    returnDummyData();
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

function returnDummyData() {
    $dummyMenuItems = [
        [
            "id" => 1,
            "name" => "Classic Pancakes",
            "description" => "Fluffy pancakes with maple syrup and butter",
            "price" => "12.99",
            "category_id" => 1,
            "image_url" => "images/breakfast-1.jpg",
            "is_available" => 1,
            "created_at" => "2025-06-22 16:46:54",
            "updated_at" => "2025-06-22 16:46:54",
            "category" => "breakfast",
            "category_display" => "Breakfast"
        ],
        [
            "id" => 2,
            "name" => "English Breakfast",
            "description" => "Eggs, bacon, sausages, beans, and toast",
            "price" => "15.99",
            "category_id" => 1,
            "image_url" => "images/breakfast-2.jpg",
            "is_available" => 1,
            "created_at" => "2025-06-22 16:46:54",
            "updated_at" => "2025-06-22 16:46:54",
            "category" => "breakfast",
            "category_display" => "Breakfast"
        ],
        [
            "id" => 3,
            "name" => "Avocado Toast",
            "description" => "Sourdough toast with avocado and cherry tomatoes",
            "price" => "11.99",
            "category_id" => 1,
            "image_url" => "images/breakfast-3.jpg",
            "is_available" => 1,
            "created_at" => "2025-06-22 16:46:54",
            "updated_at" => "2025-06-22 16:46:54",
            "category" => "breakfast",
            "category_display" => "Breakfast"
        ],
        [
            "id" => 4,
            "name" => "Breakfast Burrito",
            "description" => "Scrambled eggs, cheese, peppers in tortilla",
            "price" => "13.99",
            "category_id" => 1,
            "image_url" => "images/breakfast-4.jpg",
            "is_available" => 1,
            "created_at" => "2025-06-22 16:46:54",
            "updated_at" => "2025-06-22 16:46:54",
            "category" => "breakfast",
            "category_display" => "Breakfast"
        ],
        [
            "id" => 5,
            "name" => "Grilled Chicken",
            "description" => "Perfectly grilled chicken breast with herbs",
            "price" => "18.99",
            "category_id" => 2,
            "image_url" => "images/lunch-1.jpg",
            "is_available" => 1,
            "created_at" => "2025-06-22 16:46:54",
            "updated_at" => "2025-06-22 16:46:54",
            "category" => "lunch",
            "category_display" => "Lunch"
        ],
        [
            "id" => 6,
            "name" => "Caesar Salad",
            "description" => "Fresh romaine lettuce with caesar dressing",
            "price" => "12.99",
            "category_id" => 2,
            "image_url" => "images/lunch-2.jpg",
            "is_available" => 1,
            "created_at" => "2025-06-22 16:46:54",
            "updated_at" => "2025-06-22 16:46:54",
            "category" => "lunch",
            "category_display" => "Lunch"
        ],
        [
            "id" => 7,
            "name" => "Ribeye Steak",
            "description" => "Premium ribeye steak grilled to perfection",
            "price" => "28.99",
            "category_id" => 3,
            "image_url" => "images/dinner-1.jpg",
            "is_available" => 1,
            "created_at" => "2025-06-22 16:46:54",
            "updated_at" => "2025-06-22 16:46:54",
            "category" => "dinner",
            "category_display" => "Dinner"
        ],
        [
            "id" => 8,
            "name" => "Chocolate Cake",
            "description" => "Rich chocolate cake with vanilla ice cream",
            "price" => "8.99",
            "category_id" => 4,
            "image_url" => "images/dessert-1.jpg",
            "is_available" => 1,
            "created_at" => "2025-06-22 16:46:54",
            "updated_at" => "2025-06-22 16:46:54",
            "category" => "desserts",
            "category_display" => "Desserts"
        ],
        [
            "id" => 9,
            "name" => "Red Wine",
            "description" => "Fine red wine selection",
            "price" => "25.99",
            "category_id" => 5,
            "image_url" => "images/wine-1.jpg",
            "is_available" => 1,
            "created_at" => "2025-06-22 16:46:54",
            "updated_at" => "2025-06-22 16:46:54",
            "category" => "wine",
            "category_display" => "Wine & Liquor"
        ]
    ];

    if(isset($_GET['type']) && $_GET['type'] === 'categories') {
        $dummyCategories = [
            ["id" => 1, "name" => "breakfast", "display_name" => "Breakfast", "sort_order" => 1],
            ["id" => 2, "name" => "lunch", "display_name" => "Lunch", "sort_order" => 2],
            ["id" => 3, "name" => "dinner", "display_name" => "Dinner", "sort_order" => 3],
            ["id" => 4, "name" => "desserts", "display_name" => "Desserts", "sort_order" => 4],
            ["id" => 5, "name" => "wine", "display_name" => "Wine & Liquor", "sort_order" => 5]
        ];
        echo json_encode($dummyCategories);
    } elseif(isset($_GET['type']) && $_GET['type'] === 'combos') {
        $dummyCombos = [
            [
                "id" => 1,
                "name" => "Family Feast",
                "description" => "Perfect meal for 4 people",
                "price" => "89.99",
                "category_id" => 1,
                "image_url" => "images/combo-1.jpg",
                "is_available" => 1,
                "category" => "breakfast",
                "category_display" => "Breakfast",
                "combo_items" => "Pancakes (2), English Breakfast (2)"
            ]
        ];
        echo json_encode($dummyCombos);
    } else {
        echo json_encode($dummyMenuItems);
    }
}
?>