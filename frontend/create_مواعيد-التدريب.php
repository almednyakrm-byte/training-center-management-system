**create_مواعيد-التدريب.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Sanitize input data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
    $time = filter_var($_POST['time'], FILTER_SANITIZE_STRING);

    // Insert data into database
    $query = "INSERT INTO مواعيد_التدريب (name, description, date, time) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $name, $description, $date, $time);
    $stmt->execute();

    // Redirect back to list page
    header('Location: list_مواعيد-التدريب.php');
    exit;
}

// Include header
require_once '../includes/header.php';

// Include Tailwind CSS
?>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<div class="container mx-auto p-4 mt-12">
    <h1 class="text-3xl font-bold text-emerald-600">إضافة موعد تدريب جديد</h1>

    <form action="" method="post" class="bg-white p-8 rounded-lg shadow-md">
        <div class="mb-4">
            <label for="name" class="block text-sm font-bold text-gray-700">اسم التدريب</label>
            <input type="text" name="name" id="name" class="w-full p-2 border border-gray-300 rounded-md" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-bold text-gray-700">وصف التدريب</label>
            <textarea name="description" id="description" class="w-full p-2 border border-gray-300 rounded-md" required></textarea>
        </div>

        <div class="mb-4">
            <label for="date" class="block text-sm font-bold text-gray-700">تاريخ التدريب</label>
            <input type="date" name="date" id="date" class="w-full p-2 border border-gray-300 rounded-md" required>
        </div>

        <div class="mb-4">
            <label for="time" class="block text-sm font-bold text-gray-700">ساعة التدريب</label>
            <input type="time" name="time" id="time" class="w-full p-2 border border-gray-300 rounded-md" required>
        </div>

        <button type="submit" name="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">حفظ</button>
    </form>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>


**create_مواعيد-التدريب.js**
javascript
$(document).ready(function() {
    // Submit form via AJAX
    $('form').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '../backend/مواعيد-التدريب.php',
            data: formData,
            success: function(response) {
                if (response === 'success') {
                    window.location.href = 'list_مواعيد-التدريب.php';
                } else {
                    alert('Error: ' + response);
                }
            }
        });
    });
});


**مواعيد-التدريب.php (backend)**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Insert data into database
    $name = $_POST['name'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $query = "INSERT INTO مواعيد_التدريب (name, description, date, time) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $name, $description, $date, $time);
    $stmt->execute();

    echo 'success';
}
?>