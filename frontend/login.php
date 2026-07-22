<?php
// login.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #1a1d23, #2c2f36);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
        }
        
        .glassmorphic {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
        
        .glassmorphic::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
            backdrop-filter: blur(20px);
            border-radius: 10px;
            z-index: -1;
        }
        
        .gradient {
            background: linear-gradient(90deg, #1a1d23, #2c2f36);
            padding: 10px;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-gray-900">
    <div class="max-w-md mx-auto p-4 glassmorphic">
        <div class="text-center mb-4">
            <h1 class="text-3xl font-bold text-slate-900">Login</h1>
        </div>
        <form id="login-form">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-slate-900">Username</label>
                <input type="text" id="username" name="username" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                <div id="username-error" class="text-sm text-red-500 mt-1"></div>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-slate-900">Password</label>
                <input type="password" id="password" name="password" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Password" required>
                <div id="password-error" class="text-sm text-red-500 mt-1"></div>
            </div>
            <button type="submit" class="w-full p-2 mt-4 text-sm text-white bg-indigo-500 hover:bg-indigo-700 rounded-lg">Login</button>
            <p class="text-sm text-gray-500 mt-2">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a></p>
        </form>
    </div>

    <script>
        const form = document.getElementById('login-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });
                const data = await response.json();
                if (data.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    document.getElementById('username-error').innerHTML = data.username_error ? data.username_error : '';
                    document.getElementById('password-error').innerHTML = data.password_error ? data.password_error : '';
                }
            } catch (error) {
                console.error(error);
                alert('Error logging in. Please try again.');
            }
        });
    </script>
</body>
</html>