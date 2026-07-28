<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/certificates' => array(
        'GET' => function() {
            // Select all certificates
            $stmt = $pdo->prepare('SELECT * FROM certificates');
            $stmt->execute();
            $certificates = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($certificates);
        },
        'POST' => function() {
            // Validate input data
            if (!isset($input['name']) || !isset($input['description'])) {
                http_response_code(400);
                echo json_encode(array('error' => 'Invalid input'));
                exit;
            }

            // Sanitize input data
            $name = htmlspecialchars($input['name']);
            $description = htmlspecialchars($input['description']);

            // Insert new certificate
            $stmt = $pdo->prepare('INSERT INTO certificates (name, description) VALUES (:name, :description)');
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->execute();
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Certificate created successfully'));
        }
    ),
    '/certificates/:id' => array(
        'GET' => function($id) {
            // Validate input data
            if (!ctype_digit($id)) {
                http_response_code(400);
                echo json_encode(array('error' => 'Invalid input'));
                exit;
            }

            // Select certificate by ID
            $stmt = $pdo->prepare('SELECT * FROM certificates WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $certificate = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$certificate) {
                http_response_code(404);
                echo json_encode(array('error' => 'Certificate not found'));
                exit;
            }

            // Check if user is admin
            if ($_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(array('error' => 'Forbidden'));
                exit;
            }

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($certificate);
        },
        'PUT' => function($id) {
            // Validate input data
            if (!isset($input['name']) || !isset($input['description'])) {
                http_response_code(400);
                echo json_encode(array('error' => 'Invalid input'));
                exit;
            }

            // Sanitize input data
            $name = htmlspecialchars($input['name']);
            $description = htmlspecialchars($input['description']);

            // Check if user is admin
            if ($_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(array('error' => 'Forbidden'));
                exit;
            }

            // Update certificate
            $stmt = $pdo->prepare('UPDATE certificates SET name = :name, description = :description WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->execute();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Certificate updated successfully'));
        },
        'DELETE' => function($id) {
            // Validate input data
            if (!ctype_digit($id)) {
                http_response_code(400);
                echo json_encode(array('error' => 'Invalid input'));
                exit;
            }

            // Check if user is admin
            if ($_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(array('error' => 'Forbidden'));
                exit;
            }

            // Delete certificate
            $stmt = $pdo->prepare('DELETE FROM certificates WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Certificate deleted successfully'));
        }
    )
);

// Get route
$route = explode('/', $_SERVER['REQUEST_URI']);
array_shift($route); // Remove empty string
array_shift($route); // Remove 'شهادات'

// Get method
$method = $_SERVER['REQUEST_METHOD'];

// Check if route exists
if (isset($routes['/' . implode('/', $route)]) && isset($routes['/' . implode('/', $route)][$method])) {
    $routes['/' . implode('/', $route)][$method]($route[0]);
} else {
    http_response_code(404);
    echo json_encode(array('error' => 'Not found'));
}
?>