<?php
session_start();
include '../connection/connection.php';

// Handle delete request with prepared statement
if (isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM user_info WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    $stmt->execute();
    $stmt->close();
}
include 'design/mainbody.php';
// Update client status (using a prepared statement is optional here for efficiency)
$conn->query("UPDATE user_info SET status = 'Old Client' 
    WHERE created_at <= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) AND status != 'Old Client'");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['clientname'], $_POST['status'], $_POST['nameproject'])) {
    $clientname = $_POST['clientname'];
    $status = $_POST['status'];
    $nameproject = $_POST['nameproject'];
    $updateTime = date('Y-m-d H:i:s');

    // ✅ Generate unique reference number
    $reference_number = "REF" . date("YmdHis") . strtoupper(substr(md5(uniqid()), 0, 4));

    // Insert into database (with reference_number)
    $stmt = $conn->prepare("INSERT INTO user_info (clientname, status, nameproject, updatestatus, update_time, reference_number) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $clientname, $status, $nameproject, $status, $updateTime, $reference_number);

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

$sql = "SELECT * FROM user_info";
$result = $conn->query($sql);

$tableRows = '';
while ($row = $result->fetch_assoc()) {
    $status_display = $row["status"] === "New" ? "Old Client" : $row["status"];
    $tableRows .= '
        <tr class="hover:bg-gray-50 transition">

            <td class="py-3 px-6">' . $row["clientname"] . '</td>
            <td class="py-3 px-6">' . $status_display . '</td>
            <td class="py-3 px-6">' . $row["reference_number"] . '</td>
            <td class="py-3 px-6">' . $row["nameproject"] . '</td>
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
    <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
    <h2 class="text-2xl font-semibold text-center mb-4">Client Management</h2>

    <!-- Form to add new client -->
    <form method="post" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="col-span-1">
                <label for="clientname" class="block text-sm font-medium text-gray-700">Client Name</label>
                <input type="text" name="clientname" id="clientname" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="col-span-1">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
                    <option value="New">New</option>
                    <option value="Old Client">Old Client</option>
                </select>
            </div>
            <div class="col-span-1">
                <label for="nameproject" class="block text-sm font-medium text-gray-700">Project Name</label>
                <input type="text" name="nameproject" id="nameproject" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 flex items-center justify-center">
                <i class="ri-add-line mr-2"></i> Add Client
            </button>
        </div>
    </form>

    <!-- Export Button -->
    <div class="text-center mt-4">
        <form method="post">
            <button type="submit" name="export" class="px-6 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 flex items-center justify-center">
                <i class="ri-export-2-line mr-2"></i> Export Clients
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
                        <th class="py-3 px-6 font-semibold text-sm text-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- PHP will generate table rows here -->
                    <?php echo $tableRows; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>