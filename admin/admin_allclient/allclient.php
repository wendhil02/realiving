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

// Handle update request
if (isset($_POST['update_id'])) {
    $updateId = $_POST['update_id'];
    $clientname = $_POST['edit_clientname'];
    $status = $_POST['edit_status'];
    $nameproject = $_POST['edit_nameproject'];
    $client_type = $_POST['edit_client_type'];
    $client_class = $_POST['edit_client_class'];
    $updateTime = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("UPDATE user_info SET clientname=?, status=?, nameproject=?, updatestatus=?, update_time=?, client_type=?, client_class=? WHERE id=?");
    $stmt->bind_param("sssssssi", $clientname, $status, $nameproject, $status, $updateTime, $client_type, $client_class, $updateId);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Client updated successfully!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['error_message'] = "Error updating client: " . $stmt->error;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    
    $stmt->close();
}

// Automatically update old clients
$conn->query("UPDATE user_info SET status = 'Old Client' 
    WHERE created_at <= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) AND status != 'Old Client'");

// Handle new client form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['clientname'], $_POST['status'], $_POST['nameproject'], $_POST['client_type'], $_POST['client_class']) && !isset($_POST['export']) && !isset($_POST['update_id'])) {
    $clientname = $_POST['clientname'];
    $status = $_POST['status'];
    $nameproject = $_POST['nameproject'];
    $client_type = $_POST['client_type'];
    $client_class = $_POST['client_class']; // NEW
    $updateTime = date('Y-m-d H:i:s');

    $reference_number = "REF" . date("YmdHis") . strtoupper(substr(md5(uniqid()), 0, 4));

    $stmt = $conn->prepare("INSERT INTO user_info (clientname, status, nameproject, updatestatus, update_time, reference_number, client_type, client_class) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $clientname, $status, $nameproject, $status, $updateTime, $reference_number, $client_type, $client_class);

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
                    <span class="px-3 py-1 rounded-lg text-xs font-medium text-black">
                        ' . htmlspecialchars($row["client_class"]) . '
                    </span>
                </td>
                <td class="py-4 px-6 text-center">
                    <div class="flex justify-center space-x-3">
                        <button type="button" onclick="openEditModal(' . $row["id"] . ', \'' . htmlspecialchars($row["clientname"], ENT_QUOTES) . '\', \'' . htmlspecialchars($row["status"], ENT_QUOTES) . '\', \'' . htmlspecialchars($row["nameproject"], ENT_QUOTES) . '\', \'' . htmlspecialchars($row["client_type"], ENT_QUOTES) . '\', \'' . htmlspecialchars($row["client_class"], ENT_QUOTES) . '\')" class="text-blue-600 hover:text-blue-800 hover:underline focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <form method="post" onsubmit="return confirm(\'Are you sure you want to delete this client?\');" class="inline">
                            <input type="hidden" name="delete_id" value="' . $row["id"] . '" />
                            <button type="submit" class="text-red-600 hover:text-red-800 hover:underline focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>';
    }
} else {
    $tableRows = '<tr><td colspan="7" class="py-4 px-6 text-center text-gray-500">No clients found</td></tr>';
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
            0% {
                opacity: 1;
            }

            70% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        .card-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: modalFadeIn 0.3s;
        }
        
        @keyframes modalFadeIn {
            from {opacity: 0; transform: translateY(-20px);}
            to {opacity: 1; transform: translateY(0);}
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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

                <div>
                    <label for="client_class" class="block mb-1 text-sm font-medium text-gray-700">Client Classification</label>
                    <select name="client_class" id="client_class" class="w-[200px] p-2 border rounded">
                        <option value="VIP">VIP</option>
                        <option value="Regular">Regular</option>
                        <option value="Walk-in">Walk-in</option>
                        <option value="Returning">Returning</option>
                    </select>

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
                        <option value="" <?php echo $filter_status === '' ? 'selected' : ''; ?>>All Status</option>
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
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client Class</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php echo $tableRows; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Modal -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Edit Client</h2>
                    <span class="close">&times;</span>
                </div>
                <form method="post" id="editForm" class="space-y-4">
                    <input type="hidden" name="update_id" id="edit_id">
                    
                    <div>
                        <label for="edit_clientname" class="block text-sm font-medium text-gray-700 mb-1">Client Name</label>
                        <input type="text" name="edit_clientname" id="edit_clientname" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>
                    
                    <div>
                        <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="edit_status" id="edit_status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="New Client">New Client</option>
                            <option value="Old Client">Old Client</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="edit_nameproject" class="block text-sm font-medium text-gray-700 mb-1">Project Name</label>
                        <input type="text" name="edit_nameproject" id="edit_nameproject"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>
                    
                    <div>
                        <label for="edit_client_type" class="block text-sm font-medium text-gray-700 mb-1">Client Type</label>
                        <select name="edit_client_type" id="edit_client_type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="Noblehome">Noblehome</option>
                            <option value="Realiving">Realiving</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="edit_client_class" class="block text-sm font-medium text-gray-700 mb-1">Client Classification</label>
                        <select name="edit_client_class" id="edit_client_class"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="VIP">VIP</option>
                            <option value="Regular">Regular</option>
                            <option value="Walk-in">Walk-in</option>
                            <option value="Returning">Returning</option>
                        </select>
                    </div>
                    
                    <div class="flex justify-end mt-6">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-800 font-medium rounded-lg mr-2">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 transition duration-150">
                            <i class="fas fa-save mr-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-gray-600 text-sm">
            <p>&copy; <?php echo date('Y'); ?> Client Management System. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Modal functions
        var modal = document.getElementById("editModal");
        var span = document.getElementsByClassName("close")[0];
        
        function openEditModal(id, clientname, status, nameproject, client_type, client_class) {
            document.getElementById("edit_id").value = id;
            document.getElementById("edit_clientname").value = clientname;
            document.getElementById("edit_status").value = status;
            document.getElementById("edit_nameproject").value = nameproject;
            document.getElementById("edit_client_type").value = client_type;
            document.getElementById("edit_client_class").value = client_class;
            modal.style.display = "block";
        }
        
        function closeModal() {
            modal.style.display = "none";
        }
        
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            closeModal();
        }
        
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    
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