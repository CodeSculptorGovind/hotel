
<?php
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
        getAllMenuItems($db);
        break;
    
    case 'POST':
        createMenuItem($request, $db);
        break;
    
    case 'PUT':
        if(isset($_GET['id'])) {
            updateMenuItem($_GET['id'], $request, $db);
        }
        break;
    
    case 'DELETE':
        if(isset($_GET['id'])) {
            deleteMenuItem($_GET['id'], $db);
        }
        break;
}

function getAllMenuItems($db) {
    $query = "SELECT * FROM menu_items ORDER BY category, name";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($items);
}

function createMenuItem($data, $db) {
    $query = "INSERT INTO menu_items (name, description, price, category, image_url, is_available) 
              VALUES (:name, :description, :price, :category, :image_url, :is_available)";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':price', $data['price']);
    $stmt->bindParam(':category', $data['category']);
    $stmt->bindParam(':image_url', $data['image_url']);
    $stmt->bindParam(':is_available', $data['is_available']);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Menu item created successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create menu item']);
    }
}

function updateMenuItem($id, $data, $db) {
    $query = "UPDATE menu_items SET name = :name, description = :description, price = :price, 
              category = :category, image_url = :image_url, is_available = :is_available WHERE id = :id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':price', $data['price']);
    $stmt->bindParam(':category', $data['category']);
    $stmt->bindParam(':image_url', $data['image_url']);
    $stmt->bindParam(':is_available', $data['is_available']);
    $stmt->bindParam(':id', $id);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Menu item updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update menu item']);
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
?>
