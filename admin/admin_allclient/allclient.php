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
    // Fields including email
    $contact = $_POST['edit_contact'];
    $email = $_POST['edit_email'];
    $country = $_POST['edit_country'];
    $address = $_POST['edit_address'];
    $gender = $_POST['edit_gender'];
    $updateTime = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("UPDATE user_info SET clientname=?, status=?, nameproject=?, updatestatus=?, update_time=?, client_type=?, client_class=?, contact=?, email=?, country=?, address=?, gender=? WHERE id=?");
    $stmt->bind_param("ssssssssssssi", $clientname, $status, $nameproject, $status, $updateTime, $client_type, $client_class, $contact, $email, $country, $address, $gender, $updateId);
    
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
    $client_class = $_POST['client_class'];
    // Fields including email
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $country = $_POST['country'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $updateTime = date('Y-m-d H:i:s');

    $reference_number = "REF" . date("YmdHis") . strtoupper(substr(md5(uniqid()), 0, 4));

    $stmt = $conn->prepare("INSERT INTO user_info (clientname, status, nameproject, updatestatus, update_time, reference_number, client_type, client_class, contact, email, country, address, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssss", $clientname, $status, $nameproject, $status, $updateTime, $reference_number, $client_type, $client_class, $contact, $email, $country, $address, $gender);

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
    $sql = "SELECT clientname, reference_number, nameproject, client_type, contact, email, country, address, gender FROM user_info";
    $result = $conn->query($sql);

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="clientRef.csv"');
    $output = fopen("php://output", "w");
    fputcsv($output, ['Client Name', 'Reference Number', 'Project Name', 'Client Type', 'Contact', 'Email', 'Country', 'Address', 'Gender']);

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
    $sql .= " AND (clientname LIKE '$search' OR reference_number LIKE '$search' OR nameproject LIKE '$search' OR email LIKE '$search')";
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
                <td class="py-4 px-6 ">
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
                <td class="py-4 px-6">' . htmlspecialchars($row["contact"] ?? '') . '</td>
                <td class="py-4 px-6">' . htmlspecialchars($row["email"] ?? '') . '</td>
                <td class="py-4 px-6 max-w-xs overflow-x-auto truncate" title="' . htmlspecialchars($row["address"] ?? '') . '">' . htmlspecialchars($row["address"] ?? '') . '</td>
                <td class="py-4 px-6 text-center">
                    <div class="flex justify-center space-x-3">
                        <button type="button" onclick="openEditModal(' . $row["id"] . ', \'' . htmlspecialchars($row["clientname"], ENT_QUOTES) . '\', \'' . htmlspecialchars($row["status"], ENT_QUOTES) . '\', \'' . htmlspecialchars($row["nameproject"], ENT_QUOTES) . '\', \'' . htmlspecialchars($row["client_type"], ENT_QUOTES) . '\', \'' . htmlspecialchars($row["client_class"], ENT_QUOTES) . '\', \'' . htmlspecialchars($row["contact"] ?? '', ENT_QUOTES) . '\', \'' . htmlspecialchars($row["email"] ?? '', ENT_QUOTES) . '\', \'' . htmlspecialchars($row["country"] ?? '', ENT_QUOTES) . '\', \'' . htmlspecialchars($row["address"] ?? '', ENT_QUOTES) . '\', \'' . htmlspecialchars($row["gender"] ?? '', ENT_QUOTES) . '\')" class="text-blue-600 hover:text-blue-800 hover:underline focus:outline-none">
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
    $tableRows = '<tr><td colspan="12" class="py-4 px-6 text-center text-gray-500">No clients found</td></tr>';
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Management System</title>
     <link rel="icon" type="image/png" sizes="32x32" href="../../logo/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   <link rel="stylesheet" href="src/allclient.css">
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
                    <!-- First row -->
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
                
                <!-- Second row - Including Email -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label for="contact" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-phone text-gray-400"></i>
                            </div>
                            <input type="text" name="contact" id="contact" placeholder="Enter contact number"
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                required>
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" name="email" id="email" placeholder="Enter email address"
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                required>
                        </div>
                    </div>
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-globe text-gray-400"></i>
                            </div>
                            <input type="text" name="country" id="country" placeholder="Enter country"
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                required>
                        </div>
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-venus-mars text-gray-400"></i>
                            </div>
                            <select name="gender" id="gender"
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                                <option value="Prefer not to say">Prefer not to say</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Third row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="client_class" class="block text-sm font-medium text-gray-700 mb-1">Client Classification</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-award text-gray-400"></i>
                            </div>
                            <select name="client_class" id="client_class"
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                required>
                                <option value="VIP">VIP</option>
                                <option value="Regular">Regular</option>
                                <option value="Walk-in">Walk-in</option>
                                <option value="Returning">Returning</option>
                            </select>
                        </div>
                    </div>
                    <!-- Address field -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <div class="relative">
                            <div class="absolute top-3 left-0 pl-3 flex items-start pointer-events-none">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                            </div>
                            <textarea name="address" id="address" placeholder="Enter full address" rows="3"
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                required></textarea>
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

        <!-- Search and Filter -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <form method="get" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search clients, emails..." class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="filter_status" class="block text-sm font-medium text-gray-700 mb-1">Filter by Status</label>
                    <select id="filter_status" name="filter_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="New Client" <?php echo $filter_status === 'New Client' ? 'selected' : ''; ?>>New Client</option>
                        <option value="Old Client" <?php echo $filter_status === 'Old Client' ? 'selected' : ''; ?>>Old Client</option>
                    </select>
                </div>
                <div>
                    <label for="filter_type" class="block text-sm font-medium text-gray-700 mb-1">Filter by Type</label>
                    <select id="filter_type" name="filter_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Types</option>
                        <option value="Noblehome" <?php echo $filter_type === 'Noblehome' ? 'selected' : ''; ?>>Noblehome</option>
                        <option value="Realiving" <?php echo $filter_type === 'Realiving' ? 'selected' : ''; ?>>Realiving</option>
                    </select>
                </div>
                <div class="md:col-span-3 flex justify-center">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 transition duration-300 ease-in-out">
                        <i class="fas fa-search mr-2"></i> Search
                    </button>
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="ml-4 px-6 py-2 bg-gray-500 text-white font-medium rounded-lg shadow hover:bg-gray-600 transition duration-300 ease-in-out">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Client table -->
        <div class="bg-white rounded-xl shadow-xl p-6 overflow-hidden">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-list mr-3 text-blue-600"></i>
                Client List
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                        <tr>
                            <th class="py-4 px-6 text-left text-sm font-semibold uppercase tracking-wider">Client Name</th>
                            <th class="py-4 px-6 text-left text-sm font-semibold uppercase tracking-wider">Status</th>
                            <th class="py-4 px-6 text-left text-sm font-semibold uppercase tracking-wider">Reference #</th>
                            <th class="py-4 px-6 text-left text-sm font-semibold uppercase tracking-wider">Project</th>
                            <th class="py-4 px-6 text-left text-sm font-semibold uppercase tracking-wider">Type</th>
                            <th class="py-4 px-6 text-center text-sm font-semibold uppercase tracking-wider">Class</th>
                            <th class="py-4 px-6 text-left text-sm font-semibold uppercase tracking-wider">Contact</th>
                            <th class="py-4 px-6 text-left text-sm font-semibold uppercase tracking-wider">Email</th>
                            <th class="py-4 px-6 text-left text-sm font-semibold uppercase tracking-wider">Address</th>
                            <th class="py-4 px-6 text-center text-sm font-semibold uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php echo $tableRows; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6 rounded-t-xl">
                <h3 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-edit mr-3"></i>
                    Edit Client Information
                </h3>
            </div>
            
            <form method="post" class="p-6 space-y-6">
                <input type="hidden" id="edit_id" name="update_id">
                
                <!-- First Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label for="edit_clientname" class="block text-sm font-medium text-gray-700 mb-1">Client Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" name="edit_clientname" id="edit_clientname" 
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                required>
                        </div>
                    </div>
                    <div>
                        <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-tag text-gray-400"></i>
                            </div>
                            <select name="edit_status" id="edit_status"
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                required>
                                <option value="New Client">New Client</option>
                                <option value="Old Client">Old Client</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="edit_nameproject" class="block text-sm font-medium text-gray-700 mb-1">Project Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-project-diagram text-gray-400"></i>
                            </div>
                            <input type="text" name="edit_nameproject" id="edit_nameproject"
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                required>
                        </div>
                    </div>
                    <div>
                        <label for="edit_client_type" class="block text-sm font-medium text-gray-700 mb-1">Client Type</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-building text-gray-400"></i>
                            </div>
                            <select name="edit_client_type" id="edit_client_type"
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                required>
                                <option value="Noblehome">Noblehome</option>
                                <option value="Realiving">Realiving</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Second Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label for="edit_contact" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-phone text-gray-400"></i>
                            </div>
                            <input type="text" name="edit_contact" id="edit_contact"
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                required>
                        </div>
                    </div>
                    <div>
                        <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" name="edit_email" id="edit_email"
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                required>
                        </div>
                    </div>
                    <div>
                        <label for="edit_country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-globe text-gray-400"></i>
                            </div>
                            <input type="text" name="edit_country" id="edit_country"
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                required>
                        </div>
                    </div>
                    <div>
                        <label for="edit_gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-venus-mars text-gray-400"></i>
                            </div>
                            <select name="edit_gender" id="edit_gender"
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                                <option value="Prefer not to say">Prefer not to say</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Third Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="edit_client_class" class="block text-sm font-medium text-gray-700 mb-1">Client Classification</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-award text-gray-400"></i>
                            </div>
                            <select name="edit_client_class" id="edit_client_class"
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                required>
                                <option value="VIP">VIP</option>
                                <option value="Regular">Regular</option>
                                <option value="Walk-in">Walk-in</option>
                                <option value="Returning">Returning</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="edit_address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <div class="relative">
                            <div class="absolute top-3 left-0 pl-3 flex items-start pointer-events-none">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                            </div>
                            <textarea name="edit_address" id="edit_address" rows="3"
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                required></textarea>
                        </div>
                    </div>
                </div>

                <!-- Modal Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeEditModal()"
                        class="px-6 py-2 bg-gray-500 text-white font-medium rounded-lg shadow hover:bg-gray-600 transition duration-300 ease-in-out">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 transition duration-300 ease-in-out">
                        <i class="fas fa-save mr-2"></i> Update Client
                    </button>
                </div>
            </form>
        </div>
    </div>
<script src="js/allclient.js"></script>
</body>
</html>