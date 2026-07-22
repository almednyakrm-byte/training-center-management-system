**edit_شهادات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = json_decode(file_get_contents('../backend/شهادات.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit شهادات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-slate-900 mb-4">Edit شهادات</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-slate-900">Title</label>
                <input type="text" id="title" name="title" class="block w-full p-2 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['title'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"><?= $existingRecord['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/شهادات.php',
                    data: formData,
                    success: function(response) {
                        window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/شهادات.php**

<?php
// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = array(
    'id' => $id,
    'title' => 'Existing Title',
    'description' => 'Existing Description'
);

// Output as JSON
echo json_encode($existingRecord);