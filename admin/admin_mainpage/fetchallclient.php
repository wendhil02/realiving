<?php
include '../connection/connection.php';

// SQL query to fetch data from the 'user_info' table
$sql = "SELECT * FROM user_info";  // Assuming reference_number is in the 'user_info' table

// Execute the query
$result = $conn->query($sql);

// Start the table output
$tableRows = '';

if ($result->num_rows > 0) {
    // Output the data of each row
    while ($row = $result->fetch_assoc()) {
        $tableRows .= '<tr class="border-b hover:bg-gray-50">
                           <td class="py-3 px-6 text-sm text-gray-800">' . $row["id"] . '</td>
                           <td class="py-3 px-6 text-sm text-gray-800">' . $row["clientname"] . '</td>
                           <td class="py-3 px-6 text-sm text-gray-800">' . $row["status"] . '</td>
                           <td class="py-3 px-6 text-sm text-gray-800">' . $row["reference_number"] . '</td>
                       </tr>';
    }
} else {
    $tableRows = '<tr><td colspan="4" class="py-3 px-6 text-center text-sm text-gray-600">No results found.</td></tr>';
}

// Close the connection
$conn->close();

// Pass the table rows back for use in the HTML
echo $tableRows;
?>