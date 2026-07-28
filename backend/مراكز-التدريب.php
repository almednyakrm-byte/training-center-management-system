<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/get' => function() use ($inputData, $db) {
        // Validate and sanitize input
        $limit = isset($inputData['limit']) ? intval($inputData['limit']) : 10;
        $offset = isset($inputData['offset']) ? intval($inputData['offset']) : 0;

        // SQL query
        $stmt = $db->prepare('SELECT * FROM مراكز_التدريب ORDER BY id LIMIT :limit OFFSET :offset');
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch data
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Output
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    },
    '/get/:id' => function($id) use ($inputData, $db) {
        // Validate and sanitize input
        $id = intval($id);

        // SQL query
        $stmt = $db->prepare('SELECT * FROM مراكز_التدريب WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch data
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Output
        if ($data) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Not found'));
        }
    },
    '/create' => function() use ($inputData, $db, $userRole, $userID) {
        // Validate and sanitize input
        $name = trim($inputData['name']);
        $address = trim($inputData['address']);

        // Check if user is admin
        if ($userRole != 'admin') {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
            exit;
        }

        // SQL query
        $stmt = $db->prepare('INSERT INTO مراكز_التدريب (name, address, created_by) VALUES (:name, :address, :created_by)');
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':created_by', $userID, PDO::PARAM_INT);
        $stmt->execute();

        // Output
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Created successfully'));
    },
    '/update/:id' => function($id) use ($inputData, $db, $userRole, $userID) {
        // Validate and sanitize input
        $id = intval($id);
        $name = trim($inputData['name']);
        $address = trim($inputData['address']);

        // Check if user is admin
        if ($userRole != 'admin') {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
            exit;
        }

        // SQL query
        $stmt = $db->prepare('UPDATE مراكز_التدريب SET name = :name, address = :address, updated_by = :updated_by WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':updated_by', $userID, PDO::PARAM_INT);
        $stmt->execute();

        // Output
        if ($stmt->rowCount()) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Updated successfully'));
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Not found'));
        }
    },
    '/delete/:id' => function($id) use ($inputData, $db, $userRole, $userID) {
        // Validate and sanitize input
        $id = intval($id);

        // Check if user is admin
        if ($userRole != 'admin') {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
            exit;
        }

        // SQL query
        $stmt = $db->prepare('DELETE FROM مراكز_التدريب WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Output
        if ($stmt->rowCount()) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Deleted successfully'));
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Not found'));
        }
    }
);

// Get route
$route = $_SERVER['REQUEST_URI'];
$route = explode('/', $route);
array_shift($route); // Remove empty string
array_shift($route); // Remove empty string

// Call route function
if (isset($routes['/' . implode('/', $route)])) {
    call_user_func($routes['/' . implode('/', $route)], ...array_slice($route, 1));
} else {
    http_response_code(404);
    echo json_encode(array('error' => 'Not found'));
}