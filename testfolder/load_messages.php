<?php
include '../connection/connection.php';

$ref = $_GET['ref'] ?? '';
if (!$ref) exit;

// Get client name
$get_user = $conn->prepare("SELECT clientname FROM user_info WHERE reference_number = ?");
$get_user->bind_param("s", $ref);
$get_user->execute();
$user_result = $get_user->get_result();
$clientname = $user_result->fetch_assoc()['clientname'] ?? 'Client';

// Get messages
$messages = $conn->prepare("SELECT * FROM messages WHERE reference_number = ? ORDER BY created_at ASC");
$messages->bind_param("s", $ref);
$messages->execute();
$result = $messages->get_result();

while ($msg = $result->fetch_assoc()):
    $senderName = $msg['sender'] === 'client' ? $clientname : 'Admin';
    $align = $msg['sender'] === 'client' ? 'justify-start' : 'justify-end';
    $bgClass = $msg['sender'] === 'client' ? 'bg-gray-200' : 'bg-blue-600 text-white';

    // Format date and time
    $dateTime = date("M d, Y h:i A", strtotime($msg['created_at'])); // Example: Jun 14, 2025 04:23 PM

    // Fetch replied message (if any)
    $repliedText = '';
    if (!empty($msg['replied_message_id'])) {
        $repStmt = $conn->prepare("SELECT message FROM messages WHERE id = ?");
        $repStmt->bind_param("i", $msg['replied_message_id']);
        $repStmt->execute();
        $repResult = $repStmt->get_result();
        if ($repRow = $repResult->fetch_assoc()) {
            $repliedText = $repRow['message'];
        }
    }
?>
<div class="flex <?= $align ?>">
    <div>
        <div class="text-xs text-gray-500 mb-1 <?= $msg['sender'] === 'client' ? 'text-left' : 'text-right' ?>">
            <?= htmlspecialchars($senderName) ?>
        </div>
        <div class="px-4 py-2 rounded-lg <?= $bgClass ?>">
            <!-- Quoted/Replied message -->
            <?php if ($repliedText): ?>
                <div class="text-sm italic text-gray-500 bg-white border-l-4 border-blue-400 pl-2 mb-1">
                    <?= htmlspecialchars($repliedText) ?>
                </div>
            <?php endif; ?>

            <!-- Main message -->
            <?= nl2br(htmlspecialchars($msg['message'])) ?>

            <!-- Time stamp -->
            <div class="text-[11px] text-right mt-1 opacity-70 <?= $msg['sender'] === 'client' ? 'text-gray-500' : 'text-white' ?>">
                <?= $dateTime ?>
            </div>
        </div>
    </div>
</div>
<?php endwhile; ?>
