**edit_اساتذة.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/اساتذة.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

// Set page title
$pageTitle = 'Edit ' . $existingRecord['name'];

// Include header
include 'header.php';

?>

<div class="container mx-auto p-4 mt-12">
    <h1 class="text-3xl font-bold text-emerald-600"><?= $pageTitle ?></h1>
    <form id="edit-form" class="max-w-md mx-auto mt-8 bg-white p-8 rounded-lg shadow-md">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600" value="<?= $existingRecord['name'] ?>">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600" value="<?= $existingRecord['email'] ?>">
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
            <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600" value="<?= $existingRecord['phone'] ?>">
        </div>
        <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Update</button>
    </form>
</div>

<script>
    // Fetch existing record details via GET
    fetch('../backend/اساتذة.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
            document.getElementById('phone').value = data.phone;
        })
        .catch(error => console.error('Error:', error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', (e) => {
        e.preventDefault();

        // Get form data
        const formData = new FormData(document.getElementById('edit-form'));

        // Send AJAX PUT request
        fetch('../backend/اساتذة.php', {
            method: 'PUT',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                // Redirect to list page
                window.location.href = 'list_اساتذة.php';
            })
            .catch(error => console.error('Error:', error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/اساتذة.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    echo 'Invalid request';
    exit;
}

// Connect to database
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Check connection
if (!$conn) {
    echo 'Connection failed';
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Query to fetch existing record details
$query = "SELECT * FROM teachers WHERE id = '$id'";

// Execute query
$result = mysqli_query($conn, $query);

// Check if record exists
if (mysqli_num_rows($result) > 0) {
    // Fetch record details
    $record = mysqli_fetch_assoc($result);

    // Return record details as JSON
    echo json_encode($record);
} else {
    echo 'Record not found';
}

// Close connection
mysqli_close($conn);
?>


**backend/اساتذة.php (PUT request handler)**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    echo 'Invalid request';
    exit;
}

// Connect to database
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Check connection
if (!$conn) {
    echo 'Connection failed';
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

// Query to update existing record
$query = "UPDATE teachers SET name = '$name', email = '$email', phone = '$phone' WHERE id = '$id'";

// Execute query
mysqli_query($conn, $query);

// Close connection
mysqli_close($conn);

// Redirect to list page
header('Location: list_اساتذة.php');
exit;
?>