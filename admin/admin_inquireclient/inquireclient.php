<?php
include '../design/mainbody.php';
include '../../connection/connection.php';
session_start();

include '../checkrole.php';


// Allow only admin1 to admin5
require_role([ 'admin4', 'admin5', 'superadmin']);

if (isset($_SESSION['admin_email'], $_SESSION['admin_role'])) {
    echo '
      <div class="mb-4 p-2 bg-gray-100 rounded text-sm text-gray-700 flex justify-end space-x-4">
        <span>Logged in as:</span>
        <span class="font-medium">' . htmlspecialchars($_SESSION['admin_email']) . '</span>
        <span class="text-gray-500">|</span>
        <span class="font-semibold">' . htmlspecialchars($_SESSION['admin_role']) . '</span>
      </div>
    ';
}

// Check if the 'status' filter is set
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Modify the query based on the filter
$query = "SELECT * FROM contact_inquiries";

// Add the WHERE clause only if the filter is applied
if ($status_filter == 'new') {
    $query .= " WHERE sent_to_admin = 0"; // 0 means not sent to admin (new inquiries)
} elseif ($status_filter == 'sent') {
    $query .= " WHERE sent_to_admin = 1"; // 1 means sent to admin
}

$query .= " ORDER BY created_at DESC"; // Ensure ordering always happens

$inquiries = $conn->query($query);

// Fetch admin emails, client_status, and role
$admins = $conn->query("SELECT id, email, client_status, role FROM account WHERE role LIKE 'admin%'");

