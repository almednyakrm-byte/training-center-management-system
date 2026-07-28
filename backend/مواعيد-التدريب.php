<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Validate input data
if (!isset($inputData['id']) && !isset($inputData['date']) && !isset($inputData['time']) && !isset($inputData['trainer_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input data']);
    exit;
}

// Sanitize input data
$inputData['date'] = date('Y-m-d', strtotime($inputData['date']));
$inputData['time'] = date('H:i', strtotime($inputData['time']));

// Connect to database
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    $stmt = $db->prepare('SELECT * FROM مواعيد_التدريب');
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}

// Handle GET request by ID
if (isset($_GET['action']) && $_GET['action'] == 'get_by_id') {
    $id = $_GET['id'];
    $stmt = $db->prepare('SELECT * FROM مواعيد_التدريب WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
    }
    exit;
}

// Handle POST request
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $stmt = $db->prepare('INSERT INTO مواعيد_التدريب (date, time, trainer_id) VALUES (:date, :time, :trainer_id)');
    $stmt->bindParam(':date', $inputData['date']);
    $stmt->bindParam(':time', $inputData['time']);
    $stmt->bindParam(':trainer_id', $inputData['trainer_id']);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Created successfully']);
    exit;
}

// Handle PUT request
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $id = $inputData['id'];
    $stmt = $db->prepare('UPDATE مواعيد_التدريب SET date = :date, time = :time, trainer_id = :trainer_id WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':date', $inputData['date']);
    $stmt->bindParam(':time', $inputData['time']);
    $stmt->bindParam(':trainer_id', $inputData['trainer_id']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Updated successfully']);
    exit;
}

// Handle DELETE request
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $id = $inputData['id'];
    $stmt = $db->prepare('DELETE FROM مواعيد_التدريب WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Deleted successfully']);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
exit;