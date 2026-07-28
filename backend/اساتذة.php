<?php

require_once 'db.php';

// Get user role from session
$userRole = $_SESSION['userRole'];

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($userRole == 'admin') {
        // Select all teachers
        $stmt = $pdo->prepare("SELECT * FROM teachers");
        $stmt->execute();
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($teachers);
    } else {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input data
    if (!isset($inputData['name']) || !isset($inputData['email']) || !isset($inputData['phone'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Sanitize input data
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($inputData['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($inputData['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Insert new teacher
    $stmt = $pdo->prepare("INSERT INTO teachers (name, email, phone) VALUES (:name, :email, :phone)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Teacher added successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to add teacher']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['email']) || !isset($inputData['phone'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($inputData['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($inputData['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is admin
    if ($userRole != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Update teacher
    $stmt = $pdo->prepare("UPDATE teachers SET name = :name, email = :email, phone = :phone WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Teacher updated successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to update teacher']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is admin
    if ($userRole != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Delete teacher
    $stmt = $pdo->prepare("DELETE FROM teachers WHERE id = :id");
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Teacher deleted successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to delete teacher']);
    }
}