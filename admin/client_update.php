<?php
session_start();

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
    header("Location: ../index.php");
    exit();
}

include 'design/mainbody.php';
include '../connection/connection.php';

// Reset AUTO_INCREMENT if empty
$result = $conn->query("SELECT COUNT(*) AS count FROM step_updates");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $conn->query("ALTER TABLE step_updates AUTO_INCREMENT = 1");
}

// Get client ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch client data
$query = "SELECT id, clientname, updatestatus, reference_number FROM user_info WHERE id = $id";
$result = $conn->query($query);
$row = $result->fetch_assoc();

// Check if client exists, otherwise handle error
if (!$row) {
    echo "<div class='p-4 bg-red-100 border border-red-300 text-red-700 rounded mb-4'>
            Client not found. Please check the client ID or go back to the dashboard.
          </div>";
    exit();
}

$currentStatus = $row['updatestatus'] ?? 1;
$clientName = $row['clientname'];
$referenceNumber = $row['reference_number']; // Reference number from database

// Step definitions
$steps = [
    1 => 'Quatation',
    2 => 'Site Visit',
    3 => 'Material Approval',
    4 => 'Plan/3D Approval',
    5 => 'Cutting list',
    6 => 'Order Processing',
    7 => 'fabrication',
    8 => 'Delivery',
    9 => 'Installation',
    10 => 'Handover'
];

