<?php
include '../connection/connection.php';

$result = $conn->query("SELECT clientname, reference_number FROM user_info ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin Chat List</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
  <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4 text-blue-800">Client Chat List</h1>
    <ul class="space-y-2">
      <?php while ($row = $result->fetch_assoc()): ?>
        <li class="border-b pb-2">
          <span class="font-semibold"><?= htmlspecialchars($row['clientname']) ?></span> — 
          Ref: <code><?= htmlspecialchars($row['reference_number']) ?></code><br>
          <a href="admin_chat.php?ref=<?= urlencode($row['reference_number']) ?>" class="text-blue-600 hover:underline">Open Chat</a>
        </li>
      <?php endwhile; ?>
    </ul>
  </div>
</body>
</html>
