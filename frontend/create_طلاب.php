**create_طلاب.php**

<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit;
}

require_once '../config.php';
require_once '../functions.php';

$mod_slug = 'طلاب';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if (empty($name)) {
        $errors[] = 'Name is required';
    }

    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email';
    }

    if (empty($phone)) {
        $errors[] = 'Phone is required';
    }

    if (empty($address)) {
        $errors[] = 'Address is required';
    }

    if (empty($errors)) {
        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
        ];

        $result = insertRecord($mod_slug, $data);

        if ($result) {
            $message = 'Record created successfully';
            $type = 'success';
        } else {
            $message = 'Error creating record';
            $type = 'error';
        }
    } else {
        $message = implode('<br>', $errors);
        $type = 'error';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create <?php echo $mod_slug; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded shadow-md">
        <h1 class="text-lg font-bold mb-4 text-emerald-600">Create <?php echo $mod_slug; ?></h1>
        <form id="create-form" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3 mb-6 md:mb-0">
                    <label for="name" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Name</label>
                    <input id="name" type="text" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="Name" required>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3 mb-6 md:mb-0">
                    <label for="email" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Email</label>
                    <input id="email" type="email" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="Email" required>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3 mb-6 md:mb-0">
                    <label for="phone" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Phone</label>
                    <input id="phone" type="tel" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="Phone" required>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3 mb-6 md:mb-0">
                    <label for="address" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Address</label>
                    <textarea id="address" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="Address" required></textarea>
                </div>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Create</button>
        </form>
        <?php if (isset($message)) : ?>
            <div class="bg-<?php echo $type === 'success' ? 'green-100' : 'red-100'; ?> p-4 mt-4 rounded">
                <p class="text-<?php echo $type === 'success' ? 'green-600' : 'red-600'; ?>"><?php echo $message; ?></p>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/<?php echo $mod_slug; ?>.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = '../list_<?php echo $mod_slug; ?>.php';
                        } else {
                            alert(response);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

This code creates a premium Tailwind UI form with all necessary fields based on common attributes for the 'طلاب' module. It includes session validation and uses AJAX to POST the form data to the backend script. On success, it redirects back to the list page.