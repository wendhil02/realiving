<?php
// Include your database connection
include '../connection/connection.php';

// Get the project ID from the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $project_id = intval($_GET['id']); // Ensure it's an integer

    // Fetch the project details from the database
    $sql = "SELECT * FROM project WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $project_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch the project data
            $project = $result->fetch_assoc();
        } else {
            echo "<p>Project not found. Project ID: " . $project_id . "</p>";
            exit;
        }
        $stmt->close();
    } else {
        echo "<p>Database error: " . $conn->error . "</p>";
        exit;
    }

    // Fetch other projects (not necessarily related) to display in the "Related Projects" section
    $other_projects_sql = "SELECT * FROM project WHERE id != ? ORDER BY RAND() LIMIT 4";
    $other_projects_stmt = $conn->prepare($other_projects_sql);
    
    if ($other_projects_stmt) {
        $other_projects_stmt->bind_param("i", $project_id);
        $other_projects_stmt->execute();
        $other_projects_result = $other_projects_stmt->get_result();

        $other_projects = [];
        while ($row = $other_projects_result->fetch_assoc()) {
            $other_projects[] = $row;
        }
        $other_projects_stmt->close();
    }
} else {
    echo "<p>No project selected or invalid project ID.</p>";
    exit;
}

// Contact Form Handling
$contact_errors = [];
$contact_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    // Sanitize inputs
    $contact_name = trim(htmlspecialchars($_POST['name'] ?? ''));
    $contact_phone = trim(htmlspecialchars($_POST['phone'] ?? ''));
    $contact_email = trim(htmlspecialchars($_POST['email'] ?? ''));
    $contact_location = trim(htmlspecialchars($_POST['location'] ?? ''));

    // Validation
    if (empty($contact_name)) $contact_errors['name'] = 'Name is required';
    if (empty($contact_phone)) {
        $contact_errors['phone'] = 'Phone number is required';
    } elseif (!preg_match('/^[0-9]{10,15}$/', $contact_phone)) {
        $contact_errors['phone'] = 'Invalid phone format';
    }
    if (empty($contact_email)) {
        $contact_errors['email'] = 'Email is required';
    } elseif (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
        $contact_errors['email'] = 'Invalid email format';
    }
    if (empty($contact_location)) $contact_errors['location'] = 'Location is required';

    if (empty($contact_errors)) {
        // Process the form data here (email/save to DB)
        $contact_success = true;
    }
}

include "header/headernav.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Montserrat&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?php echo htmlspecialchars($project['title']); ?> - Project Details</title>
    <link rel="stylesheet" href="projects/project-template.css?v=2.0" />
</head>
<body class="scroll-hidden">


<section class="sub-header" style="background-image: url('<?php echo htmlspecialchars($project['main_image']); ?>');">
    <section class="sub-header-text"> 
        <h1 class="sub-header-text"><?php echo htmlspecialchars($project['title']); ?></h1>
    </section>
</section>

<section>
<div class="project-container">
    <!-- Top Left Description -->
    <section class="grid-item project-description animate-up delay-1">
        <h1 class="animate-up delay-1"><?php echo htmlspecialchars($project['title']); ?></h1>
        <p class="animate-up delay-1"><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>
    </section>

    <!-- Top Right Image -->
    <section class="grid-item project-image-top animate-up delay-1">
        <?php if (!empty($project['image1'])): ?>
            <img src="/realiving/realivingpage/<?php echo htmlspecialchars($project['image1']); ?>" 
                 alt="<?php echo htmlspecialchars($project['title']); ?> - Overview"
                 style="width: 100%; height: 100%; object-fit: cover; display: block;">
        <?php else: ?>
            <div class="image-placeholder" style="width: 100%; height: 100%; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                <p>No image available</p>
            </div>
        <?php endif; ?>
    </section>

    <!-- Bottom Left Image -->
    <section class="grid-item project-image-bottom animate-left delay-3">
        <?php if (!empty($project['image2'])): ?>
            <img src="/realivingpage/<?php echo htmlspecialchars($project['image2']); ?>" 
                 alt="<?php echo htmlspecialchars($project['title']); ?> - Details"
                 style="width: 100%; height: 100%; object-fit: cover; display: block;">
        <?php else: ?>
            <div class="image-placeholder" style="width: 100%; height: 100%; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                <p>No image available</p>
            </div>
        <?php endif; ?>
    </section>

    <!-- Bottom Right Contact Form -->
    <section class="grid-item contact-form-section animate-right delay-4">
        <h2 class="contact-header animate-right delay-4">INTERESTED?</h2>
        
        <?php if ($contact_success): ?>
            <div class="success-message">
                <p>Thank you for your interest! We'll contact you soon.</p>
            </div>
        <?php else: ?>
            <form class="contact-form animate-right delay-4" method="POST">
                <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($project['id']); ?>">
                
                <div class="form-group">
                    <input type="text" name="name" placeholder="NAME *" 
                           value="<?php echo isset($contact_name) ? htmlspecialchars($contact_name) : ''; ?>" required>
                    <?php if (isset($contact_errors['name'])): ?>
                        <span class="error"><?php echo $contact_errors['name']; ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <input type="tel" name="phone" placeholder="PHONE NUMBER *" 
                           value="<?php echo isset($contact_phone) ? htmlspecialchars($contact_phone) : ''; ?>" required>
                    <?php if (isset($contact_errors['phone'])): ?>
                        <span class="error"><?php echo $contact_errors['phone']; ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <input type="email" name="email" placeholder="EMAIL *" 
                           value="<?php echo isset($contact_email) ? htmlspecialchars($contact_email) : ''; ?>" required>
                    <?php if (isset($contact_errors['email'])): ?>
                        <span class="error"><?php echo $contact_errors['email']; ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <input type="text" name="location" placeholder="LOCATION *" 
                           value="<?php echo isset($contact_location) ? htmlspecialchars($contact_location) : ''; ?>">
                    <?php if (isset($contact_errors['location'])): ?>
                        <span class="error"><?php echo $contact_errors['location']; ?></span>
                    <?php endif; ?>
                </div>
                
                <button type="submit" name="contact_submit" class="submit-btn">SUBMIT</button>
            </form>
        <?php endif; ?>
    </section>
