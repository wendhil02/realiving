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
    header("Location: ../admin/admin_mainpage/mainpage");
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

                header("Location: ../admin/admin_mainpage/mainpage");
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

                header("Location: ../admin/admin_mainpage/mainpage");
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
                        primary: "#3b82f6",
                        secondary: "#2563eb",
                        accent: "#1e40af",
                        dark: "#1e293b"
                    },
                    borderRadius: {
                        button: "12px",
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                    boxShadow: {
                        custom: '0 10px 25px -5px rgba(59, 130, 246, 0.1), 0 8px 10px -6px rgba(59, 130, 246, 0.1)',
                    }
                },
            },
        };

        // Show the loading spinner and hide the form, then submit after delay
        function showLoading(event) {
            event.preventDefault(); // Prevent the form from being submitted immediately
            document.getElementById("loading-button").classList.remove("hidden");
            document.getElementById("login-form").classList.add("hidden");

            // Wait for 3 seconds and then submit the form
            setTimeout(function() {
                document.getElementById("login-form").submit();
            }, 3000); // 3 seconds delay
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .glass-effect {
            backdrop-filter: blur(16px);
            background: rgba(255, 255, 255, 0.85);
        }
        
        .input-field:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }
        
        .custom-checkbox {
            border-radius: 4px;
            width: 18px;
            height: 18px;
            accent-color: #3b82f6;
        }
        
        .login-animation {
            animation: fadeIn 0.6s ease-in-out;
        }
        
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        
        .form-container {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .logo-glow {
            filter: drop-shadow(0 0 12px rgba(59, 130, 246, 0.6));
        }
    </style>
</head>
<body class="h-screen w-full flex items-center justify-center bg-cover bg-center relative" style="background-image: url('../realivingpage/images/new.png');">

    <!-- Left half: Background image with overlay -->
    <div class="absolute left-0 top-0 w-1/2 h-full bg-cover bg-center z-0" style="background-image: url('../logo/bg.png');"></div>
    <div class="absolute left-0 top-0 w-1/2 h-full bg-gradient-to-r from-dark to-primary/80 opacity-80 z-10"></div>

    <!-- Left half: Logos and text -->
    <div class="absolute left-0 top-0 w-1/2 h-full flex flex-col items-center justify-center space-y-10 z-20 login-animation">
        <img src="../logo/mmone.png" alt="Logo" class="h-32 object-contain logo-glow transition-all duration-300 hover:scale-105" />
        <div class="text-center max-w-md px-6">
            <h1 class="text-3xl font-bold text-white mb-4">Welcome Back</h1>
            <p class="text-white/90 text-lg">Access your admin dashboard to manage your system effectively.</p>
        </div>
    </div>

    <!-- Right half: Login Form -->
    <div class="relative z-30 w-full max-w-md ml-auto mr-12 login-animation">
        <div class="form-container glass-effect rounded-2xl p-10">
            <div class="flex items-center justify-center mb-8">
                <div class="bg-primary/10 rounded-full p-4">
                    <i class="ri-admin-line text-primary text-3xl"></i>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-center text-dark mb-2">Admin Login</h2>
            <p class="text-gray-500 text-center mb-8">Enter your credentials to continue</p>

            <!-- Error Message -->
            <?php if (!empty($error_message)): ?>
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="ri-error-warning-line text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium">
                                <?php echo htmlspecialchars($error_message); ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Loading Button -->
            <div id="loading-button" class="hidden text-center">
                <button type="button" class="bg-primary text-white w-full py-4 rounded-button font-medium flex items-center justify-center shadow-lg shadow-primary/30 hover:shadow-primary/40 transition-all duration-300" disabled>
                    <svg class="mr-3 animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0l4 4-4 4V4a4 4 0 00-4 4 4 4 0 004 4v4a8 8 0 01-8-8z"></path>
                    </svg>
                    Authenticating...
                </button>
            </div>

            <!-- Login Form -->
            <form id="login-form" method="POST" action="" onsubmit="showLoading(event)" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500">
                            <i class="ri-mail-line"></i>
                        </div>
                        <input
                            type="email"
                            name="email"  
                            id="email"
                            required
                            placeholder="admin@example.com"
                            class="input-field w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-gray-800 bg-gray-50/80 transition-all duration-200 font-medium text-sm" />
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                       
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500">
                            <i class="ri-lock-line"></i>
                        </div>
                        <input
                            type="password"
                            name="password"  
                            required
                            placeholder="••••••••"
                            class="input-field w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-gray-800 bg-gray-50/80 transition-all duration-200 font-medium text-sm" />
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="remember" name="remember" class="custom-checkbox">
                    <label for="remember" class="text-sm text-gray-600">Keep me signed in</label>
                </div>

                <button
                    type="submit"
                    class="w-full bg-primary hover:bg-secondary text-white py-4 rounded-xl font-semibold transition-all duration-300 shadow-lg shadow-primary/30 hover:shadow-primary/40 transform hover:-translate-y-1">
                    Sign in to Dashboard
                </button>
            </form>
            
            <div class="text-center mt-8">
                <p class="text-sm text-gray-500">Protected Admin Area <i class="ri-shield-check-line ml-1 text-primary"></i></p>
            </div>
        </div>
    </div>
</body>
</html>