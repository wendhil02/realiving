<?php
include '../connection/connection.php';

$ref = $_GET['ref'];

// Get user details using reference
$user = $conn->prepare("SELECT * FROM user_info WHERE reference_number = ?");
$user->bind_param("s", $ref);
$user->execute();
$user_result = $user->get_result();

if ($user_result->num_rows === 0) {
    echo "Invalid reference number.";
    exit;
}
$user_data = $user_result->fetch_assoc();

// Get all messages for this ref
$messages = $conn->prepare("SELECT * FROM messages WHERE reference_number = ? ORDER BY created_at ASC");
$messages->bind_param("s", $ref);
$messages->execute();
$message_result = $messages->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Client Chat</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <div class="max-w-2xl mx-auto bg-white h-screen flex flex-col">
    <div class="p-4 border-b font-bold">Hello, <?= htmlspecialchars($user_data['clientname']) ?> – Ref: <?= $ref ?></div>

    <div id="chat-box" class="flex-1 overflow-y-auto p-4 space-y-2">
     <?php while ($msg = $message_result->fetch_assoc()): ?>
      <?php
        $repliedText = '';
        if ($msg['replied_message_id']) {
            $repStmt = $conn->prepare("SELECT message FROM messages WHERE id = ?");
            $repStmt->bind_param("i", $msg['replied_message_id']);
            $repStmt->execute();
            $repResult = $repStmt->get_result();
            if ($repRow = $repResult->fetch_assoc()) {
                $repliedText = $repRow['message'];
            }
        }
      ?>
      <div class="flex <?= $msg['sender'] === 'client' ? 'justify-start' : 'justify-end' ?>">
        <div class="max-w-sm px-4 py-2 rounded-lg <?= $msg['sender'] === 'client' ? 'bg-gray-200' : 'bg-blue-600 text-white' ?>">
          <?php if ($repliedText): ?>
            <div class="text-sm italic text-gray-500 border-l-4 border-gray-400 pl-2 mb-1">
              <?= htmlspecialchars($repliedText) ?>
            </div>
          <?php endif; ?>
          <?= nl2br(htmlspecialchars($msg['message'])) ?>
        </div>
      </div>
    <?php endwhile; ?>
    </div>

    <!-- Message Form -->
    <form action="send_message.php" method="POST" class="p-4 border-t flex space-x-2">
      <input type="hidden" name="ref" value="<?= $ref ?>">
      <input type="hidden" name="sender" value="client">
      <input type="text" name="message" placeholder="Type your message..." class="flex-1 border p-2 rounded" required>
      <button type="submit" class="bg-blue-600 text-white px-4 rounded">Send</button>
    </form>
  </div>

<script>
  const ref = "<?= $ref ?>";

  function loadMessages() {
    const chatBox = document.getElementById('chat-box');

    // Check if user is near the bottom (10px buffer)
    const isAtBottom = chatBox.scrollTop + chatBox.clientHeight >= chatBox.scrollHeight - 10;

    fetch(`load_messages.php?ref=${encodeURIComponent(ref)}`)
      .then(response => response.text())
      .then(data => {
        chatBox.innerHTML = data;

        // Auto-scroll only if user is at the bottom
        if (isAtBottom) {
          chatBox.scrollTop = chatBox.scrollHeight;
        }
      });
  }

  // Load initially
  loadMessages();

  // Refresh every 3 seconds
  setInterval(loadMessages, 3000);
</script>

</body>
</html>
