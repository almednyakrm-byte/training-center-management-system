**edit_مراكز-التدريب.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/مراكز-التدريب.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (isset($data['error'])) {
    echo '<div class="alert alert-danger">' . $data['error'] . '</div>';
} else {
    // Populate form fields
    $name = $data['name'];
    $address = $data['address'];
    $phone = $data['phone'];
    $email = $data['email'];
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مراكز التدريب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .emerald-600 {
            color: #008751;
        }
        .teal-500 {
            color: #0097a7;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">تعديل مراكز التدريب</h1>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم المركز</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $name ?>">
            </div>
            <div class="mb-4">
                <label for="address" class="block text-gray-700 text-sm font-bold mb-2">عنوان المركز</label>
                <input type="text" id="address" name="address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $address ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">رقم الهاتف</label>
                <input type="text" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $phone ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $email ?>">
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">تعديل</button>
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
                    url: '../backend/مراكز-التدريب.php',
                    data: formData,
                    success: function(data) {
                        if (data.success) {
                            window.location.href = 'list_مراكز-التدريب.php';
                        } else {
                            alert(data.error);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/مراكز-التدريب.php**

<?php
// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details from database
$query = "SELECT * FROM مراكز_التدريب WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Check if data exists
if (isset($data['error'])) {
    echo json_encode(array('error' => $data['error']));
} else {
    // Return data as JSON
    echo json_encode($data);
}
?>


Note: This code assumes that you have a database connection established in the `backend/مراكز-التدريب.php` file. You should replace the `mysqli_query` and `mysqli_fetch_assoc` functions with your own database interaction code. Additionally, you should ensure that the `مراكز_التدريب` table exists in your database and that the `id` column is indexed for efficient querying.