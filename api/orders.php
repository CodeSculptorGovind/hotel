
<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                throw new Exception('Invalid JSON data');
            }
            
            // Validate required fields
            $required_fields = ['customer_name', 'customer_email', 'customer_phone', 'items', 'total'];
            foreach ($required_fields as $field) {
                if (empty($input[$field])) {
                    throw new Exception("Missing required field: $field");
                }
            }
            
            // Generate tracking token (expires in 24 hours)
            $tracking_token = bin2hex(random_bytes(16));
            $expires_at = date('Y-m-d H:i:s', strtotime('+24 hours'));
            
            // Generate order ID
            $order_id = 'MRH' . date('Ymd') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            
            // Calculate estimated pickup time (45 minutes from now)
            $estimated_pickup = date('Y-m-d H:i:s', strtotime('+45 minutes'));
            $pickup_display = date('g:i A', strtotime('+45 minutes'));
            
            // Insert order
            $stmt = $pdo->prepare("
                INSERT INTO orders (
                    order_id, tracking_token, customer_name, customer_email, 
                    customer_phone, special_instructions, subtotal, tax, total, 
                    order_type, status, estimated_pickup, expires_at, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?, ?, NOW())
            ");
            
            $stmt->execute([
                $order_id,
                $tracking_token,
                $input['customer_name'],
                $input['customer_email'],
                $input['customer_phone'],
                $input['special_instructions'] ?? '',
                $input['subtotal'],
                $input['tax'] ?? ($input['subtotal'] * 0.05),
                $input['total'],
                $input['order_type'] ?? 'takeaway',
                $estimated_pickup,
                $expires_at
            ]);
            
            $db_order_id = $pdo->lastInsertId();
            
            // Insert order items
            $stmt = $pdo->prepare("
                INSERT INTO order_items (order_id, item_id, item_name, price, quantity, subtotal) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            foreach ($input['items'] as $item) {
                $stmt->execute([
                    $db_order_id,
                    $item['id'],
                    $item['name'],
                    $item['price'],
                    $item['quantity'],
                    $item['price'] * $item['quantity']
                ]);
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Order placed successfully',
                'order_id' => $order_id,
                'tracking_token' => $tracking_token,
                'estimated_pickup' => $pickup_display,
                'expires_at' => $expires_at
            ]);
            break;
            
        case 'GET':
            if (isset($_GET['tracking_token'])) {
                $token = $_GET['tracking_token'];
                
                $stmt = $pdo->prepare("
                    SELECT o.*, oi.item_name, oi.price, oi.quantity, oi.subtotal 
                    FROM orders o 
                    LEFT JOIN order_items oi ON o.id = oi.order_id 
                    WHERE o.tracking_token = ? AND o.expires_at > NOW()
                ");
                $stmt->execute([$token]);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (empty($results)) {
                    throw new Exception('Order not found or expired');
                }
                
                // Group items by order
                $order = $results[0];
                $order['items'] = [];
                
                foreach ($results as $row) {
                    if ($row['item_name']) {
                        $order['items'][] = [
                            'name' => $row['item_name'],
                            'price' => $row['price'],
                            'quantity' => $row['quantity'],
                            'subtotal' => $row['subtotal']
                        ];
                    }
                }
                
                // Remove duplicate order fields
                unset($order['item_name'], $order['price'], $order['quantity'], $order['subtotal']);
                
                echo json_encode($order);
            } else {
                // Return all orders (admin use)
                $stmt = $pdo->prepare("SELECT * FROM orders ORDER BY created_at DESC");
                $stmt->execute();
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
            break;
            
        default:
            throw new Exception('Method not allowed');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
