**list_طلاب.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلاب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #ffffff;
        }
        .header a {
            color: #ffffff;
        }
        .header a:hover {
            color: #ffffff;
            text-decoration: none;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4">
        <div class="header flex justify-between items-center py-4">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                <a href="logout.php" class="ml-4 text-lg font-bold">Logout</a>
            </div>
        </div>
        <div class="flex justify-between items-center py-4">
            <h1 class="text-3xl font-bold">طلاب</h1>
            <a href="create_طلاب.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Add New Item</a>
        </div>
        <div class="flex justify-between items-center py-4">
            <input type="search" id="search" class="w-full p-2 text-lg font-bold" placeholder="Search...">
            <button id="search-btn" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Search</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch API to load records
        fetch('../backend/طلاب.php')
            .then(response => response.json())
            .then(data => {
                const records = document.getElementById('records');
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.id}</td>
                        <td>${record.name}</td>
                        <td>${record.email}</td>
                        <td>
                            <a href="edit_طلاب.php?id=${record.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">Delete</button>
                        </td>
                    `;
                    records.appendChild(row);
                });
            })
            .catch(error => console.error(error));

        // Search functionality
        const searchInput = document.getElementById('search');
        const searchBtn = document.getElementById('search-btn');
        searchBtn.addEventListener('click', () => {
            const searchValue = searchInput.value.trim();
            if (searchValue) {
                fetch('../backend/طلاب.php?search=' + searchValue)
                    .then(response => response.json())
                    .then(data => {
                        const records = document.getElementById('records');
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.id}</td>
                                <td>${record.name}</td>
                                <td>${record.email}</td>
                                <td>
                                    <a href="edit_طلاب.php?id=${record.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                                    <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">Delete</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            } else {
                fetch('../backend/طلاب.php')
                    .then(response => response.json())
                    .then(data => {
                        const records = document.getElementById('records');
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.id}</td>
                                <td>${record.name}</td>
                                <td>${record.email}</td>
                                <td>
                                    <a href="edit_طلاب.php?id=${record.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                                    <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">Delete</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            }
        });

        // Delete record functionality
        function deleteRecord(id) {
            if (confirm('Are you sure you want to delete this record?')) {
                fetch('../backend/طلاب.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Record deleted successfully!');
                        window.location.reload();
                    } else {
                        alert('Error deleting record!');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

This code creates a premium Tailwind UI layout with a header navigation, table displaying list of records, and search bar. It also includes AJAX functionality to fetch records from the backend and delete records. The delete functionality uses a confirm dialog to ensure the user wants to delete the record.