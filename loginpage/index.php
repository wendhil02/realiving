<?php
session_start();
include '../connection/connection.php';

// Prevent page caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$error_message = "";

// Redirect to main page if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: ../admin/admin_mainpage/mainpage.php");
    exit();
}

// Auto-login with remember_token from cookie
if (!isset($_SESSION['admin_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $stmt = $conn->prepare("SELECT id, email, role, remember_token FROM account WHERE remember_token IS NOT NULL");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            if (password_verify($token, $row['remember_token'])) {
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_email'] = $row['email'];
                $_SESSION['admin_role'] = $row['role'];

                header("Location: ../admin/admin_mainpage/mainpage.php");
                exit();
            }
        }
    }
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if 'email' and 'password' exist in the POST array
    $email = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';
    $password_input = isset($_POST['password']) ? $_POST['password'] : '';
    $remember_me = isset($_POST['remember']);

    if (!empty($email) && !empty($password_input)) {
        $stmt = $conn->prepare("SELECT id, email, password, role FROM account WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $row = $result->fetch_assoc();

            if (password_verify($password_input, $row['password'])) {
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_email'] = $row['email'];
                $_SESSION['admin_role'] = $row['role'];

                if ($remember_me) {
                    $token = bin2hex(random_bytes(32));
                    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

                    $update_stmt = $conn->prepare("UPDATE account SET remember_token = ? WHERE id = ?");
                    $update_stmt->bind_param("si", $hashed_token, $row['id']);
                    $update_stmt->execute();
                    $update_stmt->close();

                    setcookie("remember_token", $token, time() + (30 * 24 * 60 * 60), "/", "", false, true);
                }

                header("Location: ../admin/admin_mainpage/mainpage.php");
                exit();
            } else {
                $error_message = "Incorrect password. Please try again.";
            }
        } else {
            $error_message = "No account found with that email.";
        }

        $stmt->close();
    } else {
        $error_message = "Please fill in all fields.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#4f46e5",
                        secondary: "#6366f1"
                    },
                    borderRadius: {
                        button: "8px",
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    }
                },
            },
        };

        // Show the loading spinner and hide the form, then submit after 4 seconds
        function showLoading(event) {
            event.preventDefault(); // Prevent the form from being submitted immediately
            document.getElementById("loading-button").classList.remove("hidden");
            document.getElementById("login-form").classList.add("hidden");

            // Wait for 4 seconds and then submit the form
            setTimeout(function() {
                document.getElementById("login-form").submit();
            }, 3000); // 3 seconds delay
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="h-screen w-full flex items-center justify-center bg-cover bg-center relative" style="background-image: url('../../images/background-image2.png');">

    <!-- Left half: Background image + dark overlay -->
    <div class="absolute left-0 top-0 w-1/2 h-full bg-cover bg-center z-0" style="background-image: url('../logo/bg.png');"></div>
    <div class="absolute left-0 top-0 w-1/2 h-full bg-black bg-opacity-60  z-10"></div>

    <!-- Left half: Logos (vertical center) -->
    <div class="absolute left-0 top-0 w-1/2 h-full flex flex-col items-center justify-center space-y-8 z-20">
        <img src="../logo/mmone.png" alt="Logo 1" class="h-24 object-contain drop-shadow-xl" />
        <img src="../logo/noblebg.png" alt="Logo 2" class="h-24 object-contain drop-shadow-xl" />
    </div>

    <!-- Right half: Login Form -->
    <div class="relative z-30 w-full max-w-sm ml-[700px] mr-12 bg-white bg-opacity-80 rounded-xl p-8 ">

        <h2 class="text-2xl font-bold text-center text-blue-900 mb-6">Admin Panel</h2>

        <!-- Error Message -->
        <?php if (!empty($error_message)): ?>
            <div class="mb-4 text-red-600 text-sm text-center font-semibold">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Loading Button -->
        <div id="loading-button" class="hidden text-center">
            <button type="button" class="bg-indigo-500 text-white w-full py-3 rounded-button font-medium flex items-center justify-center" disabled>
                <svg class="mr-3 animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0l4 4-4 4V4a4 4 0 00-4 4 4 4 0 004 4v4a8 8 0 01-8-8z"></path>
                </svg>
                Processing…
            </button>
        </div>

        <!-- Login Form -->
        <form id="login-form" method="POST" action="" onsubmit="showLoading(event)">
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="ri-mail-line"></i>
                    </div>
                    <input
                        type="email"
                        name="email"  
                        id="email"
                        required
                        placeholder="Enter your email"
                        class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary/20 focus:border-primary text-gray-900" />
                </div>
            </div>

            <div class="mb-5">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="ri-lock-line"></i>
                    </div>
                    <input
                        type="password"
                        name="password"  
                        required
                        placeholder="••••••••"
                        class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary/20 focus:border-primary text-gray-900" />
                </div>
            </div>

            <div class="mb-5 flex items-center space-x-2">
                <input type="checkbox" id="remember" name="remember" class="accent-primary">
                <label for="remember" class="text-sm text-gray-700">Remember me</label>
            </div>

            <button
                type="submit"
                class="w-full bg-primary hover:bg-secondary text-white py-3 rounded-button font-medium transition duration-200">
                Sign in
            </button>
        </form>
    </div>
</body>
</html>
