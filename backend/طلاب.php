<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get input data from JSON or POST request
$inputData = json_decode(file_get_contents('php://input'), true);
if (empty($inputData)) {
    $inputData = $_POST;
}

// Validate and sanitize input data
if (empty($inputData['name']) || empty($inputData['email']) || empty($inputData['phone'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

// Prepare database connection
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// GET all students
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    try {
        $stmt = $db->prepare('SELECT * FROM طلاب');
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($students);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// GET student by ID
if (isset($_GET['action']) && $_GET['action'] == 'get_by_id') {
    try {
        $stmt = $db->prepare('SELECT * FROM طلاب WHERE ID = :id');
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($student) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($student);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Student not found']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// POST create student
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    try {
        if ($userRole != 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }
        $stmt = $db->prepare('INSERT INTO طلاب (name, email, phone) VALUES (:name, :email, :phone)');
        $stmt->bindParam(':name', $inputData['name']);
        $stmt->bindParam(':email', $inputData['email']);
        $stmt->bindParam(':phone', $inputData['phone']);
        $stmt->execute();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Student created successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// PUT update student
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    try {
        if ($userRole != 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }
        $stmt = $db->prepare('UPDATE طلاب SET name = :name, email = :email, phone = :phone WHERE ID = :id');
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->bindParam(':name', $inputData['name']);
        $stmt->bindParam(':email', $inputData['email']);
        $stmt->bindParam(':phone', $inputData['phone']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Student updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// DELETE student
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    try {
        if ($userRole != 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }
        $stmt = $db->prepare('DELETE FROM طلاب WHERE ID = :id');
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Student deleted successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Close database connection
$db = null;