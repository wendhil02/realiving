<?php
session_start();
include '../checkrole.php';

require_role(['admin1', 'superadmin']);

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
    header("Location: ../../loginpage/index.php");
    exit();
}

include '../design/mainbody.php';
include '../../connection/connection.php';

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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="src/client_update.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>

</head>

<body class="min-h-screen bg-gray-50">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Client Info Banner -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border-l-4 border-primary-500">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Client Information</h2>
                    <div class="mt-2 flex flex-col sm:flex-row sm:space-x-6">
                        <p class="text-gray-600"><span class="font-semibold text-primary-600">Reference No:</span> <?= htmlspecialchars($referenceNumber) ?></p>
                        <p class="text-gray-600"><span class="font-semibold text-gray-700">Client Name:</span> <?= htmlspecialchars($clientName) ?></p>
                    </div>
                </div>
              <div class="flex flex-wrap items-center gap-2 md:gap-3">
    <a href="export_step_updates.php?id=<?= $id ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition-colors font-medium text-sm">
        <i class="fas fa-file-excel mr-2 text-primary-600"></i>
        Export Data
    </a>

    <a href="billing.php?id=<?= $id ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition-colors font-medium text-sm">
        <i class="fas fa-file-invoice-dollar mr-2 text-primary-600"></i>
        Billing Progress
    </a>
