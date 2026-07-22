<?php
// Start the session to handle user authentication
session_start();

// Import the database connection script
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response with their details
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $response = array(
        'status' => 'logged_in',
        'user_id' => $user_id,
        'username' => $username
    );
    echo json_encode($response);
    exit;
}

// Handle the login action
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if the username and password are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Sanitize the input fields to prevent SQL injection
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Prepare the SQL query to select the user
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        // Check if the user exists and the password is correct
        if ($user && password_verify($password, $user['password'])) {
            // If the user is valid, start a session and store their details
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $response = array(
                'status' => 'logged_in',
                'user_id' => $_SESSION['user_id'],
                'username' => $_SESSION['username']
            );
            echo json_encode($response);
        } else {
            // If the user is not valid, return an error message
            $response = array(
                'status' => 'error',
                'message' => 'Invalid username or password'
            );
            echo json_encode($response);
        }
    } else {
        // If the username or password is not set, return an error message
        $response = array(
            'status' => 'error',
            'message' => 'Username and password are required'
        );
        echo json_encode($response);
    }
}

// Handle the register action
if (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if the username, email, and password are set
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        // Sanitize the input fields to prevent SQL injection
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Check if the username and email are unique
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        // If the username or email is already taken, return an error message
        if ($user) {
            $response = array(
                'status' => 'error',
                'message' => 'Username or email is already taken'
            );
            echo json_encode($response);
            exit;
        }

        // Hash the password using password_hash()
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query to insert the new user
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        // If the user is created successfully, return a success message
        $response = array(
            'status' => 'success',
            'message' => 'User created successfully'
        );
        echo json_encode($response);
    } else {
        // If the username, email, or password is not set, return an error message
        $response = array(
            'status' => 'error',
            'message' => 'Username, email, and password are required'
        );
        echo json_encode($response);
    }
}

// Handle the logout action
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Destroy the session to log out the user
    session_destroy();
    $response = array(
        'status' => 'logged_out'
    );
    echo json_encode($response);
}