</div>
</section>

<!-- Footer -->
    <footer class="bg-[#faf6f0] py-8 px-4 md:px-12 text-black">
        <div class="flex flex-col md:flex-row justify-between items-start mb-8">
            <!-- Logo and Contact Info -->
            <div class="mb-6 md:mb-0 md:w-1/3">
                <div class="mb-6">
                    <span class="text-4xl font-bold text-[#0096c7]">Real</span><span class="text-4xl font-bold text-[#f59e0b]">Living</span>
                    <p class="text-xs text-gray-500">DESIGN · CENTER · CORPORATION</p>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-full bg-[#f59e0b] flex items-center justify-center mr-3">
                            <span class="text-white text-xs">☏</span>
                        </div>
                        <span class="text-sm">(+63) 912 345 6789</span>
                    </div>

                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-full bg-[#f59e0b] flex items-center justify-center mr-3">
                            <span class="text-white text-xs">✉</span>
                        </div>
                        <span class="text-sm">info@realliving.com</span>
                    </div>

                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-full bg-[#f59e0b] flex items-center justify-center mr-3">
                            <span class="text-white text-xs">◎</span>
                        </div>
                        <span class="text-sm">MC Premier-EDSA Balintawak, Quezon City</span>
                    </div>

                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-full bg-[#f59e0b] flex items-center justify-center mr-3">
                            <span class="text-white text-xs">⏱</span>
                        </div>
                        <span class="text-sm">Mon-Fr: 7AM-5PM | Sat 7AM-12PM</span>
                    </div>
                </div>
            </div>

            <!-- Quick Links Section -->
            <div class="mb-6 md:mb-0 md:w-1/3">
                <h3 class="text-lg font-semibold mb-4 text-[#3c1f0e]">Quick Links</h3>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-sm hover:text-[#f59e0b] transition-colors duration-200">Home</a></li>
                            <li><a href="#" class="text-sm hover:text-[#f59e0b] transition-colors duration-200">About Us</a></li>
                            <li><a href="#" class="text-sm hover:text-[#f59e0b] transition-colors duration-200">Services</a></li>
                            <li><a href="#" class="text-sm hover:text-[#f59e0b] transition-colors duration-200">Projects</a></li>
                        </ul>
                    </div>
                    <div>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-sm hover:text-[#f59e0b] transition-colors duration-200">Appointment</a></li>
                            <li><a href="#" class="text-sm hover:text-[#f59e0b] transition-colors duration-200">Contact</a></li>
                            <li><a href="#" class="text-sm hover:text-[#f59e0b] transition-colors duration-200">FAQ</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Social Media Links -->
            <div class="md:w-1/4">
                <h3 class="text-lg font-semibold mb-4 text-[#3c1f0e]">Follow us</h3>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-700 hover:text-[#f59e0b] transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-700 hover:text-[#f59e0b] transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-700 hover:text-[#f59e0b] transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-700 hover:text-[#f59e0b] transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z" />
                        </svg>
                    </a>
                </div>

                <!-- Newsletter Signup -->
                <div class="mt-6">
                    <h3 class="text-sm font-medium mb-2 text-[#3c1f0e]">Subscribe to our newsletter</h3>
                    <div class="flex">
                        <input type="email" placeholder="Your email" class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-[#f59e0b] w-full" />
                        <button class="bg-[#f59e0b] text-white px-3 py-2 rounded-r-md hover:bg-[#e59000] transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-gray-300 pt-4 text-center">
            <p class="text-xs uppercase text-gray-700">All rights reserved</p>
        </div>
    </footer>

<style>
.error {
    color: red;
    font-size: 12px;
    display: block;
    margin-top: 5px;
}

.success-message {
    background: #d4edda;
    color: #155724;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.image-placeholder {
    background: #f0f0f0;
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #ddd;
}

/* Add JavaScript to handle animations when page loads */
</style>

<script>
// Animation trigger when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Add visible class to trigger animations
    const animateElements = document.querySelectorAll('.animate-up, .animate-left, .animate-right');
    
    setTimeout(() => {
        animateElements.forEach(element => {
            if (element.classList.contains('animate-up')) {
                element.classList.add('in-view');
            } else {
                element.classList.add('animate-visible');
            }
        });
    }, 100);
});
</script>

</body>
</html>