</div>

            </div>
        </div>

        <div class="flex flex-col lg:flex-row w-full gap-6">
            <!-- Client Tracker Section -->
            <div class="w-full lg:w-1/2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-tasks text-primary-500 mr-2"></i>
                            Client Progress Tracker
                        </h2>
                    </div>

                    <div class="p-6 space-y-4">
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

                            // Fetch revision_count for step 4 (Plan/3D) from user_info
                            $revisionCount = null;
                            if ($step == 4) {
                                $stmt = $conn->prepare("SELECT revision_count FROM user_info WHERE id = ?");
                                $stmt->bind_param("i", $id);  // Assuming $id is the client ID
                                $stmt->execute();
                                $stmt->bind_result($revisionCount);
                                $stmt->fetch();
                                $stmt->close();
                            }
                        ?>
                            <div class="progress-item">
                                <div class="p-4 rounded-lg border <?= $hasTime ? 'bg-green-50 border-green-200' : ($isCurrent ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-200') ?> flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <?php if ($hasTime): ?>
                                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                                    <i class="fas fa-check text-green-500"></i>
                                                </div>
                                            <?php elseif ($isCurrent): ?>
                                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <i class="fas fa-hourglass-half text-blue-500"></i>
                                                </div>
                                            <?php else: ?>
                                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <i class="far fa-circle text-gray-400"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <p class="font-medium <?= $hasTime ? 'text-green-700' : ($isCurrent ? 'text-blue-700' : 'text-gray-700') ?>">
                                                <?= $label ?>
                                            </p>
                                            <?php if ($updateTime): ?>
                                                <p class="text-xs text-gray-500 mt-1">Updated: <?= $updateTime ?></p>
                                            <?php endif; ?>
                                            <?php if ($endDate): ?>
                                                <p class="text-xs text-gray-500 mt-0.5">End Date: <?= $endDate ?></p>
                                            <?php endif; ?>
                                            <?php if ($step == 4 && $revisionCount !== null): ?>
                                                <p class="text-xs text-gray-500 mt-0.5">Revision Count: <?= $revisionCount ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div>
                                        <?php if ($hasTime): ?>
                                            <span class="text-xs font-medium bg-green-100 text-green-800 py-1 px-2 rounded-full">Complete</span>
                                        <?php elseif ($isCurrent): ?>
                                            <span class="text-xs font-medium bg-blue-100 text-blue-800 py-1 px-2 rounded-full">In Progress</span>
                                        <?php else: ?>
                                            <span class="text-xs font-medium bg-gray-100 text-gray-600 py-1 px-2 rounded-full">Pending</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Update Button -->
                        <div class="flex justify-end mt-6">
                            <button id="openUpdateModal" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center shadow-sm hover-raise">
                                <i class="fas fa-edit mr-2"></i>
                                Update Status
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Processing Section -->
            <div class="w-full lg:w-1/2 mt-6 lg:mt-0">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-shipping-fast text-primary-500 mr-2"></i>
                            Order Processing
                        </h2>
                    </div>

                    <div class="p-6">
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    // Fetching updates for Step 6 (Order Processing)
                                    $stmt = $conn->prepare("SELECT id, update_time, description, end_date FROM step_updates WHERE client_id = ? AND step = 6 ORDER BY update_time DESC");
                                    $stmt->bind_param("i", $id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result->num_rows > 0):
                                        while ($row = $result->fetch_assoc()):
                                            $updateTime = !empty($row['update_time']) ? date("F j, Y g:i A", strtotime($row['update_time'])) : 'Not Updated';
                                            $description = $row['description'] ?? '';
                                            $endDate = !empty($row['end_date']) ? date("F j, Y", strtotime($row['end_date'])) : 'N/A';
                                    ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= htmlspecialchars($updateTime) ?></td>
                                                <td class="px-6 py-4 text-sm text-gray-700">
                                                    <div id="desc-display-<?= $row['id'] ?>" class="flex justify-between items-center">
                                                        <span id="desc-text-<?= $row['id'] ?>" class="editable-text cursor-pointer" onclick="enableEdit(<?= $row['id'] ?>)">
                                                            <?= htmlspecialchars($description) ?>
                                                            <i class="fas fa-pencil-alt text-xs ml-2 text-gray-400"></i>
                                                        </span>
                                                    </div>
                                                    <form method="POST" action="save_update.php" id="desc-form-<?= $row['id'] ?>" class="hidden mt-1 flex items-center gap-2">
                                                        <input type="hidden" name="update_id" value="<?= $row['id'] ?>">
                                                        <input type="hidden" name="client_id" value="<?= $id ?>">
                                                        <input id="desc-input-<?= $row['id'] ?>" type="text" name="description" value="<?= htmlspecialchars($description) ?>" class="border border-gray-300 rounded-md px-3 py-1 text-sm w-full focus:outline-none focus:ring-2 focus:ring-primary-500">
                                                        <button type="submit" name="update_description" class="text-primary-600 hover:text-primary-800 font-medium text-xs">Save</button>
                                                        <button type="button" onclick="cancelEdit(<?= $row['id'] ?>)" class="text-gray-500 text-xs hover:text-gray-700">Cancel</button>
                                                    </form>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= htmlspecialchars($endDate) ?></td>
                                            </tr>
                                        <?php endwhile;
                                    else: ?>
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No updates found.</td>
                                        </tr>
                                    <?php endif;
                                    $stmt->close(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div id="notification" class="hidden fixed bottom-4 right-4 bg-white border-l-4 border-primary-500 rounded-lg shadow-lg p-4 max-w-sm z-50">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-primary-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <p id="notificationMessage" class="text-sm text-gray-700"></p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button onclick="closeNotification()" class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-gray-100 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div id="updateModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex justify-center items-center">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-4 animate__animated animate__fadeInUp">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Update Client Status</h2>
                <button id="closeUpdateModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="updateForm" action="save_update.php" method="POST" class="space-y-5">
                <!-- Hidden client_id -->
                <input type="hidden" name="client_id" value="<?= $id ?>">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Step:</label>
                    <div class="relative">
                        <select name="step" id="stepSelect" class="w-full border border-gray-300 rounded-lg p-3 pr-10 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 appearance-none">
                            <?php foreach ($steps as $step => $label): ?>
                                <option value="<?= $step ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Update Date:</label>
                        <input type="date" name="update_date" id="updateDate" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500" required />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Update Time:</label>
                        <input type="time" name="update_time" id="updateTime" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500" required />
                    </div>
                </div>

                <div id="endDateContainer" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date:</label>
                    <input type="date" name="end_date" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500" />
                </div>

                <!-- Revision Count Input (Shown only in Step 4) -->
                <div id="revisionContainer" class="hidden mb-4">
                    <label for="revisionCount" class="block text-sm font-medium text-gray-700 mb-1">
                        Revision Count
                    </label>
                    <input
                        type="number"
                        id="revisionCount"
                        name="revisionCount"
                        min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-base focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Enter number of revisions" />
                </div>

                <div id="descriptionContainer" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description:</label>
                    <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                </div>

                <!-- Modal Action Buttons -->
                <div id="normalButtons" class="flex justify-between items-center mt-6">
                    <button type="button" id="saveButton" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm font-medium flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Save Changes
                    </button>
                </div>

                <div id="confirmButtons" class="hidden space-y-4">
                    <p class="text-sm text-gray-700 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                        Are you sure you want to save this update?
                    </p>
                    <div class="flex justify-between">
                        <button type="button" id="confirmNo" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">
                            No, Go Back
                        </button>
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium flex items-center">
                            <i class="fas fa-check mr-2"></i>
                            Yes, Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
            <?php if (isset($_SESSION['error'])): ?>
                showNotification("<?= $_SESSION['error']; ?>", "error");
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                showNotification("<?= $_SESSION['success']; ?>", "success");
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

    <script src="js/clientupdate.js"></script>
    <script src="js/client_update.js"></script>
    
</body>

</html>