<?php
session_start();
include '../../connection/connection.php';
include '../design/mainbody.php';
include '../checkrole.php';
// Allow only admin1 and superadmin
require_role(['admin1', 'superadmin']);

// Handle delete request with prepared statement
if (isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM user_info WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    $stmt->execute();
    $stmt->close();
    
    // Set success message
    $_SESSION['success_message'] = "Client deleted successfully!";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Automatically update old clients
$conn->query("UPDATE user_info SET status = 'Old Client' 
    WHERE created_at <= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) AND status != 'Old Client'");

// Handle new client form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['clientname'], $_POST['status'], $_POST['nameproject'], $_POST['client_type']) && !isset($_POST['export'])) {
    $clientname = $_POST['clientname'];
    $status = $_POST['status'];
    $nameproject = $_POST['nameproject'];
    $client_type = $_POST['client_type'];
    $updateTime = date('Y-m-d H:i:s');

    $reference_number = "REF" . date("YmdHis") . strtoupper(substr(md5(uniqid()), 0, 4));

    $stmt = $conn->prepare("INSERT INTO user_info (clientname, status, nameproject, updatestatus, update_time, reference_number, client_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $clientname, $status, $nameproject, $status, $updateTime, $reference_number, $client_type);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "New client added successfully!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $stmt->error;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    $stmt->close();
}

// Export logic
if (isset($_POST['export'])) {
    $sql = "SELECT clientname, reference_number, nameproject, client_type FROM user_info";
    $result = $conn->query($sql);

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="clientRef.csv"');
    $output = fopen("php://output", "w");
    fputcsv($output, ['Client Name', 'Reference Number', 'Project Name', 'Client Type']);

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';
$filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : '';

// Prepare base query
$sql = "SELECT * FROM user_info WHERE 1=1";

// Add search condition if search term is provided
if (!empty($search)) {
    $search = "%$search%";
    $sql .= " AND (clientname LIKE '$search' OR reference_number LIKE '$search' OR nameproject LIKE '$search')";
}

// Add filter conditions
if (!empty($filter_status)) {
    $sql .= " AND status = '$filter_status'";
}

if (!empty($filter_type)) {
    $sql .= " AND client_type = '$filter_type'";
}

// Add sorting
$sql .= " ORDER BY id DESC";

$result = $conn->query($sql);

$tableRows = '';
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $status_class = $row["status"] === "New Client" ? "bg-green-100 text-green-800" : "bg-blue-100 text-blue-800";
        $type_class = $row["client_type"] === "Noblehome" ? "bg-purple-100 text-purple-800" : "bg-indigo-100 text-indigo-800";
        
        $tableRows .= '
            <tr class="hover:bg-gray-50 border-b border-gray-200 transition duration-150">
                <td class="py-4 px-6">' . htmlspecialchars($row["clientname"]) . '</td>
                <td class="py-4 px-6">
                    <span class="px-3 py-1 rounded-full text-xs font-medium ' . $status_class . '">
                        ' . htmlspecialchars($row["status"]) . '
                    </span>
                </td>
                <td class="py-4 px-6 font-mono text-sm">' . htmlspecialchars($row["reference_number"]) . '</td>
                <td class="py-4 px-6">' . htmlspecialchars($row["nameproject"]) . '</td>
                <td class="py-4 px-6">
                    <span class="px-3 py-1 rounded-full text-xs font-medium ' . $type_class . '">
                        ' . htmlspecialchars($row["client_type"]) . '
                    </span>
                </td>
                <td class="py-4 px-6 text-center">
                    <form method="post" onsubmit="return confirm(\'Are you sure you want to delete this client?\');">
                        <input type="hidden" name="delete_id" value="' . $row["id"] . '" />
                        <button type="submit" class="text-red-600 hover:text-red-800 hover:underline focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </td>
            </tr>';
    }
} else {
    $tableRows = '<tr><td colspan="6" class="py-4 px-6 text-center text-gray-500">No clients found</td></tr>';
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .animate-fade {
            animation: fadeOut 5s forwards;
        }
        @keyframes fadeOut {
            0% { opacity: 1; }
            70% { opacity: 1; }
            100% { opacity: 0; }
        }
        .card-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">
    <div class="container mx-auto p-4 md:p-6">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Client Management System</h1>
            <p class="text-gray-600">Manage your clients and projects efficiently</p>
        </div>

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div id="success-alert" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow animate-fade">
                <div class="flex items-center">
                    <div class="py-1">
                        <svg class="h-6 w-6 text-green-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">Success!</p>
                        <p class="text-sm"><?php echo $_SESSION['success_message']; ?></p>
                    </div>
                </div>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div id="error-alert" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow animate-fade">
                <div class="flex items-center">
                    <div class="py-1">
                        <svg class="h-6 w-6 text-red-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">Error!</p>
                        <p class="text-sm"><?php echo $_SESSION['error_message']; ?></p>
                    </div>
                </div>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Add Client Card -->
        <div class="bg-white rounded-xl shadow-xl card-shadow p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-user-plus mr-3 text-blue-600"></i>
                    Add New Client
                </h2>
                <div class="flex space-x-2">
                    <form method="post">
                        <button type="submit" name="export" class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white font-medium rounded-lg shadow hover:bg-green-700 transition duration-300 ease-in-out transform hover:scale-105">
                            <i class="fas fa-file-export"></i>
                            Export CSV
                        </button>
                    </form>
                </div>
            </div>

            <!-- Form to add new client -->
            <form method="post" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label for="clientname" class="block text-sm font-medium text-gray-700 mb-1">Client Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" name="clientname" id="clientname" placeholder="Enter client name"
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                required>
                        </div>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-tag text-gray-400"></i>
                            </div>
                            <select name="status" id="status"
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                required>
                                <option value="New Client">New Client</option>
                                <option value="Old Client">Old Client</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="nameproject" class="block text-sm font-medium text-gray-700 mb-1">Project Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-project-diagram text-gray-400"></i>
                            </div>
                            <input type="text" name="nameproject" id="nameproject" placeholder="Enter project name"
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                required>
                        </div>
                    </div>
                    <div>
                        <label for="client_type" class="block text-sm font-medium text-gray-700 mb-1">Client Type</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-building text-gray-400"></i>
                            </div>
                            <select name="client_type" id="client_type"
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                required>
                                <option value="Noblehome">Noblehome</option>
                                <option value="Realiving">Realiving</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center mt-6">
                    <button type="submit"
                        class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 transition duration-300 ease-in-out transform hover:scale-105">
                        <i class="fas fa-plus"></i> Add Client
                    </button>
                </div>
            </form>
        </div>

        <!-- Search and Filter Bar -->
        <div class="bg-white rounded-xl shadow-md p-4 mb-6">
            <form method="get" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search clients..."
                        class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-filter text-gray-400"></i>
                    </div>
                    <select name="filter_status" class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="" <?php echo $filter_status === '' ? 'selected' : ''; ?>>All Statuses</option>
                        <option value="New Client" <?php echo $filter_status === 'New Client' ? 'selected' : ''; ?>>New Client</option>
                        <option value="Old Client" <?php echo $filter_status === 'Old Client' ? 'selected' : ''; ?>>Old Client</option>
                    </select>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-building text-gray-400"></i>
                    </div>
                    <select name="filter_type" class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="" <?php echo $filter_type === '' ? 'selected' : ''; ?>>All Types</option>
                        <option value="Noblehome" <?php echo $filter_type === 'Noblehome' ? 'selected' : ''; ?>>Noblehome</option>
                        <option value="Realiving" <?php echo $filter_type === 'Realiving' ? 'selected' : ''; ?>>Realiving</option>
                    </select>
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 flex items-center justify-center">
                        <i class="fas fa-search mr-2"></i> Search
                    </button>
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-150 flex items-center justify-center">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Client Table -->
        <div class="bg-white rounded-xl shadow-xl card-shadow overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-users mr-3 text-blue-600"></i>
                    Client Directory
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference Number</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php echo $tableRows; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="mt-8 text-center text-gray-600 text-sm">
            <p>&copy; <?php echo date('Y'); ?> Client Management System. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('#success-alert, #error-alert');
            alerts.forEach(alert => {
                if (alert) {
                    alert.style.display = 'none';
                }
            });
        }, 5000);
    </script>
</body>

</html>