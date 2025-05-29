<?php
include '../../connection/connection.php';
include '../checkrole.php';
include '../design/mainbody.php';

// Allow only admin1 to admin5
require_role(['superadmin']);

// Fetch admin emails, client_status, and role
$admins = $conn->query("SELECT id, email, client_status, role FROM account WHERE role LIKE 'admin%'");


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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Billing Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'bounce-slow': 'bounce 3s infinite',
                        'pulse-slow': 'pulse 3s infinite',
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-header {
            background: linear-gradient(135deg,rgb(11, 96, 208) 0%,rgb(2, 96, 237) 100%);
        }
        
        .gradient-button {
            background: linear-gradient(45deg, #10b981, #059669);
        }
        
        .gradient-button:hover {
            background: linear-gradient(45deg, #059669, #047857);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .receipt-preview {
            transition: all 0.3s ease;
        }
        
        .receipt-preview:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <div class="gradient-header text-white py-4 mb-4">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">
                        <i class="fas fa-user-shield mr-3"></i>Admin Billing Panel
                    </h1>
                    <p class="text-white opacity-75">Manage billing submissions and send notifications</p>
                </div>
                <div>
                    <button onclick="refreshBillings()" class="bg-white text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4">
        <!-- Search and Filter -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-4">
                    <input type="text" 
                           id="searchInput" 
                           placeholder="Search by reference number or client name"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-3">
                    <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="received">Received</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <input type="date" 
                           id="dateFilter"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                    <button onclick="applyFilters()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Billing Records -->
        <div id="billingContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="col-span-full flex flex-col items-center justify-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                <p class="mt-4 text-gray-600">Loading billing records...</p>
            </div>
        </div>
    </div>

    <!-- Notification Container -->
    <div id="notificationContainer" class="fixed top-5 right-5 z-50 space-y-2"></div>

    <!-- Receipt Modal -->
    <div id="receiptModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-full overflow-auto">
            <div class="flex justify-between items-center p-4 border-b">
                <h5 class="text-xl font-semibold">Receipt Preview</h5>
                <button onclick="closeModal('receiptModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-4 text-center">
                <img id="receiptImage" src="" alt="Receipt" class="max-w-full h-auto">
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-md w-full">
            <div class="flex justify-between items-center p-4 border-b">
                <h5 class="text-xl font-semibold">Confirm Action</h5>
                <button onclick="closeModal('confirmModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-4">
                <p class="mb-4">Are you sure you want to mark this billing as received and send notification to the client?</p>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p><strong>Client:</strong> <span id="confirmClientName"></span></p>
                    <p><strong>Reference:</strong> <span id="confirmRefNumber"></span></p>
                </div>
            </div>
            <div class="flex justify-end gap-3 p-4 border-t">
                <button onclick="closeModal('confirmModal')" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button id="confirmReceiveBtn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-check mr-2"></i>Yes, Mark as Received
                </button>
            </div>
        </div>
    </div>

<script src="js/jsadminbilling/adminbilling.js"></script>
</body>
</html>