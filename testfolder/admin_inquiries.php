<?php
include '../connection/connection.php';
$inquiries = $conn->query("SELECT * FROM inquiries ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Inquiries</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 font-sans">
  <div class="max-w-5xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Client Inquiries</h1>
    <?php while ($row = $inquiries->fetch_assoc()): ?>
      <div class="bg-white p-4 rounded shadow mb-4">
        <p><strong>Name:</strong> <?= htmlspecialchars($row['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
        <p><strong>Message:</strong> <?= nl2br(htmlspecialchars($row['message'])) ?></p>
        <p><strong>Reply:</strong> <?= $row['reply'] ? nl2br(htmlspecialchars($row['reply'])) : '<span class="text-gray-500">No reply yet</span>' ?></p>

        <?php if (!$row['reply']): ?>
          <form action="reply_inquiry.php" method="POST" class="mt-4 space-y-2">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <textarea name="reply" required class="w-full border p-2 rounded" placeholder="Your reply here..."></textarea>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Send Reply</button>
          </form>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  </div>
</body>
</html>
