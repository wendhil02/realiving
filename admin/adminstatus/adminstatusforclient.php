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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-r from-blue-50 to-blue-100 min-h-screen items-center justify-center ">

    <div class="w-full max-w-md md:max-w-lg lg:max-w-xl mx-auto p-4 sm:p-6">
        <div class="bg-white shadow-xl rounded-2xl p-5 sm:p-8 border border-blue-200">
            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-center text-blue-700 mb-6">Admin Status</h1>

            <!-- Admin Details -->
            <div class="space-y-4 mb-6">
                <div>
                    <p class="text-gray-700 text-xs sm:text-sm font-medium">Email</p>
                    <p class="text-base sm:text-lg font-semibold text-gray-900 break-all"><?= htmlspecialchars($admin['email']) ?></p>
                </div>

                <div>
                    <p class="text-gray-700 text-xs sm:text-sm font-medium">Role</p>
                    <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs sm:text-sm font-semibold shadow-sm">
                        <?= htmlspecialchars($admin['role']) ?>
                    </span>
                </div>

                <div>
                    <p class="text-gray-700 text-xs sm:text-sm font-medium">Client Status</p>
                    <span class="
                        inline-block px-3 py-1 rounded-full text-xs sm:text-sm font-semibold shadow-sm
                        <?= $admin['client_status'] === 'No clients' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' ?>
                    ">
                        <?= htmlspecialchars($admin['client_status']) ?>
                    </span>
                </div>
            </div>

            <!-- Form to Update Status -->
            <form action="adminstatusforclient.php" method="POST" class="space-y-4">
                <input type="hidden" name="admin_id" value="<?= $admin['id'] ?>">

                <label for="client_status" class="block text-sm font-medium text-gray-700">Update Status</label>
                <select id="client_status" name="client_status" required
                    class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    <option value="No clients" <?= $admin['client_status'] === 'No clients' ? 'selected' : '' ?>>No clients</option>
                    <option value="Has clients" <?= $admin['client_status'] === 'Has clients' ? 'selected' : '' ?>>Has clients</option>
                </select>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition shadow-md">
                    Update Status
                </button>
            </form>
        </div>
    </div>

</body>

</html>
