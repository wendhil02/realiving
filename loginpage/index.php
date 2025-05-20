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
                        primary: "#4f46e5",
                        secondary: "#4338ca",
                        accent: "#3730a3"
                    },
                }
            },
        };

        // Simple loading function - no disabling of inputs
        function showLoading(event) {
            event.preventDefault();
            document.getElementById("loading-button").classList.remove("hidden");
            
            // Wait for 1 second and then submit the form normally
            setTimeout(function() {
                document.getElementById("login-form").submit();
            }, 1000);
        }

        // Toggle password visibility
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordIcon.classList.remove('ri-eye-line');
                passwordIcon.classList.add('ri-eye-off-line');
            } else {
                passwordField.type = 'password';
                passwordIcon.classList.remove('ri-eye-off-line');
                passwordIcon.classList.add('ri-eye-line');
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .pulse-animation {
            animation: pulse 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 to-blue-100 flex items-center justify-center p-4">
    <div class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col lg:flex-row">
        <!-- Left side - decorative area -->
        <div class="hidden lg:block lg:w-1/2 relative bg-gradient-to-br from-indigo-600 to-purple-700">
            <div class="absolute inset-0 bg-black opacity-30"></div>
            
            <!-- Decorative elements -->
            <div class="absolute top-8 left-8 z-10">
                <img src="../logo/mmone.png" alt="Logo" class="h-16 object-contain" />
            </div>
            
            <div class="absolute inset-0 flex flex-col justify-center items-center z-10 px-12">
                <div class="float-animation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="90" height="90" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mb-8 opacity-90">
                        <path d="M21 13V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8"></path>
                        <path d="M16 2v4"></path>
                        <path d="M8 2v4"></path>
                        <path d="M3 10h18"></path>
                        <circle cx="18" cy="18" r="3"></circle>
                        <path d="M18 14v1"></path>
                        <path d="M18 21v1"></path>
                        <path d="M14 18h1"></path>
                        <path d="M21 18h1"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-4 text-center">Admin Portal</h1>
                <p class="text-white/80 text-center mb-6">Access your dashboard to manage and monitor your system</p>
                
                <div class="grid grid-cols-2 gap-4 w-full max-w-xs">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 pulse-animation">
                        <div class="text-white/90 text-sm font-medium mb-1">Analytics</div>
                        <div class="text-white/70 text-xs">Real-time Insights</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 pulse-animation">
                        <div class="text-white/90 text-sm font-medium mb-1">Management</div>
                        <div class="text-white/70 text-xs">Efficient Control</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 pulse-animation">
                        <div class="text-white/90 text-sm font-medium mb-1">Security</div>
                        <div class="text-white/70 text-xs">Advanced Protection</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 pulse-animation">
                        <div class="text-white/90 text-sm font-medium mb-1">Reporting</div>
                        <div class="text-white/70 text-xs">Detailed Insights</div>
                    </div>
                </div>
            </div>
            
            <div class="absolute bottom-8 left-8 right-8 text-white/70 text-sm z-10">
                Secure Admin Dashboard © 2025
            </div>
        </div>
        
        <!-- Right side - login form -->
        <div class="w-full lg:w-1/2 p-6 sm:p-10 lg:p-16 flex flex-col justify-center">
            <div class="flex items-center justify-center lg:hidden mb-10">
                <img src="../logo/mmone.png" alt="Logo" class="h-16 object-contain" />
            </div>
            
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome back</h2>
                <p class="text-gray-500">Please enter your credentials to access the admin panel</p>
            </div>
            
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
            <div id="loading-button" class="hidden mb-6">
                <div class="w-full py-4 bg-primary text-white rounded-xl flex items-center justify-center">
                    <svg class="animate-spin h-5 w-5 mr-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Authenticating...</span>
                </div>
            </div>

            <!-- Login Form -->
            <form id="login-form" method="POST" action="" onsubmit="showLoading(event)" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="ri-mail-line"></i>
                        </div>
                        <input
                            type="email"
                            name="email"  
                            id="email"
                            required
                            autocomplete="email"
                            placeholder="admin@example.com"
                            class="w-full pl-12 pr-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white text-gray-800 transition-all duration-200 text-sm" />
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="ri-lock-line"></i>
                        </div>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full pl-12 pr-12 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white text-gray-800 transition-all duration-200 text-sm" />
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                            <button type="button" onclick="togglePassword()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                <i id="password-icon" class="ri-eye-line"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary/20">
                        <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full bg-primary hover:bg-secondary text-white py-3.5 rounded-xl font-medium transition-all duration-300 shadow-lg shadow-primary/30 hover:shadow-primary/40 flex items-center justify-center">
                    <i class="ri-login-box-line mr-2"></i>
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