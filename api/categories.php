
<?php
session_start();
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];
$request = json_decode(file_get_contents('php://input'), true);

switch($method) {
    case 'GET':
        getAllCategories($db);
        break;

    case 'POST':
        // Check admin authentication
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit();
        }
        createCategory($request, $db);
        break;

    case 'PUT':
        // Check admin authentication
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit();
        }
        
        if(isset($_GET['id'])) {
            updateCategory($_GET['id'], $request, $db);
        }
        break;

    case 'DELETE':
        // Check admin authentication
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit();
        }
        
        if(isset($_GET['id'])) {
            deleteCategory($_GET['id'], $db);
        }
        break;
}

function getAllCategories($db) {
    $query = "SELECT * FROM categories ORDER BY sort_order";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($categories);
}

function createCategory($data, $db) {
    $query = "INSERT INTO categories (name, display_name, description, sort_order, is_active) 
              VALUES (:name, :display_name, :description, :sort_order, :is_active)";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':display_name', $data['display_name']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':sort_order', $data['sort_order']);
    $stmt->bindParam(':is_active', $data['is_active']);

    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Category created successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create category']);
    }
}

function updateCategory($id, $data, $db) {
    $query = "UPDATE categories SET name = :name, display_name = :display_name, 
              description = :description, sort_order = :sort_order, is_active = :is_active 
              WHERE id = :id";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':display_name', $data['display_name']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':sort_order', $data['sort_order']);
    $stmt->bindParam(':is_active', $data['is_active']);
    $stmt->bindParam(':id', $id);

    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Category updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update category']);
    }
}

function deleteCategory($id, $db) {
    // Check if category has items
    $checkQuery = "SELECT COUNT(*) as count FROM menu_items WHERE category_id = :id";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(':id', $id);
    $checkStmt->execute();
    $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if($result['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Cannot delete category with existing menu items']);
        return;
    }

    $query = "DELETE FROM categories WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);

    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Category deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete category']);
    }
}
?>
