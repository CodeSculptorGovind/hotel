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
        if(isset($_GET['encoded_id'])) {
            getReservationByEncodedId($_GET['encoded_id'], $db);
        } elseif(isset($_GET['admin']) && $_GET['admin'] == 'true') {
            // Check admin authentication
            if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized']);
                exit();
            }

            // Admin view - get all reservations
            getAllReservations($db);
        }
        break;

    case 'POST':
        createReservation($request, $db);
        break;

    case 'PUT':
        // Check admin authentication for updates
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit();
        }

        if(isset($_GET['id'])) {
            updateReservation($_GET['id'], $request, $db);
        }
        break;

    case 'DELETE':
        if(isset($_GET['id'])) {
            deleteReservation($_GET['id'], $db);
        }
        break;
}

function createReservation($data, $db) {
    $encoded_id = uniqid('MRH_', true);

    $query = "INSERT INTO reservations (name, email, phone, date, time, guests, request_type, special_requests, encoded_id) 
              VALUES (:name, :email, :phone, :date, :time, :guests, :request_type, :special_requests, :encoded_id)";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':phone', $data['phone']);
    $stmt->bindParam(':date', $data['date']);
    $stmt->bindParam(':time', $data['time']);
    $stmt->bindParam(':guests', $data['guests']);
    $stmt->bindParam(':request_type', $data['request_type']);
    $stmt->bindParam(':special_requests', $data['special_requests']);
    $stmt->bindParam(':encoded_id', $encoded_id);

    if($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Reservation request submitted successfully',
            'encoded_id' => $encoded_id
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit reservation']);
    }
}

function getAllReservations($db) {
    $query = "SELECT * FROM reservations ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($reservations);
}

function getReservationByEncodedId($encoded_id, $db) {
    $query = "SELECT * FROM reservations WHERE encoded_id = :encoded_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':encoded_id', $encoded_id);
    $stmt->execute();

    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
    if($reservation) {
        echo json_encode($reservation);
    } else {
        echo json_encode(['error' => 'Reservation not found']);
    }
}

function updateReservation($id, $data, $db) {
    $query = "UPDATE reservations SET status = :status";
    $params = [':status' => $data['status'], ':id' => $id];

    if(isset($data['date'])) {
        $query .= ", date = :date";
        $params[':date'] = $data['date'];
    }
    if(isset($data['time'])) {
        $query .= ", time = :time";
        $params[':time'] = $data['time'];
    }

    $query .= " WHERE id = :id";

    $stmt = $db->prepare($query);

    if($stmt->execute($params)) {
        echo json_encode(['success' => true, 'message' => 'Reservation updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update reservation']);
    }
}

function deleteReservation($id, $db) {
    $query = "DELETE FROM reservations WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);

    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Reservation deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete reservation']);
    }
}
?>