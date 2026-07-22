**create_طلاب.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-8">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة طالب جديد</h2>
        <form id="create-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="text-slate-900 font-bold">اسم الطالب</label>
                    <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="email" class="text-slate-900 font-bold">بريد إلكتروني</label>
                    <input type="email" id="email" name="email" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
                </div>
            </div>
            <div>
                <label for="phone" class="text-slate-900 font-bold">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            <div>
                <label for="address" class="text-slate-900 font-bold">عنوان الطالب</label>
                <textarea id="address" name="address" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/طلاب.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_طلاب.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/طلاب.php**

<?php
// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to database
    $db = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');
    
    // Prepare SQL query
    $stmt = $db->prepare('INSERT INTO طلاب (name, email, phone, address) VALUES (:name, :email, :phone, :address)');
    
    // Bind form data
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':phone', $_POST['phone']);
    $stmt->bindParam(':address', $_POST['address']);
    
    // Execute query
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error: ' . $stmt->errorInfo()[2];
    }
    
    // Close database connection
    $db = null;
} else {
    echo 'Invalid request';
}
?>