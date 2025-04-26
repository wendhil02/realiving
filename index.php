<?php
session_start();
include 'connection/connection.php';

// Initialize error message
$error_message = "";

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password_input = $_POST['password'];

    if (!empty($email) && !empty($password_input)) {
        // Prepare a safe SQL statement
        $stmt = $conn->prepare("SELECT id, email, password FROM account WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if email exists
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            // Verify password
            if (password_verify($password_input, $row['password'])) {
                // Login success
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_email'] = $row['email'];

                header("Location: admin/mainpage");
                exit();
            } else {
                // Password wrong
                $error_message = "Incorrect password. Please try again.";
            }
        } else {
            // Email not found
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
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
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
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="h-screen flex">
    <!-- Left Side - Login Form -->
    <div class="flex w-full md:w-1/2 justify-center items-center bg-black relative overflow-hidden" style="background-image: url('logo/bg.png'); background-size: cover; background-position: center;">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Logos Centered -->
        <div class="absolute top-16 flex justify-center items-center space-x-6">
            <img src="logo/mmone.png" alt="Logo 1" class="relative h-20 max-w-sm object-contain drop-shadow-lg" />
            <img src="logo/noblebg.png" alt="Logo 2" class="relative h-20 max-w-sm object-contain drop-shadow-lg" />
        </div>

        <!-- Login Form -->
        <div class="relative w-auto max-w-sm p-5 rounded-xl bg-gray-200/90 backdrop-blur-md shadow-lg">
            <!-- Error Message -->
            <?php if (!empty($error_message)): ?>
                <div class="mb-4 text-red-600 text-sm text-center font-semibold">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" action="">
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none w-10 h-full justify-center">
                            <i class="ri-mail-line text-gray-400"></i>
                        </div>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-gray-900"
                            placeholder="Enter your email"
                            required />
                    </div>
                </div>

                <div class="mb-6">
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none w-10 h-full justify-center">
                            <i class="ri-lock-line text-gray-400"></i>
                        </div>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-gray-900"
                            placeholder="••••••••"
                            required />
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center w-10 h-full justify-center toggle-password">
                            <i class="ri-eye-off-line text-gray-400" id="togglePassword"></i>
                        </div>
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full bg-primary text-white py-3 rounded-button font-medium hover:bg-primary/90 transition-colors whitespace-nowrap">
                    Sign in
                </button>
            </form>
        </div>
    </div>


    <!-- Right Side - Title Section -->
    <div class="flex w-1/2 items-center justify-center bg-gray-700 relative overflow-hidden" id="bg-slide-container">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gray-900 bg-opacity-40"></div>

        <!-- Slide Content -->
        <div id="slide-container" class="relative text-center px-10 transition-opacity duration-1000">
            <h1 class="text-white text-5xl font-bold mb-4" id="title-text"></h1>
            <p class="text-white text-lg" id="subtitle-text"></p>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const togglePassword = document.getElementById("togglePassword");
            const passwordInput = document.getElementById("password");

            togglePassword.addEventListener("click", function() {
                const type =
                    passwordInput.getAttribute("type") === "password" ? "text" : "password";
                passwordInput.setAttribute("type", type);

                if (type === "password") {
                    togglePassword.classList.remove("ri-eye-line");
                    togglePassword.classList.add("ri-eye-off-line");
                } else {
                    togglePassword.classList.remove("ri-eye-off-line");
                    togglePassword.classList.add("ri-eye-line");
                }
            });
        });

        const slides = [{
                title: `<img src="logo/new.png" alt="Noble Home Logo" class="h-[150px] object-contain" />`,
                subtitle: "",
                bgImage: "url('code/images/background-image2.jpg')" // Sample building image
            },
            {
                title: `<img src="logo/mmone.png" alt="Real Living Logo" class="h-[150px] object-contain" />`,
                subtitle: "",
                bgImage: "url('logo/rlthree.jpg')" // Sample real estate image
            }
        ];

        let currentSlide = 0;
        const titleText = document.getElementById('title-text');
        const subtitleText = document.getElementById('subtitle-text');
        const slideContainer = document.getElementById('slide-container');
        const bgSlideContainer = document.getElementById('bg-slide-container');

        // Initialize first background
        bgSlideContainer.style.backgroundImage = slides[currentSlide].bgImage;
        bgSlideContainer.style.backgroundSize = 'cover';
        bgSlideContainer.style.backgroundPosition = 'center';

        setInterval(() => {
            slideContainer.classList.add('opacity-0');

            setTimeout(() => {
                currentSlide = (currentSlide + 1) % slides.length;
                titleText.innerHTML = slides[currentSlide].title;
                subtitleText.textContent = slides[currentSlide].subtitle;

                // Update background image
                bgSlideContainer.style.backgroundImage = slides[currentSlide].bgImage;
                bgSlideContainer.style.backgroundSize = 'cover';
                bgSlideContainer.style.backgroundPosition = 'center';

                slideContainer.classList.remove('opacity-0');
            }, 500);
        }, 4000);
    </script>


    <script>
        const menuBtn = document.getElementById("menu-btn");
        const mobileMenu = document.getElementById("mobile-menu");

        menuBtn.addEventListener("click", () => {
            mobileMenu.classList.toggle("hidden");
        });
    </script>
</body>

</html>