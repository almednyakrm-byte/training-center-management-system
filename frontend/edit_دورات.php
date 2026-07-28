**edit_دورات.php**

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

// Fetch existing record details via AJAX
$js = "
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: '../backend/دورات.php?id=" . $id . "',
            dataType: 'json',
            success: function(data) {
                $('#name').val(data.name);
                $('#description').val(data.description);
                $('#price').val(data.price);
            }
        });
    });
";

// Include JavaScript code
echo "<script>$js</script>";

// Include Tailwind CSS styles
echo "<link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>";

// Form
echo "
    <div class='max-w-md mx-auto p-4 bg-white rounded-lg shadow-md'>
        <h2 class='text-lg font-bold text-slate-900 mb-4'>Edit Course</h2>
        <form id='edit-course-form' class='space-y-4'>
            <div>
                <label for='name' class='block text-sm font-medium text-slate-900'>Name</label>
                <input type='text' id='name' name='name' class='block w-full p-2 pl-10 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500'>
            </div>
            <div>
                <label for='description' class='block text-sm font-medium text-slate-900'>Description</label>
                <textarea id='description' name='description' class='block w-full p-2 pl-10 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500'></textarea>
            </div>
            <div>
                <label for='price' class='block text-sm font-medium text-slate-900'>Price</label>
                <input type='number' id='price' name='price' class='block w-full p-2 pl-10 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500'>
            </div>
            <button type='submit' class='w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-indigo-500 rounded-lg hover:bg-indigo-700 focus:ring-indigo-500 focus:border-indigo-500'>Save Changes</button>
        </form>
    </div>
";

// JavaScript code for form submission
$js = "
    $(document).ready(function() {
        $('#edit-course-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'PUT',
                url: '../backend/دورات.php',
                data: $(this).serialize() + '&id=" . $id . "',
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        window.location.href = 'list_دورات.php';
                    } else {
                        alert('Error: ' + data.message);
                    }
                }
            });
        });
    });
";

// Include JavaScript code
echo "<script>$js</script>";
?>


**backend/دورات.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Get id
$id = $_GET['id'];

// Check if id is numeric
if (!is_numeric($id)) {
    http_response_code(400);
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get existing record details
$stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch existing record details
$row = $result->fetch_assoc();

// Close connection
$conn->close();

// Output existing record details as JSON
echo json_encode($row);
?>


**backend/edit_course.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Get id
$id = $_GET['id'];

// Check if id is numeric
if (!is_numeric($id)) {
    http_response_code(400);
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get updated record details
$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];

// Update record
$stmt = $conn->prepare("UPDATE courses SET name = ?, description = ?, price = ? WHERE id = ?");
$stmt->bind_param("ssdi", $name, $description, $price, $id);
$stmt->execute();

// Close connection
$conn->close();

// Output success message as JSON
echo json_encode(array('success' => true));
?>