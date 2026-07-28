**list_اساتذة.php**

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
    <title>اساتذة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .emerald-600 {
            color: #008E77;
        }
        .teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Back to Index</a>
            <div class="flex items-center">
                <p class="mr-2">Logged in as: <?= $_SESSION['username'] ?></p>
                <a href="logout.php" class="text-red-500 hover:text-red-700">Logout</a>
            </div>
        </nav>
    </header>
    <main class="max-w-7xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">اساتذة</h1>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_اساتذة.php'">Add New Item</button>
        <div class="flex justify-between mb-4">
            <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-600" placeholder="Search...">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">Search</button>
        </div>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">Name</th>
                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $url = '../backend/اساتذة.php';
                $response = fetchRecords($url);
                $records = json_decode($response, true);
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2"><?= $record['name'] ?></td>
                        <td class="border border-gray-300 px-4 py-2">
                            <a href="edit_اساتذة.php?id=<?= $record['id'] ?>" class="text-emerald-600 hover:text-emerald-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(<?= $record['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/axios@0.21.1/dist/axios.min.js"></script>
    <script>
        function searchRecords() {
            const search = document.getElementById('search').value;
            const url = '../backend/اساتذة.php?search=' + search;
            fetchRecords(url);
        }

        function fetchRecords(url) {
            axios.get(url)
                .then(response => {
                    const records = response.data;
                    const tbody = document.getElementById('records');
                    tbody.innerHTML = '';
                    records.forEach(record => {
                        const tr = document.createElement('tr');
                        const td1 = document.createElement('td');
                        td1.textContent = record.name;
                        tr.appendChild(td1);
                        const td2 = document.createElement('td');
                        const a = document.createElement('a');
                        a.href = 'edit_اساتذة.php?id=' + record.id;
                        a.textContent = 'Edit';
                        a.classList.add('text-emerald-600', 'hover:text-emerald-700');
                        td2.appendChild(a);
                        const button = document.createElement('button');
                        button.textContent = 'Delete';
                        button.classList.add('text-red-500', 'hover:text-red-700');
                        button.onclick = () => deleteRecord(record.id);
                        td2.appendChild(button);
                        tr.appendChild(td2);
                        tbody.appendChild(tr);
                    });
                })
                .catch(error => console.error(error));
        }

        function deleteRecord(id) {
            if (confirm('Are you sure you want to delete this record?')) {
                axios.delete('../backend/اساتذة.php?id=' + id)
                    .then(response => {
                        fetchRecords('../backend/اساتذة.php');
                    })
                    .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

**backend/اساتذة.php**

<?php
// Fetch records from database
$records = array(
    array('id' => 1, 'name' => 'John Doe'),
    array('id' => 2, 'name' => 'Jane Doe'),
    array('id' => 3, 'name' => 'Bob Smith'),
);

// Search functionality
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $records = array_filter($records, function ($record) use ($search) {
        return strpos($record['name'], $search) !== false;
    });
}

// Output records in JSON format
header('Content-Type: application/json');
echo json_encode($records);
?>

Note: This code assumes that you have a backend script (`backend/اساتذة.php`) that fetches records from a database and outputs them in JSON format. The frontend script (`list_اساتذة.php`) uses the Fetch API to fetch records from the backend script and displays them in a table. The search functionality is implemented using a simple `strpos` check in the backend script.