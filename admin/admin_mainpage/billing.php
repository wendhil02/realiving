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
        // Calculate the new remaining balance (total project cost - total paid)
        $totalPaidQuery = "SELECT COALESCE(SUM(amount), 0) as total_paid FROM client_payments WHERE client_id = ?";
        $stmt = $conn->prepare($totalPaidQuery);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $totalPaidResult = $stmt->get_result();
        $totalPaidRow = $totalPaidResult->fetch_assoc();
        $totalPaid = $totalPaidRow['total_paid'];

        $remainingBalance = $newProjectCost - $totalPaid;

        // Update both project cost and remaining balance
        $updateQuery = "UPDATE user_info SET total_project_cost = ?, remaining_balance = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ddi", $newProjectCost, $remainingBalance, $id);

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
$query = "SELECT id, clientname, reference_number, total_project_cost, remaining_balance FROM user_info WHERE id = $id";
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
$remainingBalance = $row['remaining_balance'] ?? $totalProjectCost; // Fallback to total if not set

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
        // Start transaction
        $conn->begin_transaction();
        try {
            // Insert the payment record
            $sql = "INSERT INTO client_payments (client_id, payment_type, amount, payment_date, payment_method, description) 
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isdsss", $id, $paymentType, $amount, $paymentDate, $paymentMethod, $description);
            $stmt->execute();

            // Update the remaining balance in user_info table
            $newRemainingBalance = $remainingBalance - $amount;
            $updateBalanceQuery = "UPDATE user_info SET remaining_balance = ? WHERE id = ?";
            $stmt = $conn->prepare($updateBalanceQuery);
            $stmt->bind_param("di", $newRemainingBalance, $id);
            $stmt->execute();

            // Commit transaction
            $conn->commit();

            $_SESSION['success'] = "Payment recorded successfully ";
            // Redirect to refresh the page and avoid form resubmission
            header("Location: billing.php?id=$id");
            exit();
        } catch (Exception $e) {
            // An error occurred, rollback the transaction
            $conn->rollback();
            $_SESSION['error'] = "Error recording payment: " . $e->getMessage();
        }
    }
}

// Process payment update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_payment'])) {
    $paymentId = (int)$_POST['payment_id'];
    $paymentType = $_POST['payment_type'];
    $newAmount = (float)$_POST['amount'];
    $paymentDate = $_POST['payment_date'];
    $paymentMethod = $_POST['payment_method'];
    $description = $_POST['description'];

    // Simple validation
    if ($newAmount <= 0) {
        $_SESSION['error'] = "Payment amount must be greater than zero";
    } else {
        // Start transaction
        $conn->begin_transaction();
        try {
            // Get the original payment amount
            $originalAmountQuery = "SELECT amount FROM client_payments WHERE id = ? AND client_id = ?";
            $stmt = $conn->prepare($originalAmountQuery);
            $stmt->bind_param("ii", $paymentId, $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $originalPayment = $result->fetch_assoc();

            if ($originalPayment) {
                $originalAmount = $originalPayment['amount'];
                $amountDifference = $newAmount - $originalAmount; // Positive if new amount is higher

                // Update payment record
                $sql = "UPDATE client_payments SET payment_type = ?, amount = ?, payment_date = ?, payment_method = ?, description = ? WHERE id = ? AND client_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sdsssii", $paymentType, $newAmount, $paymentDate, $paymentMethod, $description, $paymentId, $id);
                $stmt->execute();

                // Update remaining balance in user_info
                $newRemainingBalance = $remainingBalance - $amountDifference;
                $updateBalanceQuery = "UPDATE user_info SET remaining_balance = ? WHERE id = ?";
                $stmt = $conn->prepare($updateBalanceQuery);
                $stmt->bind_param("di", $newRemainingBalance, $id);
                $stmt->execute();

                // Commit transaction
                $conn->commit();

                $_SESSION['success'] = "Payment updated successfully";
                header("Location: billing.php?id=$id");
                exit();
            } else {
                throw new Exception("Payment record not found");
            }
        } catch (Exception $e) {
            // An error occurred, rollback the transaction
            $conn->rollback();
            $_SESSION['error'] = "Error updating payment: " . $e->getMessage();
        }
    }
}

