<?php
header('Content-Type: application/json');
include '../../connection/connection.php';

// Initialize counters
$newClientCount = 0;
$oldClientCount = 0;
$totalClientCount = 0;

$completedClients = 0;
$incompleteClients = 0;

$realivingClientCount = 0;
$noblehomeClientCount = 0;

// Check table existence
$tableCheck = $conn->query("SHOW TABLES LIKE 'user_info'");
$stepTableCheck = $conn->query("SHOW TABLES LIKE 'step_updates'");
if ($tableCheck->num_rows === 0 || $stepTableCheck->num_rows === 0) {
    echo json_encode(['error' => 'Required tables do not exist']);
    exit();
}

// Fetch clients
$clientsQuery = $conn->query("SELECT id, status, client_type FROM user_info");
if (!$clientsQuery) {
    echo json_encode(['error' => 'Failed to fetch clients']);
    exit();
}

while ($client = $clientsQuery->fetch_assoc()) {
    $clientId = $client['id'];
    $status = strtolower(trim($client['status']));
    $clientType = strtolower(trim($client['client_type'] ?? ''));

    // Count new/old clients
    if ($status === 'new client') {
        $newClientCount++;
    } elseif ($status === 'old client') {
        $oldClientCount++;
    }
    $totalClientCount++;

    // Count based on client_type
    if ($clientType === 'realiving') {
        $realivingClientCount++;
    } elseif ($clientType === 'noblehome') {
        $noblehomeClientCount++;
    }

    // Step 10 completion check
    $stepCheck = $conn->query("SELECT 1 FROM step_updates WHERE client_id = $clientId AND step = 10 LIMIT 1");
    if ($stepCheck) {
        if ($stepCheck->num_rows > 0) {
            $completedClients++;
        } else {
            $incompleteClients++;
        }
    } else {
        echo json_encode(['error' => 'Failed to check steps for client ID ' . $clientId]);
        exit();
    }
}

// Output JSON
echo json_encode([
    'newClientCount' => $newClientCount,
    'oldClientCount' => $oldClientCount,
    'totalClientCount' => $totalClientCount,
    'realivingClientCount' => $realivingClientCount,
    'noblehomeClientCount' => $noblehomeClientCount,
    'completedClients' => $completedClients,
    'incompleteClients' => $incompleteClients
]);
?>
