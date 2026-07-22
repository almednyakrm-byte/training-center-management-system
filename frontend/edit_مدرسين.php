**edit_مدرسين.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$record = json_decode(file_get_contents('../backend/مدرسين.php?id=' . $id), true);

// Check if record exists
if (empty($record)) {
    echo 'Record not found';
    exit;
}

// Set page title and mod slug
$page_title = 'تعديل مدرس';
$mod_slug = 'مدرسين';

// Include header
include 'header.php';

?>

<!-- Main content -->
<main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold leading-tight text-slate-900 mb-4"><?= $page_title ?></h1>

    <!-- Form -->
    <form id="edit-form" class="bg-white rounded-lg shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">اسم المدرس</label>
            <input type="text" id="name" name="name" class="block w-full p-2 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $record['name'] ?>">
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="email" class="block text-sm font-medium text-slate-900">البريد الإلكتروني</label>
            <input type="email" id="email" name="email" class="block w-full p-2 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $record['email'] ?>">
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="phone" class="block text-sm font-medium text-slate-900">رقم الهاتف</label>
            <input type="tel" id="phone" name="phone" class="block w-full p-2 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $record['phone'] ?>">
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">تعديل</button>
    </form>
</main>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<script>
    // Fetch existing record details via GET
    fetch('../backend/مدرسين.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
            document.getElementById('phone').value = data.phone;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX PUT request
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();
        const formData = new FormData(event.target);
        fetch('../backend/مدرسين.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>

<!-- Footer -->
<?php include 'footer.php'; ?>


**header.php**

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <!-- Header -->
    <header class="bg-slate-900 py-4">
        <nav class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold text-white">لوحة التحكم</a>
            <ul class="flex items-center space-x-4">
                <li><a href="profile.php" class="text-sm text-gray-300 hover:text-white">الملف الشخصي</a></li>
                <li><a href="logout.php" class="text-sm text-gray-300 hover:text-white">تسجيل الخروج</a></li>
            </ul>
        </nav>
    </header>


**footer.php**

<!-- Footer -->
<footer class="bg-slate-900 py-4">
    <div class="container mx-auto text-center text-sm text-gray-300">
        &copy; <?= date('Y') ?> جميع الحقوق محفوظة
    </div>
</footer>


Note: This code assumes that you have a `backend/مدرسين.php` file that handles the GET and PUT requests for the 'مدرسين' table. The `backend/مدرسين.php` file should return the existing record details in JSON format when accessed via GET, and update the record when accessed via PUT.