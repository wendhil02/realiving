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

// Get client ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Process project cost update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_project_cost'])) {
    $newProjectCost = (float)$_POST['new_project_cost'];

    if ($newProjectCost < 0) {
        $_SESSION['error'] = "Project cost cannot be negative";
    } else {
        $updateQuery = "UPDATE user_info SET total_project_cost = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("di", $newProjectCost, $id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Total project cost updated successfully";
            header("Location: billing.php?id=$id");
            exit();
        } else {
            $_SESSION['error'] = "Error updating project cost: " . $conn->error;
        }
    }
}

// Fetch client data
$query = "SELECT id, clientname, reference_number, total_project_cost FROM user_info WHERE id = $id";
$result = $conn->query($query);
$row = $result->fetch_assoc();

// Check if client exists, otherwise handle error
if (!$row) {
    echo "<div class='p-4 bg-red-100 border border-red-300 text-red-700 rounded mb-4'>
            Client not found. Please check the client ID or go back to the dashboard.
          </div>";
    exit();
}

$clientName = $row['clientname'];
$referenceNumber = $row['reference_number'];
$totalProjectCost = $row['total_project_cost'] ?? 0;

// Calculate payment percentages
$downpayment = $totalProjectCost * 0.5; // 50%
$beforeInstallation = $totalProjectCost * 0.4; // 40%
$afterInstallation = $totalProjectCost * 0.1; // 10%

// Fetch payment records
$paymentQuery = "SELECT * FROM client_payments WHERE client_id = $id ORDER BY payment_date DESC";
$paymentResult = $conn->query($paymentQuery);

// Calculate totals
$totalPaid = 0;
$downpaymentPaid = 0;
$beforeInstallationPaid = 0;
$afterInstallationPaid = 0;

if ($paymentResult && $paymentResult->num_rows > 0) {
    while ($payment = $paymentResult->fetch_assoc()) {
        $totalPaid += $payment['amount'];

        if ($payment['payment_type'] == 'downpayment') {
            $downpaymentPaid += $payment['amount'];
        } elseif ($payment['payment_type'] == 'before_installation') {
            $beforeInstallationPaid += $payment['amount'];
        } elseif ($payment['payment_type'] == 'after_installation') {
            $afterInstallationPaid += $payment['amount'];
        }
    }
}

$remainingBalance = $totalProjectCost - $totalPaid;

// Process payment form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_payment'])) {
    $paymentType = $_POST['payment_type'];
    $amount = (float)$_POST['amount'];
    $paymentDate = $_POST['payment_date'];
    $paymentMethod = $_POST['payment_method'];
    $description = $_POST['description'];

    // Simple validation
    if ($amount <= 0) {
        $_SESSION['error'] = "Payment amount must be greater than zero";
    } else {
        $sql = "INSERT INTO client_payments (client_id, payment_type, amount, payment_date, payment_method, description) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isdsss", $id, $paymentType, $amount, $paymentDate, $paymentMethod, $description);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Payment recorded successfully";
            // Redirect to refresh the page and avoid form resubmission
            header("Location: billing.php?id=$id");
            exit();
        } else {
            $_SESSION['error'] = "Error recording payment: " . $conn->error;
        }
    }
}

// Process payment update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_payment'])) {
    $paymentId = (int)$_POST['payment_id'];
    $paymentType = $_POST['payment_type'];
    $amount = (float)$_POST['amount'];
    $paymentDate = $_POST['payment_date'];
    $paymentMethod = $_POST['payment_method'];
    $description = $_POST['description'];

    // Simple validation
    if ($amount <= 0) {
        $_SESSION['error'] = "Payment amount must be greater than zero";
    } else {
        $sql = "UPDATE client_payments SET payment_type = ?, amount = ?, payment_date = ?, payment_method = ?, description = ? WHERE id = ? AND client_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsssii", $paymentType, $amount, $paymentDate, $paymentMethod, $description, $paymentId, $id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Payment updated successfully";
            header("Location: billing.php?id=$id");
            exit();
        } else {
            $_SESSION['error'] = "Error updating payment: " . $conn->error;
        }
    }
}

