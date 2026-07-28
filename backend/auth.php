<?php

// Start the session to handle user authentication
session_start();

// Import the database connection script
require_once 'db.php';

// Define a function to check if the input fields are valid
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Define a function to handle user registration
function registerUser($username, $email, $password) {
    global $conn;

    // Prepare the SQL statement to insert a new user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    // Execute the prepared statement
    if ($stmt->execute()) {
        // If the user is registered successfully, return a JSON response with a success message
        echo json_encode(array("message" => "User registered successfully"));
    } else {
        // If there's an error, return a JSON response with an error message
        echo json_encode(array("message" => "Error registering user"));
    }

    // Close the prepared statement
    $stmt->close();
}

// Define a function to handle user login
function loginUser($username, $password) {
    global $conn;

    // Prepare the SQL statement to select a user by username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    // Execute the prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the user data
    $user = $result->fetch_assoc();

    // If the user exists and the password is correct, return a JSON response with a success message and the user data
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        echo json_encode(array("message" => "User logged in successfully", "user" => $user));
    } else {
        // If the user doesn't exist or the password is incorrect, return a JSON response with an error message
        echo json_encode(array("message" => "Invalid username or password"));
    }

    // Close the prepared statement
    $stmt->close();
}

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    echo json_encode(array("message" => "User is logged in"));
} else {
    // Handle login and register actions
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'login':
                // Validate the input fields
                $username = validateInput($_POST['username']);
                $password = validateInput($_POST['password']);

                // Check if the input fields are valid
                if (!empty($username) && !empty($password)) {
                    // Call the loginUser function to handle user login
                    loginUser($username, $password);
                } else {
                    // If the input fields are invalid, return a JSON response with an error message
                    echo json_encode(array("message" => "Invalid input fields"));
                }
                break;
            case 'register':
                // Validate the input fields
                $username = validateInput($_POST['username']);
                $email = validateInput($_POST['email']);
                $password = validateInput($_POST['password']);

                // Check if the input fields are valid
                if (!empty($username) && !empty($email) && !empty($password)) {
                    // Hash the password using password_hash()
                    $password = password_hash($password, PASSWORD_DEFAULT);

                    // Call the registerUser function to handle user registration
                    registerUser($username, $email, $password);
                } else {
                    // If the input fields are invalid, return a JSON response with an error message
                    echo json_encode(array("message" => "Invalid input fields"));
                }
                break;
        }
    }

    // Handle GET requests to check the current session user status
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'checkSession':
                // Check if the user is logged in
                if (isset($_SESSION['username'])) {
                    echo json_encode(array("message" => "User is logged in"));
                } else {
                    echo json_encode(array("message" => "User is not logged in"));
                }
                break;
        }
    }
}

// Close the database connection
$conn->close();

?>