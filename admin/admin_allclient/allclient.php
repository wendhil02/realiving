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
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "<p class='text-red-500 text-center'>Error: " . $stmt->error . "</p>";
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

// Load clients
$sql = "SELECT * FROM user_info";
$result = $conn->query($sql);

$tableRows = '';
while ($row = $result->fetch_assoc()) {
    $status_display = $row["status"] === "New " ? "Old Client" : $row["status"];
    $tableRows .= '
        <tr class="hover:bg-gray-50 transition">
            <td class="py-3 px-6">' . $row["clientname"] . '</td>
            <td class="py-3 px-6">' . $status_display . '</td>
            <td class="py-3 px-6">' . $row["reference_number"] . '</td>
            <td class="py-3 px-6">' . $row["nameproject"] . '</td>
            <td class="py-3 px-6">' . $row["client_type"] . '</td>
            <td class="py-3 px-6 text-center">
                <form method="post">
                    <input type="hidden" name="delete_id" value="' . $row["id"] . '" />
                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                </form>
            </td>
        </tr>';
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-300">
    <div class="container mx-auto p-6">
    <div class="bg-white p-8 rounded-2xl shadow-xl mb-8">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Client Management</h2>

    <!-- Form to add new client -->
    <form method="post" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <label for="clientname" class="block text-sm font-medium text-gray-700 mb-1">Client Name</label>
                <input type="text" name="clientname" id="clientname"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                    required>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                    required>
                    <option value="New Client">New Client</option>
                    <option value="Old Client">Old Client</option>
                </select>
            </div>
            <div>
                <label for="nameproject" class="block text-sm font-medium text-gray-700 mb-1">Project Name</label>
                <input type="text" name="nameproject" id="nameproject"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                    required>
            </div>
            <div>
                <label for="client_type" class="block text-sm font-medium text-gray-700 mb-1">Client Type</label>
                <select name="client_type" id="client_type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                    required>
                    <option value="Noblehome">Noblehome</option>
                    <option value="Realiving">Realiving</option>
                </select>
            </div>
        </div>

        <div class="flex justify-center mt-4">
            <button type="submit"
                class="flex items-center gap-2 px-6 py-2 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 transition">
                <i class="ri-add-line text-lg"></i> Add Client
            </button>
        </div>
    </form>

    <!-- Export Button -->
    <div class="flex justify-center mt-6">
        <form method="post">
            <button type="submit" name="export"
                class="flex items-center gap-2 px-6 py-2 bg-green-600 text-white font-medium rounded-lg shadow hover:bg-green-700 transition">
                <i class="ri-export-2-line text-lg"></i> Export Clients
            </button>
        </form>
    </div>
</div>


        <!-- Client Table -->
        <div class="overflow-x-auto bg-white p-6 rounded-lg shadow-lg">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="text-left bg-gray-100">
                        <th class="py-3 px-6 font-semibold text-sm text-gray-600">Client Name</th>
                        <th class="py-3 px-6 font-semibold text-sm text-gray-600">Status</th>
                        <th class="py-3 px-6 font-semibold text-sm text-gray-600">Reference Number</th>
                        <th class="py-3 px-6 font-semibold text-sm text-gray-600">Project Name</th>
                        <th class="py-3 px-6 font-semibold text-sm text-gray-600">Client Type</th>
                        <th class="py-3 px-6 font-semibold text-sm text-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $tableRows; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