// Process payment deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_payment'])) {
    $paymentId = (int)$_POST['payment_id'];

    // Start transaction
    $conn->begin_transaction();
    try {
        // Get the payment amount being deleted
        $paymentAmountQuery = "SELECT amount FROM client_payments WHERE id = ? AND client_id = ?";
        $stmt = $conn->prepare($paymentAmountQuery);
        $stmt->bind_param("ii", $paymentId, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $paymentRow = $result->fetch_assoc();

        if ($paymentRow) {
            $paymentAmount = $paymentRow['amount'];

            // Delete the payment record
            $sql = "DELETE FROM client_payments WHERE id = ? AND client_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $paymentId, $id);
            $stmt->execute();

            // Update remaining balance (add back the deleted payment amount)
            $newRemainingBalance = $remainingBalance + $paymentAmount;
            $updateBalanceQuery = "UPDATE user_info SET remaining_balance = ? WHERE id = ?";
            $stmt = $conn->prepare($updateBalanceQuery);
            $stmt->bind_param("di", $newRemainingBalance, $id);
            $stmt->execute();

            // Commit transaction
            $conn->commit();

            $_SESSION['success'] = "Payment deleted successfully";
            header("Location: billing.php?id=$id");
            exit();
        } else {
            throw new Exception("Payment record not found");
        }
    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        $conn->rollback();
        $_SESSION['error'] = "Error deleting payment: " . $e->getMessage();
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
        <!-- Payment History Table with Fixed Height and Scroll -->
        <div class="overflow-x-auto max-h-96 overflow-y-auto border border-gray-200 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0 z-10">
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
                                        <?php elseif ($payment['payment_method'] == 'gcash'): ?>
                                            <i class="fas fa-mobile-alt text-purple-500 mr-1"></i>
                                        <?php else: ?>
                                            <i class="fas fa-credit-card text-gray-500 mr-1"></i>
                                        <?php endif; ?>
                                        <?= ucfirst($payment['payment_method']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= htmlspecialchars($payment['description']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex space-x-2">
                                        <button type="button" class="text-indigo-600 hover:text-indigo-900 edit-payment"
                                            data-id="<?= $payment['id'] ?>"
                                            data-type="<?= $payment['payment_type'] ?>"
                                            data-amount="<?= $payment['amount'] ?>"
                                            data-date="<?= $payment['payment_date'] ?>"
                                            data-method="<?= $payment['payment_method'] ?>"
                                            data-description="<?= $payment['description'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="text-red-600 hover:text-red-900 delete-payment"
                                            data-id="<?= $payment['id'] ?>"
                                            data-amount="<?= $payment['amount'] ?>">
                                            <i class="fas fa-trash-alt"></i>
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
                        <!-- Downpayment -->
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Downpayment (50%)</span>
                            <div class="text-right">
                                <span class="font-semibold text-gray-900">₱<?= number_format($downpaymentPaid, 2) ?></span>
                                <span class="text-xs text-gray-500"> / ₱<?= number_format($downpayment, 2) ?></span>
                            </div>
                        </div>

                        <!-- Before Installation -->
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Before Installation (40%)</span>
                            <div class="text-right">
                                <span class="font-semibold text-gray-900">₱<?= number_format($beforeInstallationPaid, 2) ?></span>
                                <span class="text-xs text-gray-500"> / ₱<?= number_format($beforeInstallation, 2) ?></span>
                            </div>
                        </div>

                        <!-- After Installation -->
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">After Installation (10%)</span>
                            <div class="text-right">
                                <span class="font-semibold text-gray-900">₱<?= number_format($afterInstallationPaid, 2) ?></span>
                                <span class="text-xs text-gray-500"> / ₱<?= number_format($afterInstallation, 2) ?></span>
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        <!-- Total Project Cost -->
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Total Project Cost</span>
                            <span class="font-bold text-gray-900">₱<?= number_format($totalProjectCost, 2) ?></span>
                        </div>

                        <!-- Total Paid -->
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Total Paid</span>
                            <span class="font-bold text-green-600">₱<?= number_format($totalPaid, 2) ?></span>
                        </div>

                        <!-- Remaining Balance -->
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                            <span class="text-sm font-medium text-gray-700">Remaining Balance</span>
                            <span class="font-bold <?= ($remainingBalance > 0) ? 'text-red-600' : 'text-green-600' ?>">
                                ₱<?= number_format($remainingBalance, 2) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg m-4 modal-enter overflow-hidden">
            <div class="px-6 py-4 bg-primary-50 border-b border-primary-100">
                <h3 class="text-lg font-semibold text-primary-800">Add Payment</h3>
            </div>
            <form method="POST" action="" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label for="payment_type" class="block text-sm font-medium text-gray-700 mb-1">Payment Type</label>
                        <select id="payment_type" name="payment_type" class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm">
                            <option value="downpayment">Downpayment (50%)</option>
                            <option value="before_installation">Before Installation (40%)</option>
                            <option value="after_installation">After Installation (10%)</option>
                        </select>
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (₱)</label>
                        <input type="number" step="0.01" min="0" id="amount" name="amount" required
                            class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm">
                    </div>

                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">Payment Date</label>
                        <input type="date" id="payment_date" name="payment_date" required value="<?= date('Y-m-d') ?>"
                            class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm">
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <select id="payment_method" name="payment_method" class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm">
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="check">Check</option>
                            <option value="gcash">GCash</option>
                            <option value="credit_card">Credit Card</option>
                        </select>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="2"
                            class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" id="closePaymentModal" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" name="add_payment" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        Add Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Payment Modal -->
    <div id="editPaymentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg m-4 modal-enter overflow-hidden">
            <div class="px-6 py-4 bg-indigo-50 border-b border-indigo-100">
                <h3 class="text-lg font-semibold text-indigo-800">Edit Payment</h3>
            </div>
            <form method="POST" action="" id="editPaymentForm" class="p-6">
                <input type="hidden" id="edit_payment_id" name="payment_id">
                <div class="space-y-4">
                    <div>
                        <label for="edit_payment_type" class="block text-sm font-medium text-gray-700 mb-3">Payment Type</label>
                        <select id="edit_payment_type" name="payment_type" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm">
                            <option value="downpayment">Downpayment (50%)</option>
                            <option value="before_installation">Before Installation (40%)</option>
                            <option value="after_installation">After Installation (10%)</option>
                        </select>
                    </div>

                    <div>
                        <label for="edit_amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (₱)</label>
                        <input type="number" step="0.01" min="0" id="edit_amount" name="amount" required
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500  shadow-sm">
                    </div>

                    <div>
                        <label for="edit_payment_date" class="block text-sm font-medium text-gray-700 mb-1">Payment Date</label>
                        <input type="date" id="edit_payment_date" name="payment_date" required
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm">
                    </div>

                    <div>
                        <label for="edit_payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <select id="edit_payment_method" name="payment_method" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm">
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="check">Check</option>
                            <option value="gcash">GCash</option>
                            <option value="credit_card">Credit Card</option>
                        </select>
                    </div>

                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="edit_description" name="description" rows="2"
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" id="closeEditPaymentModal" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" name="update_payment" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        Update Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Payment Confirmation Modal -->
    <div id="deletePaymentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md m-4 modal-enter overflow-hidden">
            <div class="px-6 py-4 bg-red-50 border-b border-red-100">
                <h3 class="text-lg font-semibold text-red-800">Confirm Delete</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-700">Are you sure you want to delete this payment of <span id="delete_amount" class="font-semibold">₱0.00</span>? This action cannot be undone.</p>

                <form method="POST" action="" id="deletePaymentForm" class="mt-6 flex justify-end space-x-3">
                    <input type="hidden" id="delete_payment_id" name="payment_id">
                    <button type="button" id="closeDeletePaymentModal" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" name="delete_payment" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Delete Payment
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div id="editProjectCostModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md m-4 modal-enter overflow-hidden">
            <div class="px-6 py-4 bg-primary-50 border-b border-primary-100">
                <h3 class="text-lg font-semibold text-primary-800">Edit Total Project Cost</h3>
            </div>
            <form method="POST" action="" id="editProjectCostForm" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label for="new_project_cost" class="block text-sm font-medium text-gray-700 mb-1">Total Project Cost (₱)</label>
                        <input type="number" step="0.01" min="0" id="new_project_cost" name="new_project_cost" required value="<?= $totalProjectCost ?>"
                            class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm">
                    </div>
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Updating the project cost will automatically recalculate the remaining balance and milestone amounts.
                    </p>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" id="closeProjectCostModal" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="button" id="confirmProjectCostBtn" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        Update Cost
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirm Project Cost Update Modal -->
    <div id="confirmProjectCostModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md m-4 modal-enter overflow-hidden">
            <div class="px-6 py-4 bg-orange-50 border-b border-orange-100">
                <h3 class="text-lg font-semibold text-orange-800 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Confirm Update
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <p class="text-gray-700">
                        Are you sure you want to update the total project cost from
                        <span class="font-semibold text-gray-900">₱<?= number_format($totalProjectCost, 2) ?></span>
                        to <span id="confirmNewCost" class="font-semibold text-primary-600">₱0.00</span>?
                    </p>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                        <p class="text-sm text-yellow-800">
                            <i class="fas fa-warning mr-1"></i>
                            <strong>Warning:</strong> This will automatically recalculate:
                        </p>
                        <ul class="text-sm text-yellow-700 mt-2 space-y-1">
                            <li>• Remaining balance</li>
                            <li>• Downpayment amount (50%)</li>
                            <li>• Before installation amount (40%)</li>
                            <li>• After installation amount (10%)</li>
                        </ul>
                    </div>
                </div>

                <form method="POST" action="" id="confirmProjectCostForm" class="mt-6 flex justify-end space-x-3">
                    <input type="hidden" id="confirm_new_project_cost" name="new_project_cost">
                    <button type="button" id="cancelProjectCostUpdate" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        No, Cancel
                    </button>
                    <button type="submit" name="update_project_cost" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                        Yes, Update Cost
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div id="successAlert" class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-md flex items-center z-50">
            <i class="fas fa-check-circle mr-2"></i>
            <span><?= $_SESSION['success'] ?></span>
            <button class="ml-7" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <script>
            setTimeout(function() {
                document.getElementById('successAlert').style.display = 'none';
            }, 5000);
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div id="errorAlert" class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-md flex items-center z-50">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span><?= $_SESSION['error'] ?></span>
            <button class="ml-auto" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <script>
            setTimeout(function() {
                document.getElementById('errorAlert').style.display = 'none';
            }, 5000);
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <script src="js/billing/modalpaymenthistory.js"></script>
    <script src="js/billing/modal.js"></script>
    <script src="js/billing/billing.js"></script>
    <script src="js/billing/updatehistorydel.js"></script>

</body>

</html>