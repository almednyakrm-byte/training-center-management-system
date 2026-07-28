**create_مراكز-التدريب.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Sanitize input data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Insert data into database
    $query = "INSERT INTO مراكز_التدريب (name, address, phone, email) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssss", $name, $address, $phone, $email);
    $stmt->execute();

    // Redirect back to list page
    header('Location: list_مراكز-التدريب.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مركز تدريب جديد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .emerald-600 { color: #008E77; }
        .teal-500 { color: #0097A7; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">إضافة مركز تدريب جديد</h2>
        <form id="create-form" method="post" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3 mb-6 md:mb-0">
                    <label for="name" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">اسم المركز</label>
                    <input type="text" id="name" name="name" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3 mb-6 md:mb-0">
                    <label for="address" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">عنوان المركز</label>
                    <input type="text" id="address" name="address" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3 mb-6 md:mb-0">
                    <label for="phone" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">رقم الهاتف</label>
                    <input type="tel" id="phone" name="phone" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3 mb-6 md:mb-0">
                    <label for="email" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                </div>
            </div>
            <button type="submit" name="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">إضافة</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/مراكز-التدريب.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_مراكز-التدريب.php';
                        } else {
                            alert('Error: ' + response);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

**مراكز-التدريب.php (backend)**

<?php
require_once '../config/database.php';

// Check if form data has been submitted
if (isset($_POST['name']) && isset($_POST['address']) && isset($_POST['phone']) && isset($_POST['email'])) {
    // Insert data into database
    $query = "INSERT INTO مراكز_التدريب (name, address, phone, email) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssss", $_POST['name'], $_POST['address'], $_POST['phone'], $_POST['email']);
    $stmt->execute();

    // Return success message
    echo 'success';
} else {
    // Return error message
    echo 'Error: Invalid request';
}
?>