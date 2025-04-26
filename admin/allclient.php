<?php
session_start();
include '../connection/connection.php';

// Handle delete request
if (isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];
    $conn->query("DELETE FROM user_info WHERE id = $deleteId");
}

// Update client status
$conn->query("UPDATE user_info SET status = 'Old Client' 
    WHERE created_at <= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) AND status != 'Old Client'");

// Export logic
if (isset($_POST['export'])) {
    $sql = "SELECT clientname, reference_number, nameproject FROM user_info";
    $result = $conn->query($sql);

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="clientRerf.csv"');
    $output = fopen("php://output", "w");
    fputcsv($output, ['Client Name', 'Reference Number', 'Project Name']);

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}

include 'design/siddebarmain.php';

$sql = "SELECT * FROM user_info";
$result = $conn->query($sql);

$tableRows = '';
while ($row = $result->fetch_assoc()) {
    $status_display = $row["status"] === "New" ? "Old Client" : $row["status"];
    $tableRows .= '
        <tr class="hover:bg-gray-50 transition">
            <td class="py-3 px-6">' . $row["id"] . '</td>
            <td class="py-3 px-6">' . $row["clientname"] . '</td>
            <td class="py-3 px-6">' . $status_display . '</td>
            <td class="py-3 px-6">' . $row["reference_number"] . '</td>
            <td class="py-3 px-6">' . $row["nameproject"] . '</td>
            <td class="py-3 px-6 text-center">
                <form onsubmit="openModal(' . $row["id"] . '); return false;">
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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex h-screen bg-gray-100">

    <main class="max-w-7xl mx-auto px-4 py-10">
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-gray-800">Client Records</h1>
            <p class="text-gray-600 mt-2">Manage client information, export data, and update statuses.</p>
        </div>

        <div class="flex justify-end mb-4">
            <form method="post">
                <button name="export" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 shadow">
                    Export to Excel
                </button>
            </form>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="min-w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="py-3 px-6">ID</th>
                        <th class="py-3 px-6">Client Name</th>
                        <th class="py-3 px-6"></th>
                        <th class="py-3 px-6">Reference Number</th>
                        <th class="py-3 px-6">Project Name</th>
                        <th class="py-3 px-6 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?= $tableRows ?: '<tr><td colspan="6" class="py-6 px-6 text-center text-gray-500">No records found.</td></tr>' ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Delete Confirmation</h2>
            <p class="text-gray-600 mb-4">Are you sure you want to delete this client?</p>
            <form method="post" id="deleteForm">
                <input type="hidden" name="delete_id" id="delete_id_modal">
                <div class="flex justify-end gap-3">
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Yes</button>
                    <button type="button" onclick="closeModal()" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">No</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById('delete_id_modal').value = id;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }
    </script>

</body>
</html>