// Function to fetch latest step update details
function getStepUpdateDetails($conn, $clientId, $step)
{
    $stmt = $conn->prepare("SELECT update_time, end_date, description FROM step_updates WHERE client_id = ? AND step = ? ORDER BY update_time DESC LIMIT 1");
    $stmt->bind_param("ii", $clientId, $step);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return [
            'update_time' => date("F j, Y, g:i a", strtotime($row['update_time'])),
            'end_date' => $row['end_date'] ? date("F j, Y", strtotime($row['end_date'])) : null,
            'description' => $row['description'] // Added description here
        ];
    }
    return null;
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

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class=" min-h-screen bg-gray-200">
    <div class="flex flex-col lg:flex-row w-full gap-4 p-4">

        <!-- Client Tracker Section -->
        <div class="w-full lg:w-1/2 flex flex-col p-2">
            <main class="w-full flex flex-col items-start justify-start">

                <!-- Notification Area -->
                <div id="notification" class="hidden fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white bg-green-500 z-50">
                    <p id="notificationText" class="text-sm"></p>
                </div>

                <!-- Client Tracker Card -->
                <div class="w-full bg-white rounded-lg shadow-lg p-4 space-y-4">
                    <h2 class="text-xl font-bold text-center text-green-700">Client Progress Tracker</h2>
                    <p class="text-gray-600"><span class="font-semibold text-red-600">Reference No:</span> <?= htmlspecialchars($referenceNumber) ?></p>
                    <p class="text-gray-600"><span class="font-semibold">Client Name:</span> <?= htmlspecialchars($clientName) ?></p>

                    <div class="space-y-3">
                        <?php foreach ($steps as $step => $label):
                            $details = getStepUpdateDetails($conn, $id, $step);
                            $hasTime = $details !== null;
                            $updateTime = $hasTime ? $details['update_time'] : null;
                            $endDate = $hasTime ? $details['end_date'] : null;
                            static $firstPendingStep = null;
                            if (!$hasTime && $firstPendingStep === null) {
                                $firstPendingStep = $step;
                            }
                            $isCurrent = $step === $firstPendingStep;
                        ?>
                            <div>
                                <div class="p-3 rounded-lg border flex items-center justify-between
                                    <?= $hasTime ? 'bg-green-100 border-green-500' : 'bg-gray-100 border-gray-300' ?>">
                                    <div class="flex flex-col">
                                        <p class="text-sm font-medium <?= $hasTime ? 'text-green-700' : 'text-gray-700' ?>">
                                            <?= $label ?>
                                        </p>
                                        <?php if ($updateTime): ?>
                                            <p class="text-xs text-gray-500">Updated: <?= $updateTime ?></p>
                                        <?php endif; ?>
                                        <?php if ($endDate): ?>
                                            <p class="text-xs text-gray-400">End Date: <?= $endDate ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <?= $hasTime ? '<span class="text-green-500 text-lg">✔️</span>' : '<span class="text-gray-400 text-lg">⬜</span>' ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Update Button -->
                        <div class="flex justify-end mt-4">
                            <button id="openUpdateModal" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 text-sm">
                                Update
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>


        <!-- Order Processing Section -->
        <div class="w-full lg:w-1/2 flex flex-col p-2">
            <main class="w-full max-w-full">
                <div class="w-full bg-white rounded-lg shadow-xl p-4 space-y-4">
                    <h2 class="text-xl font-bold text-center text-green-700">Order Processing Tracker</h2>
                    <div class="text-sm space-y-1">
                        <p class="text-gray-600"><span class="font-semibold text-red-600">Reference No:</span> <?= htmlspecialchars($referenceNumber) ?></p>
                        <p class="text-gray-600"><span class="font-semibold">Client Name:</span> <?= htmlspecialchars($clientName) ?></p>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full text-sm text-left">
                            <thead class="bg-green-100 text-green-800">
                                <tr>
                                    <th class="px-4 py-3 font-semibold">Updated</th>
                                    <th class="px-4 py-3 font-semibold">Description</th>
                                    <th class="px-4 py-3 font-semibold">End Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <?php
                                // Fetching updates for Step 6 (Order Processing)
                                $stmt = $conn->prepare("SELECT id, update_time, description, end_date FROM step_updates WHERE client_id = ? AND step = 6 ORDER BY update_time DESC");
                                $stmt->bind_param("i", $id); // Use $id here to fetch updates for the current client
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows > 0):
                                    while ($row = $result->fetch_assoc()):
                                        $updateTime = !empty($row['update_time']) ? date("F j, Y g:i A", strtotime($row['update_time'])) : 'Not Updated';
                                        $description = $row['description'] ?? '';
                                        $endDate = !empty($row['end_date']) ? date("F j, Y", strtotime($row['end_date'])) : 'N/A';
                                ?>
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="px-4 py-3"><?= htmlspecialchars($updateTime) ?></td>
                                            <td class="px-4 py-3">
                                                <div id="desc-display-<?= $row['id'] ?>" class="flex justify-between items-center">
                                                    <span id="desc-text-<?= $row['id'] ?>" class="editable-text cursor-pointer" onclick="enableEdit(<?= $row['id'] ?>)">
                                                        <?= htmlspecialchars($description) ?>
                                                    </span>
                                                </div>
                                                <form method="POST" action="save_update.php" id="desc-form-<?= $row['id'] ?>" class="hidden mt-1 flex items-center gap-2">
                                                    <input type="hidden" name="update_id" value="<?= $row['id'] ?>">
                                                    <input type="hidden" name="client_id" value="<?= $id ?>"> <!-- Make sure this is the correct client ID -->
                                                    <input id="desc-input-<?= $row['id'] ?>" type="text" name="description" value="<?= htmlspecialchars($description) ?>" class="border rounded px-2 py-1 text-sm w-[120px]">
                                                    <button type="submit" name="update_description" class="text-green-600 hover:text-green-800 font-semibold text-xs">Save</button>
                                                    <button type="button" onclick="cancelEdit(<?= $row['id'] ?>)" class="text-gray-500 text-xs hover:underline">Cancel</button>
                                                </form>
                                            </td>
                                            <td class="px-4 py-3"><?= htmlspecialchars($endDate) ?></td>
                                        </tr>
                                    <?php endwhile;
                                else: ?>
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-center text-gray-500">No updates found.</td>
                                    </tr>
                                <?php endif;
                                $stmt->close(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
        <!-- Notification Container (hidden by default) -->
        <div id="notification" class="hidden fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-lg shadow-md">
            <span id="notificationMessage"></span>
        </div>
        <script>


        </script>

        <div id="updateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex justify-center items-center">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-sm mx-auto">
        <h2 class="text-lg font-semibold text-center text-gray-800 mb-4">Update Client Status</h2>

        <!-- ✅ Single form for everything -->
        <form id="updateForm" action="save_update.php" method="POST" class="space-y-4">

            <!-- Hidden client_id -->
            <input type="hidden" name="client_id" value="<?= $id ?>">

            <div>
                <label class="block text-sm text-gray-700 mb-1">Select Step:</label>
                <select name="step" id="stepSelect" class="w-full border rounded p-2">
                    <?php foreach ($steps as $step => $label): ?>
                        <option value="<?= $step ?>"><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm">Update Date:</label>
                <input type="date" name="update_date" class="w-full p-2 border rounded" required />

                <label class="block text-sm mt-2">Update Time:</label>
                <input type="time" name="update_time" class="w-full p-2 border rounded" required />

                <div id="endDateContainer" class="hidden mt-2">
                    <label class="block text-sm">End Date:</label>
                    <input type="date" name="end_date" class="w-full p-2 border rounded" />
                </div>

                <div id="descriptionContainer" class="hidden mt-2">
                    <label class="block text-sm">Description:</label>
                    <textarea name="description" rows="3" class="w-full p-2 border rounded"></textarea>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div id="normalButtons" class="flex justify-between items-center mt-6">
                <button type="button" id="saveButton" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">Save</button>
                <button type="button" id="closeUpdateModal" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm">Cancel</button>
            </div>

            <div id="confirmButtons" class="hidden flex justify-between items-center mt-6">
                <p class="text-sm text-gray-700">Are you sure you want to save this update?</p>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm">Yes</button>
                    <button type="button" id="confirmNo" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 text-sm">No</button>
                </div>
            </div>
        </form>
    </div>
</div>


        <script src="../js/clientupdate.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const stepSelect = document.getElementById('stepSelect');
                const endDateContainer = document.getElementById('endDateContainer');
                const descriptionContainer = document.getElementById('descriptionContainer');

                // Toggle fields based on the selected step
                function toggleFields() {
                    const selectedStep = parseInt(stepSelect.value);

                    // Show "End of Date" for steps 3 to 10
                    if (selectedStep >= 3 && selectedStep <= 10) {
                        endDateContainer.classList.remove('hidden');
                    } else {
                        endDateContainer.classList.add('hidden');
                    }

                    // Show "Description" only for Order Processing (step 6)
                    if (selectedStep === 6) {
                        descriptionContainer.classList.remove('hidden');
                    } else {
                        descriptionContainer.classList.add('hidden');
                    }
                }

                // Listen to step selection change
                stepSelect.addEventListener('change', toggleFields);

                // Run on page load to set initial state
                toggleFields();


                document.addEventListener('DOMContentLoaded', function() {
                    const notification = document.getElementById('notification');
                    if (notification) {
                        // Only manipulate if the element exists
                        const notificationMessage = document.getElementById('notificationMessage');

                        // Ensure notificationMessage also exists
                        if (notificationMessage) {
                            notificationMessage.textContent = message;
                        }

                        // Show notification with a timeout
                        notification.classList.remove('hidden');
                        setTimeout(() => {
                            notification.classList.add('hidden');
                        }, 5000);
                    } else {
                        console.error('Notification element not found');
                    }
                });


                // Check for error message from session and show notification
                <?php if (isset($_SESSION['error'])): ?>
                    showNotification("<?= $_SESSION['error']; ?>", "error");
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                // Check for success message from session and show notification
                <?php if (isset($_SESSION['success'])): ?>
                    showNotification("<?= $_SESSION['success']; ?>", "success");
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
            });

            // Enable edit mode for description
            function enableEdit(id) {
                document.getElementById('desc-display-' + id).classList.add('hidden');
                document.getElementById('desc-form-' + id).classList.remove('hidden');
                document.getElementById('desc-input-' + id).focus();
            }

            // Cancel the edit mode and revert back
            function cancelEdit(id) {
                document.getElementById('desc-display-' + id).classList.remove('hidden');
                document.getElementById('desc-form-' + id).classList.add('hidden');
            }
        </script>


</body>

</html>