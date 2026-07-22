<!-- register.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen">
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-8 md:p-12 lg:p-16 xl:p-24">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Register</h2>
            <form id="register-form" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-slate-900">Username</label>
                    <input type="text" id="username" name="username" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="Username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                    <div id="username-error" class="text-red-500 hidden"></div>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-900">Email</label>
                    <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="Email" required>
                    <div id="email-error" class="text-red-500 hidden"></div>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-900">Password</label>
                    <input type="password" id="password" name="password" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="Password" pattern="[A-Za-z0-9!@#$%^&*()_+=-{};:'<>,./?]" required>
                    <div id="password-error" class="text-red-500 hidden"></div>
                </div>
                <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-indigo-500 rounded-md hover:bg-indigo-600 focus:ring-indigo-500 focus:border-indigo-500">Register</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#register-form').submit(function(e) {
                e.preventDefault();
                var username = $('#username').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var usernameError = $('#username-error');
                var emailError = $('#email-error');
                var passwordError = $('#password-error');

                if (username.length < 3) {
                    usernameError.text('Username must be at least 3 characters long').show();
                    return false;
                } else {
                    usernameError.hide();
                }

                if (!email.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/)) {
                    emailError.text('Invalid email address').show();
                    return false;
                } else {
                    emailError.hide();
                }

                if (password.length < 8) {
                    passwordError.text('Password must be at least 8 characters long').show();
                    return false;
                } else {
                    passwordError.hide();
                }

                $.ajax({
                    type: 'POST',
                    url: '../backend/auth.php?action=register',
                    data: {
                        username: username,
                        email: email,
                        password: password
                    },
                    success: function(response) {
                        if (response == 'success') {
                            alert('Registration successful!');
                            window.location.href = 'login.php';
                        } else {
                            alert('Registration failed!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>