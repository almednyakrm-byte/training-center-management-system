**create_مدرسين.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Check for empty fields
    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO مدرسين (name, email, phone, address) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $phone, $address);
        if ($stmt->execute()) {
            // Redirect to list page
            header('Location: list_مدرسين.php');
            exit;
        } else {
            $error = 'Error inserting data';
        }
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Create Teachers form -->
<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h2 class="text-2xl font-bold text-slate-900">Create Teachers</h2>
    <form id="create-teacher-form" class="space-y-6" method="post">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-slate-300 rounded-md" required>
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-slate-900">Email</label>
            <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-slate-300 rounded-md" required>
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-slate-900">Phone</label>
            <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-slate-300 rounded-md" required>
        </div>
        <div>
            <label for="address" class="block text-sm font-medium text-slate-900">Address</label>
            <input type="text" id="address" name="address" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-slate-300 rounded-md" required>
        </div>
        <button type="submit" name="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create Teacher</button>
    </form>
    <?php if (isset($error)) : ?>
        <p class="text-red-500"><?= $error ?></p>
    <?php endif; ?>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    // AJAX form submission
    document.getElementById('create-teacher-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('../backend/مدرسين.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_مدرسين.php';
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>


**backend/مدرسين.php**

<?php
// Include database connection
require_once '../config/database.php';

// Check if form data has been sent
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['address'])) {
    // Insert data into database
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    $sql = "INSERT INTO مدرسين (name, email, phone, address) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $phone, $address);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Error inserting data']);
    }
} else {
    echo json_encode(['error' => 'Invalid form data']);
}