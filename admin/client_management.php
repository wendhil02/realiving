<?php
session_start();
include 'design/siddebarmain.php';
require '../connection/connection.php';

// Reset auto-increment if table is empty
$result = $conn->query("SELECT COUNT(*) AS count FROM user_info");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $conn->query("ALTER TABLE user_info AUTO_INCREMENT = 1");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Real Living Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex h-screen bg-gray-50">
    <div class="flex-1 flex flex-col items-start justify-center px-6">
        <main class="w-full max-w-md">
            <form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-8">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Submit Information</h2>

                <!-- Client Name -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Client Name</label>
                    <input type="text" name="clientname" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring" />
                </div>

                <!-- Project Name -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Project Name</label>
                    <input type="text" name="nameproject" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring" />
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                    <select name="status" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring">
                        <option value="">Select status</option>
                        <option value="New Client">New client</option>
                        <option value="Old Client">Old client</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-[#219ebc] hover:bg-[#197aa0] text-white font-bold py-2 px-4 rounded">
                        Submit
                    </button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
