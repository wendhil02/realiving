<?php
include '../design/mainbody.php';
include '../../connection/connection.php';
session_start();

include '../checkrole.php';

// Allow only admin1 to admin5
require_role(['admin4', 'admin5', 'superadmin']);

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
    <link rel="icon" type="image/png" sizes="32x32" href="../../logo/favicon.ico">
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

        .notification-pulse {
            animation: pulse-notification 2s infinite;
        }

        @keyframes pulse-notification {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.7;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .sound-toggle {
            transition: all 0.3s ease;
        }

        .sound-enabled {
            background-color: #10b981;
            color: white;
        }

        .sound-disabled {
            background-color: #ef4444;
            color: white;
        }

        .new-inquiry-alert {
            border-left: 4px solid #f59e0b;
            background: linear-gradient(90deg, #fef3c7 0%, #ffffff 100%);
            animation: highlight-fade 3s ease-out;
        }

        @keyframes highlight-fade {
            0% {
                background: linear-gradient(90deg, #fbbf24 0%, #ffffff 100%);
            }
            100% {
                background: linear-gradient(90deg, #fef3c7 0%, #ffffff 100%);
            }
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen" x-data="notificationSystem()">
    <!-- Audio Elements for Notifications -->
    <!-- UPDATE THESE PATHS TO YOUR ACTUAL SOUND FILE LOCATIONS -->
    <audio id="newInquirySound" preload="auto">
        <source src="../sound/a.mp3" type="audio/mpeg">
        <source src="../../sounds/new-inquiry.wav" type="audio/wav">
        <source src="../../sounds/new-inquiry.ogg" type="audio/ogg">
    </audio>
    
    <audio id="successSound" preload="auto">
        <source src="../sound/a.mp3" type="audio/mpeg">
        <source src="../../sounds/success.wav" type="audio/wav">
        <source src="../../sounds/success.ogg" type="audio/ogg">
    </audio>
    
    <audio id="alertSound" preload="auto">
        <source src="../sound/a.mp3" type="audio/mpeg">
        <source src="../../sounds/alert.wav" type="audio/wav">
        <source src="../../sounds/alert.ogg" type="audio/ogg">
    </audio>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Page Header with Sound Controls -->
        <div class="gradient-bg text-white rounded-xl shadow-lg mb-8 p-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Inquiries Dashboard</h1>
                    <p class="text-blue-100">Manage and process client inquiries efficiently</p>
                </div>
                <div class="mt-4 md:mt-0 flex items-center space-x-4">
                    <!-- Sound Control Toggle -->
                    <div class="flex items-center space-x-2">
                        <button 
                            @click="toggleSounds()" 
                            :class="soundEnabled ? 'sound-enabled' : 'sound-disabled'"
                            class="sound-toggle px-4 py-2 rounded-lg shadow-sm flex items-center space-x-2 text-sm font-medium">
                            <i :class="soundEnabled ? 'fas fa-volume-up' : 'fas fa-volume-mute'"></i>
                            <span x-text="soundEnabled ? 'Sounds ON' : 'Sounds OFF'"></span>
                        </button>
                    </div>
                    <div class="text-sm bg-white/20 px-4 py-2 rounded-lg">
                        <?= date('l, F j, Y') ?>
                    </div>
                </div>
            </div>
            
            <!-- Auto-refresh indicator -->
            <div class="mt-4 flex items-center justify-between">
                <div class="flex items-center space-x-2 text-blue-100 text-sm">
                    <i class="fas fa-sync-alt" :class="{ 'fa-spin': isRefreshing }"></i>
                    <span>Auto-refresh: <span x-text="refreshInterval/1000"></span>s</span>
                    <span x-show="lastRefresh" class="text-xs">
                        (Last: <span x-text="lastRefresh"></span>)
                    </span>
                </div>
                <div class="flex items-center space-x-2">
                    <button @click="checkForNewInquiries()" class="bg-white/20 hover:bg-white/30 px-3 py-1 rounded text-sm transition-colors">
                        <i class="fas fa-refresh mr-1"></i> Check Now
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards with Sound Notifications -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 transition-all duration-300 card-hover">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Total Inquiries</p>
                        <h3 class="text-3xl font-bold text-gray-800" x-text="stats.total || <?= $total_inquiries ?>"><?= $total_inquiries ?></h3>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i class="fas fa-inbox text-blue-500"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500 transition-all duration-300 card-hover" 
                 :class="{ 'notification-pulse new-inquiry-alert': hasNewInquiries }">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1 flex items-center">
                            New Inquiries
                            <span x-show="hasNewInquiries" class="ml-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full animate-pulse">NEW!</span>
                        </p>
                        <h3 class="text-3xl font-bold text-gray-800" x-text="stats.new || <?= $new_inquiries ?>"><?= $new_inquiries ?></h3>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <i class="fas fa-bell text-yellow-500" :class="{ 'fa-bounce': hasNewInquiries }"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 transition-all duration-300 card-hover">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Sent to Admin</p>
                        <h3 class="text-3xl font-bold text-gray-800" x-text="stats.sent || <?= $sent_inquiries ?>"><?= $sent_inquiries ?></h3>
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
                                        class="text-blue-600 hover:text-blue-800 text-sm flex items-center focus:outline-none">
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
                                        <!-- Enhanced Send Form with Sound Feedback -->
                                        <form action="send_inquiry.php" method="POST" class="flex flex-col md:flex-row gap-3" 
                                              @submit="handleFormSubmit($event)">
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
                                                class="text-blue-600 hover:text-blue-800 text-sm flex items-center focus:outline-none">
                                                <i class="fas" :class="showCustomEmail ? 'fa-chevron-up mr-1' : 'fa-chevron-down mr-1'"></i>
                                                <span x-text="showCustomEmail ? 'Hide Custom Email Form' : 'Send to Custom Email'"></span>
                                            </button>
                                        </div>

                                        <!-- Custom Email Form -->
                                        <div x-show="showCustomEmail" x-cloak class="mt-2">
                                            <form action="send_inquiry.php" method="POST" class="flex flex-col md:flex-row gap-3"
                                                  @submit="handleFormSubmit($event)">
                                                <input type="hidden" name="inquiry_id" value="<?= $row['id'] ?>">
                                                <input type="hidden" name="message_type" value="admin">
                                                <div class="flex-grow">
                                                    <input
                                                        type="email"
                                                        name="custom_email"
                                                        placeholder="Enter email address"
                                                        required
                                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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

                    <!-- Sound Settings Card -->
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-volume-up text-blue-600 mr-2"></i> Sound Settings
                        </h3>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" x-model="soundSettings.newInquiry" class="form-checkbox text-blue-600">
                                <span class="ml-2 text-sm text-gray-700">New inquiry notifications</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" x-model="soundSettings.success" class="form-checkbox text-green-600">
                                <span class="ml-2 text-sm text-gray-700">Success confirmations</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" x-model="soundSettings.alert" class="form-checkbox text-yellow-600">
                                <span class="ml-2 text-sm text-gray-700">Alert notifications</span>
                            </label>
                            <div class="pt-2 border-t">
                                <button @click="testSounds()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded text-sm transition-colors">
                                    <i class="fas fa-play mr-1"></i> Test Sounds
                                </button>
                            </div>
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
                                <span class="text-gray-700">Sound notifications alert you to new inquiries</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-circle-check text-green-500 mt-1"></i>
                                <span class="text-gray-700">Toggle sounds on/off in the header</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-circle-check text-green-500 mt-1"></i>
                                <span class="text-gray-700">Auto-refresh checks for new inquiries every 30 seconds</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-circle-check text-green-500 mt-1"></i>
                                <span class="text-gray-700">Customize sound settings in the sidebar</span>
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

    <script>
    function notificationSystem() {
    return {
        soundEnabled: localStorage.getItem('soundEnabled') !== 'false',
        soundSettings: {
            newInquiry: localStorage.getItem('sound_newInquiry') !== 'false',
            success: localStorage.getItem('sound_success') !== 'false',
            alert: localStorage.getItem('sound_alert') !== 'false',
        },
        stats: {
            total: <?= $total_inquiries ?>,
            new: <?= $new_inquiries ?>,
            sent: <?= $sent_inquiries ?>
        },
        hasNewInquiries: false,
        isRefreshing: false,
        refreshInterval: 30000, // 30 seconds
        lastRefresh: null,
        refreshTimer: null,
        audioInitialized: false,

        init() {
            // Initialize audio context on first user interaction
            this.initializeAudio();
            
            // Start auto-refresh
            this.startAutoRefresh();
            
            // Watch for sound setting changes
            this.$watch('soundSettings', (newSettings) => {
                for (const [key, value] of Object.entries(newSettings)) {
                    localStorage.setItem(`sound_${key}`, value);
                }
            }, { deep: true });
        },

        async initializeAudio() {
            // Wait for user interaction to enable audio
            const enableAudio = () => {
                if (!this.audioInitialized) {
                    // Try to play a silent sound to unlock audio context
                    const audio = document.getElementById('newInquirySound');
                    if (audio) {
                        audio.volume = 0.1;
                        audio.play().then(() => {
                            audio.pause();
                            audio.volume = 1;
                            this.audioInitialized = true;
                            console.log('Audio initialized successfully');
                        }).catch(e => {
                            console.log('Audio initialization failed:', e);
                        });
                    }
                }
            };

            // Add event listeners for user interaction
            ['click', 'touchstart', 'keydown'].forEach(event => {
                document.addEventListener(event, enableAudio, { once: true });
            });
        },

        toggleSounds() {
            this.soundEnabled = !this.soundEnabled;
            localStorage.setItem('soundEnabled', this.soundEnabled);
            
            // Show feedback
            if (this.soundEnabled) {
                setTimeout(() => this.playSound('success'), 100);
            }
        },

        async playSound(soundType) {
            if (!this.soundEnabled || !this.soundSettings[soundType]) return;
            
            const audioMap = {
                newInquiry: 'newInquirySound',
                success: 'successSound',
                alert: 'alertSound'
            };
            
            const audio = document.getElementById(audioMap[soundType]);
            if (!audio) {
                console.log(`Audio element ${audioMap[soundType]} not found`);
                return;
            }

            try {
                // Reset audio to beginning
                audio.currentTime = 0;
                
                // Set volume
                audio.volume = 0.7;
                
                // Ensure audio is loaded
                if (audio.readyState < 2) {
                    audio.load();
                    await new Promise(resolve => {
                        audio.addEventListener('canplaythrough', resolve, { once: true });
                    });
                }
                
                // Play with error handling
                await audio.play();
                console.log(`${soundType} sound played successfully`);
                
            } catch (error) {
                console.log(`Could not play ${soundType} sound:`, error);
                
                // Fallback: try to reload and play again
                try {
                    audio.load();
                    setTimeout(async () => {
                        try {
                            await audio.play();
                            console.log(`${soundType} sound played on retry`);
                        } catch (retryError) {
                            console.log(`Retry failed for ${soundType}:`, retryError);
                        }
                    }, 500);
                } catch (fallbackError) {
                    console.log(`Fallback failed for ${soundType}:`, fallbackError);
                }
            }
        },

        testSounds() {
            // Test all enabled sounds with a 3-second delay between each
            const sounds = ['newInquiry', 'success', 'alert'];
            let delay = 0;
            
            sounds.forEach(soundType => {
                if (this.soundSettings[soundType]) {
                    setTimeout(() => {
                        console.log(`Testing ${soundType} sound...`);
                        this.playSound(soundType);
                    }, delay);
                    delay += 3000; // 3 second delay between sounds
                }
            });
        },

        // Add a manual sound test method
        testSingleSound(soundType) {
            console.log(`Manual test for ${soundType} sound`);
            this.playSound(soundType);
        },

        async checkForNewInquiries() {
            this.isRefreshing = true;
            
            try {
                const response = await fetch('check_new_inquiries.php');
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (data.success) {
                    const previousNewCount = this.stats.new;
                    
                    // Update stats
                    this.stats.total = data.stats.total;
                    this.stats.new = data.stats.new;
                    this.stats.sent = data.stats.sent;
                    
                    // Check if there are new inquiries
                    if (data.stats.new > previousNewCount) {
                        this.hasNewInquiries = true;
                        
                        // Play sound with 3-second delay
                        setTimeout(() => {
                            this.playSound('newInquiry');
                        }, 3000);
                        
                        // Show browser notification
                        this.showBrowserNotification('New Inquiry Received!', 
                            `You have ${data.stats.new} new inquiries.`);
                        
                        // Clear the "new" indicator after 8 seconds (3s delay + 5s display)
                        setTimeout(() => {
                            this.hasNewInquiries = false;
                        }, 8000);
                        
                        // Optionally reload the page to show new inquiries (with longer delay)
                        setTimeout(() => {
                            window.location.reload();
                        }, 5000);
                    }
                } else {
                    console.error('API returned error:', data.error || 'Unknown error');
                }
                
                this.lastRefresh = new Date().toLocaleTimeString();
            } catch (error) {
                console.error('Error checking for new inquiries:', error);
                // Play alert sound immediately for errors
                this.playSound('alert');
            } finally {
                this.isRefreshing = false;
            }
        },

        startAutoRefresh() {
            // Initial check after 5 seconds to allow page to fully load
            setTimeout(() => {
                this.checkForNewInquiries();
            }, 5000);
            
            // Set up interval
            this.refreshTimer = setInterval(() => {
                this.checkForNewInquiries();
            }, this.refreshInterval);
        },

        stopAutoRefresh() {
            if (this.refreshTimer) {
                clearInterval(this.refreshTimer);
                this.refreshTimer = null;
            }
        },

        handleFormSubmit(event) {
            // Play success sound with 1-second delay for form submission
            setTimeout(() => {
                this.playSound('success');
            }, 1000);
            
            // Add loading indicator
            const submitButton = event.target.querySelector('button[type="submit"]');
            if (submitButton) {
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Sending...';
                submitButton.disabled = true;
                
                // Re-enable after form submission
                setTimeout(() => {
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                }, 3000);
            }
        },

        async showBrowserNotification(title, body) {
            // Request permission if not already granted
            if (Notification.permission === 'default') {
                await Notification.requestPermission();
            }
            
            // Show notification if permission is granted
            if (Notification.permission === 'granted') {
                const notification = new Notification(title, {
                    body: body,
                    icon: '../../logo/favicon.ico',
                    badge: '../../logo/favicon.ico',
                    tag: 'inquiry-notification',
                    requireInteraction: true,
                    silent: false // Allow notification sound
                });
                
                // Auto-close notification after 10 seconds
                setTimeout(() => {
                    notification.close();
                }, 10000);
            }
        },

        // Add debugging method
        debugAudio() {
            const audioElements = ['newInquirySound', 'successSound', 'alertSound'];
            audioElements.forEach(id => {
                const audio = document.getElementById(id);
                if (audio) {
                    console.log(`${id}:`, {
                        src: audio.currentSrc,
                        readyState: audio.readyState,
                        volume: audio.volume,
                        muted: audio.muted,
                        paused: audio.paused
                    });
                } else {
                    console.log(`${id}: Element not found`);
                }
            });
        },

        // Cleanup when component is destroyed
        destroy() {
            this.stopAutoRefresh();
        }
    }
}
        // Handle page visibility changes to pause/resume auto-refresh
        document.addEventListener('visibilitychange', function() {
            const notificationComponent = window.Alpine?.getStore?.('notificationSystem');
            if (notificationComponent) {
                if (document.hidden) {
                    // Page is hidden, stop auto-refresh to save resources
                    notificationComponent.stopAutoRefresh();
                } else {
                    // Page is visible again, resume auto-refresh
                    notificationComponent.startAutoRefresh();
                }
            }
        });

        // Handle window focus/blur for better performance
        window.addEventListener('focus', function() {
            // When window gains focus, immediately check for new inquiries
            const notificationComponent = document.querySelector('[x-data]')?.__x?.$data;
            if (notificationComponent && typeof notificationComponent.checkForNewInquiries === 'function') {
                notificationComponent.checkForNewInquiries();
            }
        });

        // Add some utility functions for better UX
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth scrolling to anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + R to refresh manually
                if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                    e.preventDefault();
                    const notificationComponent = document.querySelector('[x-data]')?.__x?.$data;
                    if (notificationComponent && typeof notificationComponent.checkForNewInquiries === 'function') {
                        notificationComponent.checkForNewInquiries();
                    }
                }
                
                // Ctrl/Cmd + M to toggle sounds
                if ((e.ctrlKey || e.metaKey) && e.key === 'm') {
                    e.preventDefault();
                    const notificationComponent = document.querySelector('[x-data]')?.__x?.$data;
                    if (notificationComponent && typeof notificationComponent.toggleSounds === 'function') {
                        notificationComponent.toggleSounds();
                    }
                }
            });
        });

        // Add error handling for network issues
        window.addEventListener('online', function() {
            console.log('Connection restored. Resuming auto-refresh...');
            const notificationComponent = document.querySelector('[x-data]')?.__x?.$data;
            if (notificationComponent && typeof notificationComponent.startAutoRefresh === 'function') {
                notificationComponent.startAutoRefresh();
            }
        });

        window.addEventListener('offline', function() {
            console.log('Connection lost. Pausing auto-refresh...');
            const notificationComponent = document.querySelector('[x-data]')?.__x?.$data;
            if (notificationComponent && typeof notificationComponent.stopAutoRefresh === 'function') {
                notificationComponent.stopAutoRefresh();
            }
        });
    </script>
</body>
</html>