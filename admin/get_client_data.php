<?php
header('Content-Type: application/json');
include '../connection/connection.php';

// Initialize client counts for status and steps
$newClientCount = 0;
$oldClientCount = 0;
$totalClientCount = 0;

$completedClients = 0;
$incompleteClients = 0;

// Check if required tables exist
$tableCheck = $conn->query("SHOW TABLES LIKE 'user_info'");
$stepTableCheck = $conn->query("SHOW TABLES LIKE 'step_updates'");
if ($tableCheck->num_rows === 0 || $stepTableCheck->num_rows === 0) {
    echo json_encode(['error' => 'Required tables do not exist']);
    exit();
}

// Fetch all clients from user_info table
$clientsQuery = $conn->query("SELECT id, status FROM user_info");
if (!$clientsQuery) {
    echo json_encode(['error' => 'Failed to fetch clients']);
    exit();
}

while ($client = $clientsQuery->fetch_assoc()) {
    $clientId = $client['id'];
    $status = strtolower(trim($client['status']));

    // Count client status
    if ($status === 'new client') {
        $newClientCount++;
    } elseif ($status === 'old client') {
        $oldClientCount++;
    }
    $totalClientCount++; // Count all rows regardless of status

    // Check if the client has completed step 10
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

// Return the counts in JSON format
echo json_encode([
    'newClientCount' => $newClientCount,
    'oldClientCount' => $oldClientCount,
    'totalClientCount' => $totalClientCount,
    'completedClients' => $completedClients,
    'incompleteClients' => $incompleteClients
]);
?>
