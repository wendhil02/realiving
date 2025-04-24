<?php
session_start();
include '../connection/connection.php';

// Update client status based on the creation date (1 year old)
$sql = "UPDATE user_info 
        SET status = 'Old Client' 
        WHERE created_at <= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) AND status != 'Old Client'";
$conn->query($sql);

// Handle export request
if (isset($_POST['export'])) {
    // SQL query to fetch clientname, reference_number, and nameproject
    $sql = "SELECT clientname, reference_number, nameproject FROM user_info";
    $result = $conn->query($sql);

    // Open output buffer to write data to
    $output = fopen("php://output", "w");

    // Set headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="clientRerf.csv"');

    // Column headings for the CSV file
    fputcsv($output, ['Client Name', 'Reference Number', 'Project Name']);

    // Fetch data from the result set and write to CSV
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }
    }

    // Close the output buffer
    fclose($output);
    exit();
}

include 'design/siddebarmain.php';
// SQL query to fetch data from the 'user_info' table
$sql = "SELECT * FROM user_info";  // You can modify this query to select specific fields if needed
$result = $conn->query($sql);

// Start the table output
$tableRows = '';

if ($result->num_rows > 0) {
    // Output the data of each row
    while ($row = $result->fetch_assoc()) {
        // Check if the client is new or old based on the created_at date or another field
        $status_display = $row["status"];
        if ($row["status"] == "New") {
            $status_display = "Old Client"; // Change "New" status to "Old Client"
        }

        $tableRows .= '<tr class="border-b hover:bg-gray-50">
                           <td class="py-3 px-6 text-sm text-gray-800">' . $row["id"] . '</td>
                           <td class="py-3 px-6 text-sm text-gray-800">' . $row["clientname"] . '</td>
                           <td class="py-3 px-6 text-sm text-gray-800">' . $status_display . '</td>
                           <td class="py-3 px-6 text-sm text-gray-800">' . $row["reference_number"] . '</td>
                           <td class="py-3 px-6 text-sm text-gray-800">' . $row["nameproject"] . '</td>
                       </tr>';
    }
} else {
    $tableRows = '<tr><td colspan="5" class="py-3 px-6 text-center text-sm text-gray-600">No results found.</td></tr>';
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Info Table</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex h-screen bg-gray-50">

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-semibold text-center mb-6">Client Information</h1>
        
        <!-- Export button -->
        <form method="post">
            <button type="submit" name="export" class="bg-blue-500 text-white py-2 px-4 rounded mb-6">Export to Excel</button>
        </form>
        
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">ID</th>
                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Client Name</th>
                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Status</th>
                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Reference Number</th>
                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Project Name</th>
                </tr>
            </thead>
            <tbody>
                <!-- Output the table rows dynamically -->
                <?php echo $tableRows; ?>
            </tbody>
        </table>
    </main>

</body>
</html>
