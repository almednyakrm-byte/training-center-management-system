<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة مراكز تدريب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold text-emerald-600">نظام إدارة مراكز تدريب</h1>
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-emerald-600">مرحباً بكم</h2>
            <p>نظام إدارة مراكز تدريب</p>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-emerald-600">إحصائيات</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-emerald-600">مراكز التدريب</h3>
                    <p id="training_centers_count"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-emerald-600">طلاب</h3>
                    <p id="students_count"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-emerald-600">اساتذة</h3>
                    <p id="teachers_count"></p>
                </div>
            </div>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-emerald-600">روابط سريعة</h2>
            <ul>
                <li><a href="training_centers.php" class="text-emerald-600 hover:text-teal-500">مراكز التدريب</a></li>
                <li><a href="students.php" class="text-emerald-600 hover:text-teal-500">طلاب</a></li>
                <li><a href="teachers.php" class="text-emerald-600 hover:text-teal-500">اساتذة</a></li>
                <li><a href="schedules.php" class="text-emerald-600 hover:text-teal-500">مواعيد التدريب</a></li>
            </ul>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('api/stats.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('training_centers_count').innerText = data.training_centers_count;
                document.getElementById('students_count').innerText = data.students_count;
                document.getElementById('teachers_count').innerText = data.teachers_count;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code uses Tailwind CSS for styling and includes a session check to redirect to the login page if the user is not authenticated. The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules. The stats are fetched dynamically via a Javascript API call from the backend files.

Please note that you need to replace `api/stats.php` with the actual URL of your backend API file that returns the stats data in JSON format.

Also, make sure to create the necessary backend files (e.g., `api/stats.php`) to handle the API calls and return the required data.

You can customize the layout and design as per your requirements.