// Get counts for dashboard stats
$total_inquiries = $conn->query("SELECT COUNT(*) AS count FROM contact_inquiries")->fetch_assoc()['count'];
$new_inquiries = $conn->query("SELECT COUNT(*) AS count FROM contact_inquiries WHERE sent_to_admin = 0")->fetch_assoc()['count'];
$sent_inquiries = $conn->query("SELECT COUNT(*) AS count FROM contact_inquiries WHERE sent_to_admin = 1")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inquiries Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.12.0/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        }
        .admin-card:hover {
            border-color: #3b82f6;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="gradient-bg text-white rounded-xl shadow-lg mb-8 p-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Inquiries Dashboard</h1>
                    <p class="text-blue-100">Manage and process client inquiries efficiently</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <div class="text-sm bg-white/20 px-4 py-2 rounded-lg">
                        <?= date('l, F j, Y') ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 transition-all duration-300 card-hover">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Total Inquiries</p>
                        <h3 class="text-3xl font-bold text-gray-800"><?= $total_inquiries ?></h3>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i class="fas fa-inbox text-blue-500"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500 transition-all duration-300 card-hover">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">New Inquiries</p>
                        <h3 class="text-3xl font-bold text-gray-800"><?= $new_inquiries ?></h3>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <i class="fas fa-bell text-yellow-500"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 transition-all duration-300 card-hover">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Sent to Admin</p>
                        <h3 class="text-3xl font-bold text-gray-800"><?= $sent_inquiries ?></h3>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Left: Inquiries Section -->
            <div class="w-full lg:w-2/3 space-y-6">
                <!-- Filter Form -->
                <div class="bg-white shadow-md rounded-xl p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        <i class="fas fa-filter text-blue-600 mr-2"></i>Filter Inquiries
                    </h2>
                    <form method="GET" action="" class="flex flex-col md:flex-row md:items-end gap-4">
                        <div class="flex-grow">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status:</label>
                            <select name="status" id="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="">All Inquiries</option>
                                <option value="new" <?= isset($_GET['status']) && $_GET['status'] == 'new' ? 'selected' : '' ?>>New Inquiries</option>
                                <option value="sent" <?= isset($_GET['status']) && $_GET['status'] == 'sent' ? 'selected' : '' ?>>Sent to Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-sm transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-search mr-2"></i> Apply Filter
                        </button>
                    </form>
                </div>

                <!-- Inquiries List -->
                <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-envelope-open-text text-blue-600 mr-2"></i>
                    <?php if ($status_filter == 'new'): ?>
                        New Inquiries
                    <?php elseif ($status_filter == 'sent'): ?>
                        Sent Inquiries
                    <?php else: ?>
                        All Inquiries
                    <?php endif; ?>
                </h2>

                <?php if ($inquiries->num_rows > 0): ?>
                    <?php while ($row = $inquiries->fetch_assoc()): ?>
                        <div class="bg-white shadow-md rounded-xl p-6 border border-gray-200 transition-all duration-300 card-hover mb-4" x-data="{ open: false, showCustomEmail: false }">
                            <div class="flex flex-col md:flex-row justify-between">
                                <div class="mb-4 md:mb-0">
                                    <div class="flex items-center mb-2">
                                        <div class="bg-blue-100 text-blue-600 p-2 rounded-full mr-3">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($row['full_name']) ?></h3>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 mt-3">
                                        <p class="text-gray-700 flex items-center">
                                            <i class="fas fa-phone text-gray-500 mr-2"></i>
                                            <?= htmlspecialchars($row['phone_number']) ?>
                                        </p>
                                        <p class="text-gray-700 flex items-center">
                                            <i class="fas fa-envelope text-gray-500 mr-2"></i>
                                            <?= htmlspecialchars($row['email']) ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col items-end">
                                    <?php
                                    $client_type = htmlspecialchars($row['client_type'] ?? 'N/A');
                                    $client_type_class = 'bg-gray-200 text-gray-800';

                                    // Apply different colors based on client_type
                                    if (strtolower($client_type) == 'noblehome') {
                                        $client_type_class = 'bg-orange-600 text-white';
                                    } elseif (strtolower($client_type) == 'realiving') {
                                        $client_type_class = 'bg-yellow-600 text-white';
                                    }
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-sm font-medium mb-2 <?= $client_type_class ?>">
                                        <?= $client_type ?>
                                    </span>
                                    
                                    <div class="text-gray-500 text-sm flex items-center">
                                        <i class="far fa-clock mr-1"></i>
                                        <?= date('M j, Y \a\t g:i A', strtotime($row['created_at'])) ?>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (isset($row['message']) && !empty($row['message'])): ?>
                                <div class="mt-4">
                                    <button 
                                        @click="open = !open" 
                                        class="text-blue-600 hover:text-blue-800 text-sm flex items-center focus:outline-none"
                                    >
                                        <span x-text="open ? 'Hide Message' : 'View Message'"></span>
                                        <i class="fas" :class="open ? 'fa-chevron-up ml-1' : 'fa-chevron-down ml-1'"></i>
                                    </button>
                                    
                                    <div x-show="open" x-cloak class="mt-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                        <p class="text-gray-700"><?= nl2br(htmlspecialchars($row['message'])) ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <?php if ($row['sent_to_admin']): ?>
                                    <div class="flex items-center text-green-600 font-medium">
                                        <i class="fas fa-check-circle mr-2"></i> Sent to Admin
                                    </div>
                                <?php else: ?>
                                    <div class="flex flex-col space-y-3">
                                        <!-- Existing Admin Selection Form -->
                                        <form action="send_inquiry.php" method="POST" class="flex flex-col md:flex-row gap-3">
                                            <input type="hidden" name="inquiry_id" value="<?= $row['id'] ?>">
                                            <div class="flex-grow">
                                                <select name="recipient_email" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="">-- Select Admin --</option>
                                                    <?php 
                                                    // Reset pointer for admins
                                                    $admins->data_seek(0);
                                                    foreach ($admins as $admin): 
                                                    ?>
                                                        <option value="<?= htmlspecialchars($admin['email']) ?>">
                                                            <?= htmlspecialchars($admin['email']) ?> - <?= htmlspecialchars($admin['client_status']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-sm transition-all duration-300 flex items-center justify-center whitespace-nowrap">
                                                <i class="fas fa-paper-plane mr-2"></i> Send to Admin
                                            </button>
                                        </form>
                                        
                                        <!-- Custom Email Option -->
                                        <div class="flex items-center">
                                            <button 
                                                @click="showCustomEmail = !showCustomEmail" 
                                                class="text-blue-600 hover:text-blue-800 text-sm flex items-center focus:outline-none"
                                            >
                                                <i class="fas" :class="showCustomEmail ? 'fa-chevron-up mr-1' : 'fa-chevron-down mr-1'"></i>
                                                <span x-text="showCustomEmail ? 'Hide Custom Email Form' : 'Send to Custom Email'"></span>
                                            </button>
                                        </div>
                                        
                                    <!-- Custom Email Form -->
<div x-show="showCustomEmail" x-cloak class="mt-2">
    <form action="send_inquiry.php" method="POST" class="flex flex-col md:flex-row gap-3">
        <input type="hidden" name="inquiry_id" value="<?= $row['id'] ?>">
        <input type="hidden" name="message_type" value="admin">
        <div class="flex-grow">
            <input 
                type="email" 
                name="custom_email" 
                placeholder="Enter email address" 
                required 
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
        </div>
        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg shadow-sm transition-all duration-300 flex items-center justify-center whitespace-nowrap">
            <i class="fas fa-envelope mr-2"></i> Send to Email
        </button>
    </form>
</div>

                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="bg-white rounded-xl shadow-md p-8 text-center border border-dashed border-gray-300">
                        <div class="text-gray-400 mb-3">
                            <i class="fas fa-inbox fa-3x"></i>
                        </div>
                        <h3 class="text-xl font-medium text-gray-700 mb-1">No inquiries found</h3>
                        <p class="text-gray-500">There are no inquiries matching your current filter.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right: Admins Sidebar -->
            <div class="w-full lg:w-1/3">
                <div class="sticky top-6">
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200 mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user-shield text-blue-600 mr-2"></i> Admin Team
                        </h2>
                        <div class="space-y-4">
                            <?php
                            // Re-execute the query for sidebar if $admins is exhausted
                            $admins_sidebar = $conn->query("SELECT id, email, client_status, role FROM account WHERE role LIKE 'admin%'");
                            while ($admin = $admins_sidebar->fetch_assoc()):
                            ?>
                                <div class="admin-card p-4 rounded-lg border border-gray-200 transition-all duration-300 hover:shadow-md">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-blue-100 text-blue-600 p-2 rounded-full">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800"><?= htmlspecialchars($admin['email']) ?></p>
                                            <div class="mt-1 flex flex-wrap gap-2">
                                                <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">
                                                    <?= htmlspecialchars($admin['role']) ?>
                                                </span>
                                                <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded">
                                                    <?= htmlspecialchars($admin['client_status']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    
                    <!-- Quick Help Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl shadow-md p-6 border border-blue-100">
                        <h3 class="text-lg font-semibold text-blue-800 mb-3 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i> Quick Help
                        </h3>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-start gap-2">
                                <i class="fas fa-circle-check text-green-500 mt-1"></i>
                                <span class="text-gray-700">Use filters to sort inquiries by status</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-circle-check text-green-500 mt-1"></i>
                                <span class="text-gray-700">Select an admin based on their client status</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-circle-check text-green-500 mt-1"></i>
                                <span class="text-gray-700">Send to a custom email address when needed</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-circle-check text-green-500 mt-1"></i>
                                <span class="text-gray-700">Once sent, inquiries are marked as processed</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-12 py-6 border-t border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-500 text-sm">
            &copy; <?= date('Y') ?> Inquiries Dashboard. All rights reserved.
        </div>
    </footer>
</body>

</html>