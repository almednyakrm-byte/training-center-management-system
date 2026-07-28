<?php
// login.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #0f0f0f, #0f0f0f);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
        }
        
        .glassmorphic {
            background: linear-gradient(90deg, #0f0f0f, #0f0f0f);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .glassmorphic::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #0f0f0f, #0f0f0f);
            mix-blend-mode: multiply;
            filter: blur(10px);
        }
    </style>
</head>
<body class="bg-gray-900 h-screen flex justify-center items-center">
    <div class="glassmorphic bg-gradient-to-br from-teal-500 to-emerald-600 p-10 rounded-lg shadow-lg w-96">
        <h2 class="text-3xl font-bold text-white mb-5">Login</h2>
        <form id="login-form" class="space-y-4">
            <div>
                <label for="username" class="text-white">Username</label>
                <input type="text" id="username" name="username" class="w-full p-2 rounded-lg border border-gray-600 focus:outline-none focus:ring focus:ring-emerald-600" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
            </div>
            <div>
                <label for="password" class="text-white">Password</label>
                <input type="password" id="password" name="password" class="w-full p-2 rounded-lg border border-gray-600 focus:outline-none focus:ring focus:ring-emerald-600" required>
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg">Login</button>
        </form>
        <p class="text-white text-center mt-5">Don't have an account? <a href="register.php" class="text-emerald-600 hover:text-emerald-400">Register</a></p>
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
                    alert('Login successful!');
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error(error);
                alert('Error logging in. Please try again.');
            }
        });
    </script>
</body>
</html>


This code uses Tailwind CSS to create a premium-looking login page with a glassmorphic layout, gradients, and a form for username and password input. The form uses standard HTML input pattern validators to support Arabic and Latin characters. The AJAX JavaScript code uses the Fetch API to submit the credentials to the backend PHP script and handle the response or error alerts dynamically. The code also includes a direct link to the register.php page.