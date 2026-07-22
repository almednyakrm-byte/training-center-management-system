**create_دورات.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة دورة جديدة</h2>
        <form id="create-form" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label for="name" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">اسم الدورة</label>
                    <input type="text" id="name" name="name" class="appearance-none block w-full bg-gray-50 text-gray-900 text-sm rounded-lg py-3 px-4 leading-tight focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="اسم الدورة">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label for="description" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">وصف الدورة</label>
                    <textarea id="description" name="description" class="appearance-none block w-full bg-gray-50 text-gray-900 text-sm rounded-lg py-3 px-4 leading-tight focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="وصف الدورة"></textarea>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label for="price" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">سعر الدورة</label>
                    <input type="number" id="price" name="price" class="appearance-none block w-full bg-gray-50 text-gray-900 text-sm rounded-lg py-3 px-4 leading-tight focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="سعر الدورة">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label for="duration" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">مدة الدورة</label>
                    <input type="text" id="duration" name="duration" class="appearance-none block w-full bg-gray-50 text-gray-900 text-sm rounded-lg py-3 px-4 leading-tight focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="مدة الدورة">
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة دورة جديدة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/دورات.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_دورات.php';
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


**دورات.php (backend)**

<?php
// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['duration'])) {
    // Connect to database
    $conn = mysqli_connect('localhost', 'username', 'password', 'database');
    if (!$conn) {
        die('Error: ' . mysqli_connect_error());
    }

    // Insert data into database
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);

    $query = "INSERT INTO دورات (name, description, price, duration) VALUES ('$name', '$description', '$price', '$duration')";
    if (mysqli_query($conn, $query)) {
        echo 'success';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }

    // Close database connection
    mysqli_close($conn);
}
?>