// Process payment deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_payment'])) {
    $paymentId = (int)$_POST['payment_id'];

    $sql = "DELETE FROM client_payments WHERE id = ? AND client_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $paymentId, $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Payment deleted successfully";
        header("Location: billing.php?id=$id");
        exit();
    } else {
        $_SESSION['error'] = "Error deleting payment: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Client Billing Tracker</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

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

    <style>
        .progress-bar {
            height: 10px;
            transition: width 0.3s ease;
        }

        .hover-raise {
            transition: transform 0.2s ease;
        }

        .hover-raise:hover {
            transform: translateY(-2px);
        }

        .modal-enter {
            animation: modalEnter 0.3s ease-out;
        }

        @keyframes modalEnter {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Button -->
        <a href="client_update.php?id=<?= $id ?>" class="inline-flex items-center mb-6 text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Client Profile
        </a>

        <!-- Client Info Banner -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border-l-4 border-primary-500">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Client Billing Information</h2>
                    <div class="mt-2 flex flex-col sm:flex-row sm:space-x-6">
                        <p class="text-gray-600"><span class="font-semibold text-primary-600">Reference No:</span> <?= htmlspecialchars($referenceNumber) ?></p>
                        <p class="text-gray-600"><span class="font-semibold text-gray-700">Client Name:</span> <?= htmlspecialchars($clientName) ?></p>
                    </div>
                </div>
                <div class="mt-4 md:mt-0 flex flex-col items-start md:items-end">
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <p class="text-2xl font-bold text-gray-900">₱<?= number_format($totalProjectCost, 2) ?></p>
                            <p class="text-sm text-gray-500">Total Project Cost</p>
                        </div>
                        <button id="editProjectCostBtn" class="flex items-center justify-center w-8 h-8 bg-gray-100 hover:bg-primary-100 rounded-full transition-colors group">
                            <i class="fas fa-edit text-gray-500 group-hover:text-primary-600 text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Progress Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Downpayment Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-blue-50 border-b border-blue-100">
                    <h3 class="text-md font-semibold text-blue-800">Downpayment (50%)</h3>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-500">Progress</span>
                        <span class="text-sm font-medium text-<?= ($downpayment > 0 && $downpaymentPaid >= $downpayment) ? 'green' : 'blue' ?>-600">
                            <?= ($downpayment > 0) ? min(round(($downpaymentPaid / $downpayment) * 100), 100) : 0 ?>%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                        <div class="bg-blue-600 h-2.5 rounded-full progress-bar" style="width: <?= ($downpayment > 0) ? min(($downpaymentPaid / $downpayment) * 100, 100) : 0 ?>%"></div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-900 font-semibold">₱<?= number_format($downpaymentPaid, 2) ?> <span class="text-sm font-normal text-gray-500">paid</span></p>
                            <p class="text-sm text-gray-500">of ₱<?= number_format($downpayment, 2) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium <?= ($downpayment > 0 && $downpaymentPaid >= $downpayment) ? 'text-green-600' : 'text-blue-600' ?>">
                                <?= ($downpayment > 0 && $downpaymentPaid >= $downpayment) ? 'Complete' : 'In Progress' ?>
                            </p>
                            <?php if ($downpayment > 0 && $downpaymentPaid < $downpayment): ?>
                                <p class="text-sm text-gray-500">₱<?= number_format($downpayment - $downpaymentPaid, 2) ?> remaining</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Before Installation Card (40%) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-indigo-50 border-b border-indigo-100">
                    <h3 class="text-md font-semibold text-indigo-800">Before Installation (40%)</h3>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-500">Progress</span>
                        <span class="text-sm font-medium text-<?= ($beforeInstallation > 0 && $beforeInstallationPaid >= $beforeInstallation) ? 'green' : 'indigo' ?>-600">
                            <?= ($beforeInstallation > 0) ? min(round(($beforeInstallationPaid / $beforeInstallation) * 100), 100) : 0 ?>%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                        <div class="bg-indigo-600 h-2.5 rounded-full progress-bar" style="width: <?= ($beforeInstallation > 0) ? min(($beforeInstallationPaid / $beforeInstallation) * 100, 100) : 0 ?>%"></div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-900 font-semibold">₱<?= number_format($beforeInstallationPaid, 2) ?> <span class="text-sm font-normal text-gray-500">paid</span></p>
                            <p class="text-sm text-gray-500">of ₱<?= number_format($beforeInstallation, 2) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium <?= ($beforeInstallation > 0 && $beforeInstallationPaid >= $beforeInstallation) ? 'text-green-600' : 'text-indigo-600' ?>">
                                <?= ($beforeInstallation > 0 && $beforeInstallationPaid >= $beforeInstallation) ? 'Complete' : 'In Progress' ?>
                            </p>
                            <?php if ($beforeInstallation > 0 && $beforeInstallationPaid < $beforeInstallation): ?>
                                <p class="text-sm text-gray-500">₱<?= number_format($beforeInstallation - $beforeInstallationPaid, 2) ?> remaining</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- After Installation Card (10%) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-purple-50 border-b border-purple-100">
                    <h3 class="text-md font-semibold text-purple-800">After Installation (10%)</h3>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-500">Progress</span>
                        <span class="text-sm font-medium text-<?= ($afterInstallation > 0 && $afterInstallationPaid >= $afterInstallation) ? 'green' : 'purple' ?>-600">
                            <?= ($afterInstallation > 0) ? min(round(($afterInstallationPaid / $afterInstallation) * 100), 100) : 0 ?>%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                        <div class="bg-purple-600 h-2.5 rounded-full progress-bar" style="width: <?= ($afterInstallation > 0) ? min(($afterInstallationPaid / $afterInstallation) * 100, 100) : 0 ?>%"></div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-900 font-semibold">₱<?= number_format($afterInstallationPaid, 2) ?> <span class="text-sm font-normal text-gray-500">paid</span></p>
                            <p class="text-sm text-gray-500">of ₱<?= number_format($afterInstallation, 2) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium <?= ($afterInstallation > 0 && $afterInstallationPaid >= $afterInstallation) ? 'text-green-600' : 'text-purple-600' ?>">
                                <?= ($afterInstallation > 0 && $afterInstallationPaid >= $afterInstallation) ? 'Complete' : 'In Progress' ?>
                            </p>
                            <?php if ($afterInstallation > 0 && $afterInstallationPaid < $afterInstallation): ?>
                                <p class="text-sm text-gray-500">₱<?= number_format($afterInstallation - $afterInstallationPaid, 2) ?> remaining</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overall Progress -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Overall Payment Progress</h3>

            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-500">Total Paid: ₱<?= number_format($totalPaid, 2) ?></span>
                <span class="text-sm font-medium text-<?= ($totalProjectCost > 0 && $totalPaid >= $totalProjectCost) ? 'green' : 'gray' ?>-600">
                    <?= ($totalProjectCost > 0) ? round(($totalPaid / $totalProjectCost) * 100) : 0 ?>%
                </span>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                <div class="bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 h-3 rounded-full progress-bar"
                    style="width: <?= ($totalProjectCost > 0) ? min(($totalPaid / $totalProjectCost) * 100, 100) : 0 ?>%"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">Total Project Cost</p>
                    <p class="text-xl font-bold text-gray-900">₱<?= number_format($totalProjectCost, 2) ?></p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">Remaining Balance</p>
                    <p class="text-xl font-bold <?= ($remainingBalance > 0) ? 'text-red-600' : 'text-green-600' ?>">
                        ₱<?= number_format($remainingBalance, 2) ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Payment History -->
            <div class="w-full md:w-2/3 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Payment History</h3>
                    <button id="openPaymentModal" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center shadow-sm hover-raise">
                        <i class="fas fa-plus mr-2"></i>
                        Add Payment
                    </button>
                </div>
                <div class="p-6">
                    <!-- Payment History Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($paymentResult && $paymentResult->num_rows > 0):
                                    // Reset pointer to beginning
                                    $paymentResult->data_seek(0);
                                    while ($payment = $paymentResult->fetch_assoc()):
                                        $paymentDate = date("M d, Y", strtotime($payment['payment_date']));

                                        // Map payment type to display name
                                        $paymentTypeDisplay = [
                                            'downpayment' => 'Downpayment (50%)',
                                            'before_installation' => 'Before Installation (40%)',
                                            'after_installation' => 'After Installation (10%)'
                                        ][$payment['payment_type']] ?? $payment['payment_type'];
                                ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= $paymentDate ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <?php if ($payment['payment_type'] == 'downpayment'): ?>
                                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                        <?= $paymentTypeDisplay ?>
                                                    </span>
                                                <?php elseif ($payment['payment_type'] == 'before_installation'): ?>
                                                    <span class="px-2 py-1 text-xs font-medium bg-indigo-100 text-indigo-800 rounded-full">
                                                        <?= $paymentTypeDisplay ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">
                                                        <?= $paymentTypeDisplay ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₱<?= number_format($payment['amount'], 2) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                <span class="inline-flex items-center">
                                                    <?php if ($payment['payment_method'] == 'cash'): ?>
                                                        <i class="fas fa-money-bill-wave text-green-500 mr-1"></i>
                                                    <?php elseif ($payment['payment_method'] == 'bank_transfer'): ?>
                                                        <i class="fas fa-university text-blue-500 mr-1"></i>
                                                    <?php elseif ($payment['payment_method'] == 'check'): ?>
                                                        <i class="fas fa-money-check text-indigo-500 mr-1"></i>
                                                    <?php else: ?>
                                                        <i class="fas fa-credit-card text-gray-500 mr-1"></i>
                                                    <?php endif; ?>
                                                    <?= ucfirst(str_replace('_', ' ', $payment['payment_method'])) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($payment['description'] ?: '-') ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <button onclick="editPayment(<?= htmlspecialchars(json_encode($payment)) ?>)"
                                                        class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button onclick="deletePayment(<?= $payment['id'] ?>, '<?= htmlspecialchars($paymentTypeDisplay) ?>', '<?= number_format($payment['amount'], 2) ?>')"
                                                        class="text-red-600 hover:text-red-900 transition-colors">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile;
                                else: ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No payment records found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="w-full md:w-1/3 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Payment Summary</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-500">Total Project Cost:</span>
                            <span class="text-sm font-bold text-gray-900">₱<?= number_format($totalProjectCost, 2) ?></span>
                        </div>

                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-500">Downpayment (50%):</span>
                            <div class="text-right">
                                <span class="text-sm font-medium text-gray-900">₱<?= number_format($downpaymentPaid, 2) ?></span>
                                <span class="text-xs text-gray-500"> / ₱<?= number_format($downpayment, 2) ?></span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-500">Before Installation (40%):</span>
                            <div class="text-right">
                                <span class="text-sm font-medium text-gray-900">₱<?= number_format($beforeInstallationPaid, 2) ?></span>
                                <span class="text-xs text-gray-500"> / ₱<?= number_format($beforeInstallation, 2) ?></span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-500">After Installation (10%):</span>
                            <div class="text-right">
                                <span class="text-sm font-medium text-gray-900">₱<?= number_format($afterInstallationPaid, 2) ?></span>
                                <span class="text-xs text-gray-500"> / ₱<?= number_format($afterInstallation, 2) ?></span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-2">
                            <span class="text-base font-bold text-gray-700">Total Paid:</span>
                            <span class="text-base font-bold text-green-600">₱<?= number_format($totalPaid, 2) ?></span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-base font-bold text-gray-700">Balance Remaining:</span>
                            <span class="text-base font-bold <?= ($remainingBalance > 0) ? 'text-red-600' : 'text-green-600' ?>">
                                ₱<?= number_format($remainingBalance, 2) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Payment Modal -->
        <div id="editPaymentModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex justify-center items-center">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-4 modal-enter">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Edit Payment Record</h2>
                    <button id="closeEditPaymentModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form id="editPaymentForm" action="billing.php?id=<?= $id ?>" method="POST" class="space-y-5">
                    <!-- Hidden payment ID -->
                    <input type="hidden" name="payment_id" id="editPaymentId">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Type:</label>
                        <div class="relative">
                            <select name="payment_type" id="editPaymentType" class="w-full border border-gray-300 rounded-lg p-3 pr-10 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 appearance-none">
                                <option value="downpayment">Downpayment (50%)</option>
                                <option value="before_installation">Before Installation (40%)</option>
                                <option value="after_installation">After Installation (10%)</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount:</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                            <input type="number" name="amount" id="editPaymentAmount" step="0.01" min="0" required
                                class="w-full border border-gray-300 rounded-lg p-3 pl-8 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                placeholder="0.00">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Date:</label>
                        <input type="date" name="payment_date" id="editPaymentDate" required
                            class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method:</label>
                        <div class="relative">
                            <select name="payment_method" id="editPaymentMethod" required
                                class="w-full border border-gray-300 rounded-lg p-3 pr-10 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 appearance-none">
                                <option value="">Select payment method</option>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="check">Check</option>
                                <option value="credit_card">Credit Card</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description (Optional):</label>
                        <textarea name="description" id="editPaymentDescription" rows="3"
                            class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
                            placeholder="Additional notes about this payment..."></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" id="cancelEditPayment"
                            class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" name="update_payment"
                            class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            Update Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Payment Modal -->
        <div id="deletePaymentModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex justify-center items-center">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-4 modal-enter">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Delete Payment Record</h2>
                    <button id="closeDeletePaymentModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="mb-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <p class="text-center text-gray-700 mb-2">Are you sure you want to delete this payment record?</p>
                    <div id="deletePaymentDetails" class="bg-gray-50 rounded-lg p-4 text-center">
                        <!-- Payment details will be inserted here -->
                    </div>
                    <p class="text-center text-sm text-red-600 mt-2">This action cannot be undone.</p>
                </div>

                <form id="deletePaymentForm" action="billing.php?id=<?= $id ?>" method="POST">
                    <input type="hidden" name="payment_id" id="deletePaymentId">

                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelDeletePayment"
                            class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" name="delete_payment"
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                            <i class="fas fa-trash mr-2"></i>
                            Delete Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>


    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex justify-center items-center">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-4 modal-enter">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Add Payment Record</h2>
                <button id="closePaymentModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="paymentForm" action="billing.php?id=<?= $id ?>" method="POST" class="space-y-5">
                <!-- Hidden client_id -->
                <input type="hidden" name="client_id" value="<?= $id ?>">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Type:</label>
                    <div class="relative">
                        <select name="payment_type" id="paymentType" class="w-full border border-gray-300 rounded-lg p-3 pr-10 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 appearance-none">
                            <option value="downpayment">
                            <option value="downpayment">Downpayment (50%)</option>
                            <option value="before_installation">Before Installation (40%)</option>
                            <option value="after_installation">After Installation (10%)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount:</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                        <input type="number" name="amount" step="0.01" min="0" required
                            class="w-full border border-gray-300 rounded-lg p-3 pl-8 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500"
                            placeholder="0.00">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Date:</label>
                    <input type="date" name="payment_date" required
                        class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500"
                        value="<?= date('Y-m-d') ?>">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method:</label>
                    <div class="relative">
                        <select name="payment_method" required
                            class="w-full border border-gray-300 rounded-lg p-3 pr-10 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 appearance-none">
                            <option value="">Select payment method</option>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="check">Check</option>
                            <option value="credit_card">Credit Card</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (Optional):</label>
                    <textarea name="description" rows="3"
                        class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
                        placeholder="Additional notes about this payment..."></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" id="cancelPayment"
                        class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" name="add_payment"
                        class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Save Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Project Cost Modal -->
    <div id="editProjectCostModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex justify-center items-center">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-4 modal-enter">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Edit Project Cost</h2>
                <button id="closeProjectCostModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form action="billing.php?id=<?= $id ?>" method="POST" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Project Cost:</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                        <input type="number" name="new_project_cost" step="0.01" min="0" required
                            value="<?= $totalProjectCost ?>"
                            class="w-full border border-gray-300 rounded-lg p-3 pl-8 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500"
                            placeholder="0.00">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        Note: Changing this will update the payment breakdowns (50%, 40%, 10%)
                    </p>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" id="cancelProjectCostEdit"
                        class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" name="update_project_cost"
                        class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Update Cost
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div id="successMessage" class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg z-50">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <?= $_SESSION['success'] ?>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div id="errorMessage" class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg z-50">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?= $_SESSION['error'] ?>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <script>
        // Payment type suggestion based on remaining amounts
        const paymentTypeSelect = document.getElementById('paymentType');
        const amountInput = document.querySelector('input[name="amount"]');

        if (paymentTypeSelect && amountInput) {
            paymentTypeSelect.addEventListener('change', function() {
                const selectedType = this.value;
                let suggestedAmount = 0;

                // Calculate suggested amounts based on remaining balance for each type
                switch (selectedType) {
                    case 'downpayment':
                        suggestedAmount = <?= max(0, $downpayment - $downpaymentPaid) ?>;
                        break;
                    case 'before_installation':
                        suggestedAmount = <?= max(0, $beforeInstallation - $beforeInstallationPaid) ?>;
                        break;
                    case 'after_installation':
                        suggestedAmount = <?= max(0, $afterInstallation - $afterInstallationPaid) ?>;
                        break;
                }

                if (suggestedAmount > 0) {
                    amountInput.value = suggestedAmount.toFixed(2);
                }
            });
        }
    </script>
    <script src="js/billing/modal.js"></script>
    <script src="js/billing/billing.js"></script>
    <script src="js/billing/updatehistorydel.js"></script>
    
</body>

</html>