
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

switch($method) {
    case 'POST':
        createOrder($db);
        break;
        
    case 'GET':
        if(isset($_GET['order_id'])) {
            getOrder($_GET['order_id'], $db);
        } else {
            getAllOrders($db);
        }
        break;
        
    case 'PUT':
        if(isset($_GET['order_id'])) {
            updateOrderStatus($_GET['order_id'], $db);
        }
        break;
}

function createOrder($db) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    try {
        $db->beginTransaction();
        
        // Generate order ID
        $order_id = 'ORD' . date('Ymd') . strtoupper(substr(uniqid(), -6));
        
        // Insert order
        $query = "INSERT INTO orders (order_id, customer_name, customer_phone, customer_email, 
                  customer_address, special_instructions, order_items, subtotal, delivery_fee, 
                  total, order_type, order_status, created_at) 
                  VALUES (:order_id, :customer_name, :customer_phone, :customer_email, 
                  :customer_address, :special_instructions, :order_items, :subtotal, 
                  :delivery_fee, :total, :order_type, 'pending', NOW())";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':customer_name', $input['customer_name']);
        $stmt->bindParam(':customer_phone', $input['customer_phone']);
        $stmt->bindParam(':customer_email', $input['customer_email']);
        $stmt->bindParam(':customer_address', $input['customer_address']);
        $stmt->bindParam(':special_instructions', $input['special_instructions']);
        $stmt->bindParam(':order_items', json_encode($input['order_items']));
        $stmt->bindParam(':subtotal', $input['subtotal']);
        $stmt->bindParam(':delivery_fee', $input['delivery_fee']);
        $stmt->bindParam(':total', $input['total']);
        $stmt->bindParam(':order_type', $input['order_type']);
        
        $stmt->execute();
        
        $db->commit();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Order placed successfully',
            'order_id' => $order_id
        ]);
        
    } catch(Exception $e) {
        $db->rollback();
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to place order: ' . $e->getMessage()
        ]);
    }
}

function getOrder($order_id, $db) {
    $query = "SELECT * FROM orders WHERE order_id = :order_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':order_id', $order_id);
    $stmt->execute();
    
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($order) {
        $order['order_items'] = json_decode($order['order_items'], true);
        echo json_encode(['success' => true, 'order' => $order]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
    }
}

function getAllOrders($db) {
    $query = "SELECT order_id, customer_name, customer_phone, total, order_status, 
              created_at FROM orders ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'orders' => $orders]);
}

function updateOrderStatus($order_id, $db) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $query = "UPDATE orders SET order_status = :status WHERE order_id = :order_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':status', $input['status']);
    $stmt->bindParam(':order_id', $order_id);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Order status updated']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update order status']);
    }
}
?>
