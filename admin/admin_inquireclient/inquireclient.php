<?php
include '../design/mainbody.php';
include '../../connection/connection.php';
session_start();

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inquiries Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto p-6 flex flex-col lg:flex-row gap-6">
        <!-- Left: Inquiries Section -->
        <div class="w-full lg:w-2/3 space-y-6">
            <h1 class="text-3xl font-bold text-center mb-6 text-blue-800">📥 New Inquiries</h1>

            <!-- Filter Form -->
            <form method="GET" action="" class="mb-6 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <label for="status" class="text-sm font-medium text-gray-700">Filter by Status:</label>
                    <select name="status" id="status" class="border rounded p-2 focus:ring focus:ring-blue-200">
                        <option value="">All</option>
                        <option value="new" <?= isset($_GET['status']) && $_GET['status'] == 'new' ? 'selected' : '' ?>>New Inquiries</option>
                        <option value="sent" <?= isset($_GET['status']) && $_GET['status'] == 'sent' ? 'selected' : '' ?>>Sent to Admin</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow-sm">
                    Filter
                </button>
            </form>

            <?php while ($row = $inquiries->fetch_assoc()): ?>
                <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
                    <p class="text-gray-700"><strong>Name:</strong> <?= htmlspecialchars($row['full_name']) ?></p>
                    <p class="text-gray-700"><strong>Phone:</strong> <?= htmlspecialchars($row['phone_number']) ?></p>
                    <p class="text-gray-700"><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>

                    <!-- Conditional styling for client_type -->
                    <p class="text-gray-700">
                        <strong>Client Type:</strong>
                        <?php
                        $client_type = htmlspecialchars($row['client_type'] ?? 'N/A');
                        $client_type_class = '';

                        // Apply different colors based on client_type
                        if (strtolower($client_type) == 'noblehome') {
                            $client_type_class = 'bg-orange-600 text-white';
                        } elseif (strtolower($client_type) == 'realiving') {
                            $client_type_class = 'bg-yellow-600 text-white';
                        }
                        ?>
                        <span class="px-1 py-0.5 rounded <?= $client_type_class ?>">
                            <?= $client_type ?>
                        </span>
                    </p>


                    <p class="text-gray-600 text-sm italic mt-1">
                        <strong>Submitted:</strong>
                        <?= date('F j, Y \a\t g:i A', strtotime($row['created_at'])) ?>
                    </p>

                    <?php if ($row['sent_to_admin']): ?>
                        <div class="mt-4 text-green-600 font-medium">Sent to Admin</div>
                    <?php else: ?>
                        <form action="send_inquiry.php" method="POST" class="mt-4">
                            <input type="hidden" name="inquiry_id" value="<?= $row['id'] ?>">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select the admin to send to:</label>
                            <select name="recipient_email" required class="border rounded w-full p-2 focus:ring focus:ring-blue-200">
                                <option value="">-- Select Admin --</option>
                                <?php foreach ($admins as $admin): ?>
                                    <option value="<?= htmlspecialchars($admin['email']) ?>">
                                        <?= htmlspecialchars($admin['email']) ?> - <?= htmlspecialchars($admin['client_status']) ?> (<?= htmlspecialchars($admin['role']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow-sm">
                                Send
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>

        </div>

        <!-- Right: Admins Sidebar -->
        <div class="w-full lg:w-1/3 sticky top-0">
            <div class="bg-white border border-gray-200 rounded-lg shadow p-5 h-fit">
                <h2 class="text-xl font-semibold text-blue-700 mb-4">Admins & Client Status</h2>
                <ul class="space-y-3">
                    <?php
                    // Re-execute the query for sidebar if $admins is exhausted
                    $admins_sidebar = $conn->query("SELECT id, email, client_status, role FROM account WHERE role LIKE 'admin%'");
                    while ($admin = $admins_sidebar->fetch_assoc()):
                    ?>
                        <li class="text-sm text-gray-800 border-b pb-2">
                            <span class="block font-medium"><?= htmlspecialchars($admin['email']) ?></span>
                            <span class="text-xs text-gray-600">Status: <?= htmlspecialchars($admin['client_status']) ?> | Role: <?= htmlspecialchars($admin['role']) ?></span>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>

</body>

</html>