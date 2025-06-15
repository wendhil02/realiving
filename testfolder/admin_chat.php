<?php
include '../connection/connection.php';

$ref = $_GET['ref'] ?? '';

if (!$ref) {
    echo "No reference number provided.";
    exit;
}

// Get client details using reference
$user = $conn->prepare("SELECT * FROM user_info WHERE reference_number = ?");
$user->bind_param("s", $ref);
$user->execute();
$user_result = $user->get_result();

if ($user_result->num_rows === 0) {
    echo "Invalid reference number.";
    exit;
}
$user_data = $user_result->fetch_assoc();

// Get messages
$messages = $conn->prepare("SELECT * FROM messages WHERE reference_number = ? ORDER BY created_at ASC");
$messages->bind_param("s", $ref);
$messages->execute();
$message_result = $messages->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Chat</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }
    .scrollbar-hide {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>
</head>

<body class="bg-gray-100">
  <div class="max-w-2xl mx-auto bg-white h-screen flex flex-col">
    <div class="p-4 border-b font-bold text-blue-800">
      Chat with <?= htmlspecialchars($user_data['clientname']) ?> – Ref: <?= htmlspecialchars($ref) ?>
    </div>

    <div id="chat-box" class="flex-1 overflow-y-auto p-4 space-y-2 scrollbar-hide">
      <?php while ($msg = $message_result->fetch_assoc()): ?>
        <div class="flex <?= $msg['sender'] === 'client' ? 'justify-start' : 'justify-end' ?>">
          <div class="max-w-sm px-4 py-2 rounded-lg <?= $msg['sender'] === 'client' ? 'bg-gray-200' : 'bg-blue-600 text-white' ?>">
            <?= nl2br(htmlspecialchars($msg['message'])) ?>
            <div class="text-[11px] text-right mt-1 opacity-70 <?= $msg['sender'] === 'client' ? 'text-gray-500' : 'text-white' ?>">
              <?= date("M d, Y h:i A", strtotime($msg['created_at'])) ?>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

    <form action="send_message.php" method="POST" class="p-4 border-t flex space-x-2">
      <input type="hidden" name="ref" value="<?= $ref ?>">
      <input type="hidden" name="sender" value="admin">
      <input type="text" name="message" placeholder="Type your message..." class="flex-1 border p-2 rounded" required>
      <button type="submit" class="bg-blue-600 text-white px-4 rounded">Send</button>
    </form>
  </div>

<script>
  const ref = "<?= $ref ?>";

  function loadMessages() {
    fetch(`load_messages.php?ref=${encodeURIComponent(ref)}`)
      .then(response => response.text())
      .then(data => {
        const chatBox = document.getElementById('chat-box');
        chatBox.innerHTML = data;

        // Auto-scroll to bottom
        chatBox.scrollTop = chatBox.scrollHeight;
      });
  }

  // Load initially
  loadMessages();

  // Load messages every 3 seconds
  setInterval(loadMessages, 3000);

  // Ensure scroll on first load (in case DOM finishes late)
  window.addEventListener('load', () => {
    const chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;
  });
</script>

</body>
</html>
