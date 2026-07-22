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
    <title>نظام إدارة مركز تدريب مع إدارة دورات وشهادات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
        }
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="flex justify-between items-center p-4 bg-slate-900">
        <h1 class="text-3xl text-white">نظام إدارة مركز تدريب مع إدارة دورات وشهادات</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل الخروج</button>
    </div>
    <div class="flex justify-center items-center p-4 bg-slate-900">
        <div class="glassmorphism-card w-1/2 p-4">
            <h2 class="text-2xl text-white">مرحباً بكم</h2>
            <p class="text-gray-300">هذا هو مركز التدريب الخاص بنا</p>
        </div>
    </div>
    <div class="flex justify-center items-center p-4 bg-slate-900">
        <div class="glassmorphism-card w-1/2 p-4">
            <h2 class="text-2xl text-white">إحصائيات المركز</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg text-gray-700">عدد الدورات</h3>
                    <p id="course-count" class="text-3xl text-gray-900"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg text-gray-700">عدد الطلاب</h3>
                    <p id="student-count" class="text-3xl text-gray-900"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg text-gray-700">عدد المدرسين</h3>
                    <p id="teacher-count" class="text-3xl text-gray-900"></p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-center items-center p-4 bg-slate-900">
        <div class="glassmorphism-card w-1/2 p-4">
            <h2 class="text-2xl text-white">روابط سريعة</h2>
            <div class="flex justify-between items-center gap-4">
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='courses.php'">دورات</button>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='students.php'">طلاب</button>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='teachers.php'">مدرسين</button>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='certificates.php'">شهادات</button>
            </div>
        </div>
    </div>

    <script>
        fetch('api/stats.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('course-count').innerText = data.course_count;
                document.getElementById('student-count').innerText = data.student_count;
                document.getElementById('teacher-count').innerText = data.teacher_count;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code uses Tailwind CSS for styling and makes API calls to fetch stats dynamically. The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules. The stats are fetched from the `api/stats.php` file, which should return a JSON response with the stats data.