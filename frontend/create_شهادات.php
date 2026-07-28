**create_شهادات.php**

<?php
// Session validation
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'nav.php';

// Include form validation and processing
require_once 'form_validation.php';

// Form fields
$mod_slug = 'شهادات';
$fields = [
    [
        'label' => 'Title',
        'name' => 'title',
        'type' => 'text',
        'placeholder' => 'Enter title',
        'required' => true,
    ],
    [
        'label' => 'Description',
        'name' => 'description',
        'type' => 'textarea',
        'placeholder' => 'Enter description',
        'required' => true,
    ],
    [
        'label' => 'Date',
        'name' => 'date',
        'type' => 'date',
        'required' => true,
    ],
];

// Form validation and processing
if (isset($_POST['submit'])) {
    $errors = validateForm($fields);
    if (empty($errors)) {
        // Process form data
        $data = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'date' => $_POST['date'],
        ];
        // AJAX request to create new record
        $ajaxData = json_encode($data);
        $ajaxUrl = '../backend/شهادات.php';
        $ajaxMethod = 'POST';
        $ajaxContentType = 'application/json';
        $ajaxResponse = ajaxRequest($ajaxUrl, $ajaxMethod, $ajaxContentType, $ajaxData);
        if ($ajaxResponse['success']) {
            header('Location: list_' . $mod_slug . '.php');
            exit;
        } else {
            $errors[] = 'Error creating new record';
        }
    }
}

// Include form
require_once 'form.php';
?>

<script>
    function ajaxRequest(url, method, contentType, data) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open(method, url, true);
            xhr.setRequestHeader('Content-Type', contentType);
            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    resolve(JSON.parse(xhr.responseText));
                } else {
                    reject(xhr.statusText);
                }
            };
            xhr.onerror = function() {
                reject(xhr.statusText);
            };
            xhr.send(data);
        });
    }
</script>

<?php
// Include footer
require_once 'footer.php';
?>


**form.php**

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Create New <?= $mod_slug ?></h2>
    <form id="create-form" method="post">
        <?php foreach ($fields as $field) : ?>
            <div class="mb-4">
                <label for="<?= $field['name'] ?>" class="block text-sm font-medium text-slate-900"><?= $field['label'] ?></label>
                <input type="<?= $field['type'] ?>" id="<?= $field['name'] ?>" name="<?= $field['name'] ?>" class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="<?= $field['placeholder'] ?>" required>
            </div>
        <?php endforeach; ?>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
</div>

<script>
    document.getElementById('create-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        const ajaxData = JSON.stringify(Object.fromEntries(formData.entries()));
        const ajaxUrl = '../backend/شهادات.php';
        const ajaxMethod = 'POST';
        const ajaxContentType = 'application/json';
        ajaxRequest(ajaxUrl, ajaxMethod, ajaxContentType, ajaxData).then((response) => {
            if (response.success) {
                window.location.href = 'list_' + <?= $mod_slug ?> + '.php';
            } else {
                console.error(response.error);
            }
        }).catch((error) => {
            console.error(error);
        });
    });
</script>


**form_validation.php**

function validateForm($fields) {
    $errors = [];
    foreach ($fields as $field) {
        if (empty($_POST[$field['name']])) {
            $errors[] = $field['label'] . ' is required';
        }
    }
    return $errors;
}


**header.php, nav.php, footer.php** (assuming these files exist and are properly configured)

Note: This code assumes that the `ajaxRequest` function is defined in the `create_شهادات.php` file. If you want to move it to a separate file, you can do so by creating a new file, e.g., `ajax.js`, and including it in the `create_شهادات.php` file.