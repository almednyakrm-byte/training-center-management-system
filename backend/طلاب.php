<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $params = json_decode(file_get_contents('php://input'), true);
    if (!isset($params['limit']) || !isset($params['offset'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM طلاب ORDER BY id LIMIT :limit OFFSET :offset');
    $stmt->bindParam(':limit', $params['limit'], PDO::PARAM_INT);
    $stmt->bindParam(':offset', $params['offset'], PDO::PARAM_INT);
    $stmt->execute();

    // Fetch and return data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
}

// Handle POST request
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $params = json_decode(file_get_contents('php://input'), true);
    if (!isset($params['name']) || !isset($params['email']) || !isset($params['phone'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO طلاب (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $params['name'], PDO::PARAM_STR);
    $stmt->bindParam(':email', $params['email'], PDO::PARAM_STR);
    $stmt->bindParam(':phone', $params['phone'], PDO::PARAM_STR);
    $stmt->execute();

    // Return ID of newly inserted record
    $lastID = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $lastID]);
}

// Handle PUT request
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate and sanitize input
    $params = json_decode(file_get_contents('php://input'), true);
    if (!isset($params['id']) || !isset($params['name']) || !isset($params['email']) || !isset($params['phone'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE طلاب SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $params['id'], PDO::PARAM_INT);
    $stmt->bindParam(':name', $params['name'], PDO::PARAM_STR);
    $stmt->bindParam(':email', $params['email'], PDO::PARAM_STR);
    $stmt->bindParam(':phone', $params['phone'], PDO::PARAM_STR);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record updated successfully']);
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate and sanitize input
    $params = json_decode(file_get_contents('php://input'), true);
    if (!isset($params['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM طلاب WHERE id = :id');
    $stmt->bindParam(':id', $params['id'], PDO::PARAM_INT);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record deleted successfully']);
}