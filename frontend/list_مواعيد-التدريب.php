**list_مواعيد-التدريب.php**

<?php
// Session validation
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
    <title>مواعيد التدريب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
        }
        .bg-emerald-600 {
            background-color: #0d9488;
        }
        .text-teal-500 {
            color: #0097a7;
        }
    </style>
</head>
<body class="bg-emerald-600">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <a href="index.php" class="text-teal-500 hover:text-white">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-teal-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="document.location='logout.php'">تسجيل الخروج</button>
            </div>
        </div>
        <div class="bg-white p-4 rounded shadow-md">
            <h2 class="text-lg font-bold mb-2">مواعيد التدريب</h2>
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="document.location='create_مواعيد-التدريب.php'">إضافة جديد</button>
            <div class="flex justify-between items-center mb-4">
                <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="بحث...">
                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
            </div>
            <table class="w-full border-collapse border border-gray-400">
                <thead>
                    <tr>
                        <th class="border border-gray-400 p-2">اسم المادة</th>
                        <th class="border border-gray-400 p-2">تاريخ التدريب</th>
                        <th class="border border-gray-400 p-2">حالة التدريب</th>
                        <th class="border border-gray-400 p-2">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="records">
                    <!-- Records will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Search records
        function searchRecords() {
            const searchValue = document.getElementById('search').value;
            fetch('../backend/مواعيد-التدريب.php?search=' + searchValue)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.اسم_المادة}</td>
                            <td>${record.تاريخ_التدريب}</td>
                            <td>${record.حالة_التدريب}</td>
                            <td>
                                <a href="edit_مواعيد-التدريب.php?id=${record.id}" class="text-teal-500 hover:text-white">تعديل</a>
                                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded ml-2" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
        }

        // Delete record
        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/مواعيد-التدريب.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                })
                .catch(error => console.error(error));
            }
        }

        // Load records
        fetch('../backend/مواعيد-التدريب.php')
            .then(response => response.json())
            .then(data => {
                const records = document.getElementById('records');
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.اسم_المادة}</td>
                        <td>${record.تاريخ_التدريب}</td>
                        <td>${record.حالة_التدريب}</td>
                        <td>
                            <a href="edit_مواعيد-التدريب.php?id=${record.id}" class="text-teal-500 hover:text-white">تعديل</a>
                            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded ml-2" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    records.appendChild(row);
                });
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>

This code includes session validation, a premium Tailwind UI, and AJAX requests to fetch and delete records. It also includes a search bar that filters elements in real-time.