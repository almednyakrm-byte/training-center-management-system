**edit_مواعيد-التدريب.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/مواعيد-التدريب.php?id=' . $id;
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);

// Check if record exists
if (empty($data)) {
    echo 'Error: Record not found';
    exit;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مواعيد التدريب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-emerald-600">تعديل مواعيد التدريب</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">اسم التدريب</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 border border-gray-300 rounded-md" value="<?= $data['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700">تاريخ التدريب</label>
                <input type="date" id="date" name="date" class="block w-full p-2 mt-1 border border-gray-300 rounded-md" value="<?= $data['date'] ?>">
            </div>
            <div class="mb-4">
                <label for="time" class="block text-sm font-medium text-gray-700">وقت التدريب</label>
                <input type="time" id="time" name="time" class="block w-full p-2 mt-1 border border-gray-300 rounded-md" value="<?= $data['time'] ?>">
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">حفظ</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مواعيد-التدريب.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error: ' + response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**../backend/مواعيد-التدريب.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID not set']);
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details
$query = "SELECT * FROM مواعيد_التدريب WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Check if record exists
if (empty($data)) {
    echo json_encode(['error' => 'Record not found']);
    exit;
}

// Return record details as JSON
echo json_encode($data);

Note: This code assumes you have a MySQL database connection established in the `../backend/مواعيد-التدريب.php` file. You should replace the `mysqli_query` and `mysqli_fetch_assoc` functions with your actual database connection and query methods.