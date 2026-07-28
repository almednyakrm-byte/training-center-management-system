**list_مراكز-التدريب.php**

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
    <title>مراكز التدريب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
        }
        .emerald-600 {
            color: #008000;
        }
        .teal-500 {
            color: #0097a7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="document.location='logout.php'">تسجيل الخروج</button>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4 mt-4">
        <h1 class="text-3xl font-bold mb-4">مراكز التدريب</h1>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="document.location='create_مراكز-التدريب.php'">إضافة جديد</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" id="search" class="w-full py-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600" placeholder="بحث...">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 px-4 py-2">اسم المركز</th>
                    <th class="border border-gray-400 px-4 py-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to load records
        async function loadRecords() {
            try {
                const response = await fetch('../backend/مراكز-التدريب.php', { method: 'GET' });
                const data = await response.json();
                const records = document.getElementById('records');
                records.innerHTML = '';
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border border-gray-400 px-4 py-2">${record.name}</td>
                        <td class="border border-gray-400 px-4 py-2">
                            <a href="edit_مراكز-التدريب.php?id=${record.id}" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    records.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        // Search function
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery !== '') {
                // Fetch API to load filtered records
                async function loadFilteredRecords() {
                    try {
                        const response = await fetch('../backend/مراكز-التدريب.php', { method: 'GET', params: { search: searchQuery } });
                        const data = await response.json();
                        const records = document.getElementById('records');
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="border border-gray-400 px-4 py-2">${record.name}</td>
                                <td class="border border-gray-400 px-4 py-2">
                                    <a href="edit_مراكز-التدريب.php?id=${record.id}" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    } catch (error) {
                        console.error(error);
                    }
                }
                loadFilteredRecords();
            } else {
                loadRecords();
            }
        }

        // Delete record function
        async function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                try {
                    const response = await fetch('../backend/مراكز-التدريب.php', { method: 'DELETE', params: { id } });
                    if (response.ok) {
                        loadRecords();
                    } else {
                        console.error('Error deleting record');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }

        // Load records on page load
        loadRecords();
    </script>
</body>
</html>

This code includes:

1. Session validation to ensure the user is authenticated before accessing the page.
2. A premium Tailwind UI layout with a custom color palette.
3. A header navigation bar with links to the index page, current user info, and logout.
4. A table displaying a list of records with actions (edit and delete).
5. An "Add New Item" button linking to the create_مراكز-التدريب.php page.
6. A search bar filtering elements in real-time.
7. AJAX JavaScript code using the Fetch API to load records from the backend and delete records.

Note: This code assumes that the backend API is implemented and returns JSON data in the format expected by the JavaScript code.