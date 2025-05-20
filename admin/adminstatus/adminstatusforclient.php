<?php
include '../design/mainbody.php';
include '../../connection/connection.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../loginpage/index.php'); // Redirect to login page if not logged in
    exit;
}

// Get logged-in admin ID from session
$admin_id = $_SESSION['admin_id'];

// Handle form submission to update status
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_status = $_POST['client_status'];
    $update_query = $conn->prepare("UPDATE account SET client_status = ? WHERE id = ?");
    $update_query->bind_param("si", $new_status, $admin_id);
    $update_query->execute();

    // Refresh the admin data after updating
    $query = $conn->prepare("SELECT id, email, client_status, role FROM account WHERE id = ?");
    $query->bind_param("i", $admin_id);
    $query->execute();
    $result = $query->get_result();
    $admin = $result->fetch_assoc();
} else {
    // Fetch the logged-in admin data when the page loads for the first time
    $query = $conn->prepare("SELECT id, email, client_status, role FROM account WHERE id = ?");
    $query->bind_param("i", $admin_id);
    $query->execute();
    $result = $query->get_result();
    $admin = $result->fetch_assoc();
}


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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="src/adminstatusforclient.css">
</head>

<body class="animated-bg min-h-screen items-center justify-center">

    <div class="w-full max-w-md md:max-w-lg mx-auto mt-10 ">
        <div class="card bg-white shadow-xl rounded-2xl border border-blue-100 overflow-hidden ">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold">Admin Dashboard</h1>
                        <p class="text-blue-100 mt-1">Manage your client status</p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-full">
                        <i class="fas fa-user-shield text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 md:p-8">
                <!-- Admin Details -->
                <div class="space-y-6 mb-8">
                    <div class="flex items-center p-4 rounded-lg bg-blue-50 border border-blue-100">
                        <div class="flex-shrink-0 bg-blue-500 text-white p-3 rounded-full">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-blue-600 font-medium">Email Address</p>
                            <p class="text-lg font-semibold text-gray-800 break-all"><?= htmlspecialchars($admin['email']) ?></p>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 text-yellow-600 p-3 rounded-full">
                            <i class="fas fa-id-badge"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600 font-medium">Current Role</p>
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                <i class="fas fa-star-of-life mr-2 text-xs"></i>
                                <?= htmlspecialchars($admin['role']) ?>
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="flex-shrink-0 <?= $admin['client_status'] === 'Has clients' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' ?> p-3 rounded-full">
                            <i class="fas <?= $admin['client_status'] === 'Has clients' ? 'fa-users' : 'fa-user-slash' ?>"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600 font-medium">Client Status</p>
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold 
                                <?= $admin['client_status'] === 'Has clients' ? 'success-badge text-white' : 'danger-badge text-white' ?>">
                                <i class="fas <?= $admin['client_status'] === 'Has clients' ? 'fa-check-circle' : 'fa-times-circle' ?> mr-2"></i>
                                <?= htmlspecialchars($admin['client_status']) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="bg-white px-4 text-sm text-gray-500">Update Status</span>
                    </div>
                </div>

                <!-- Form to Update Status -->
                <form action="adminstatusforclient.php" method="POST" class="space-y-6">
                    <input type="hidden" name="admin_id" value="<?= $admin['id'] ?>">

                    <div>
                        <label for="client_status" class="block text-sm font-medium text-gray-700 mb-2">Client Availability Status</label>
                        <div class="relative">
                            <select id="client_status" name="client_status" required
                                class="form-select block w-full pl-4 pr-10 py-4 bg-gray-50 border border-gray-300 rounded-lg appearance-none focus:outline-none input-focus text-gray-700">
                                <option value="No clients" <?= $admin['client_status'] === 'No clients' ? 'selected' : '' ?>>No clients</option>
                                <option value="Has clients" <?= $admin['client_status'] === 'Has clients' ? 'selected' : '' ?>>Has clients</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i> Update Status
                    </button>
                </form>

                <!-- Footer info -->
                <div class="mt-8 text-center text-xs text-gray-500">
                    <p>Last updated: <?= date('F j, Y, g:i a') ?></p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
