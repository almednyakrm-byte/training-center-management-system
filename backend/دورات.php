<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Check if input data is valid
if (empty($input)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

// Define user roles
$roles = [
    'admin' => 1,
    'user' => 2
];

// Get user role
$userRole = $_SESSION['user_role'];

// Check if user is admin
$isAdmin = $userRole == $roles['admin'];

// Handle CRUD operations
switch ($input['action']) {
    case 'get':
        // Get all courses
        $stmt = $pdo->prepare('SELECT * FROM دورات');
        $stmt->execute();
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($courses);
        break;

    case 'create':
        // Validate input data
        if (!isset($input['name']) || !isset($input['description'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
            exit;
        }

        // Sanitize input data
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $description = filter_var($input['description'], FILTER_SANITIZE_STRING);

        // Insert new course
        $stmt = $pdo->prepare('INSERT INTO دورات (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        // Return new course ID
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['id' => $pdo->lastInsertId()]);
        break;

    case 'update':
        // Check if user is admin
        if (!$isAdmin) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        // Validate input data
        if (!isset($input['id']) || !isset($input['name']) || !isset($input['description'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
            exit;
        }

        // Sanitize input data
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $description = filter_var($input['description'], FILTER_SANITIZE_STRING);

        // Update course
        $stmt = $pdo->prepare('UPDATE دورات SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        // Return updated course ID
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['id' => $id]);
        break;

    case 'delete':
        // Check if user is admin
        if (!$isAdmin) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        // Validate input data
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
            exit;
        }

        // Sanitize input data
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

        // Delete course
        $stmt = $pdo->prepare('DELETE FROM دورات WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Return deleted course ID
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['id' => $id]);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
        break;
}