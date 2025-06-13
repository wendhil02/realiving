<?php
session_start();
include '../../connection/connection.php';
include '../design/mainbody.php';
include '../checkrole.php';
require_role(['admin1', 'superadmin']);
// Fetch user_info records including primary key id
$sql = "SELECT id, clientname, status, nameproject, reference_number FROM user_info";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Info List</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <main class="p-6">
    <div class="max-w-7xl w-full bg-white p-6 rounded-xl shadow-lg mx-auto">
      <h1 class="text-3xl font-bold text-blue-900 mb-6">Quotation Requests</h1>

      <?php if ($result && $result->num_rows): ?>
      <div class="overflow-x-auto">
        <table class="w-full divide-y divide-gray-200">
          <thead class="bg-blue-900 text-yellow-400">
            <tr>
              <th class="px-6 py-3 text-left text-lg">Client Name</th>
              <th class="px-6 py-3 text-left text-lg">Project Name</th>
              <th class="px-6 py-3 text-left text-lg">Status</th>
              <th class="px-6 py-3 text-left text-lg">Reference #</th>
              <th class="px-6 py-3"></th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td class="px-6 py-4 text-lg"><?= htmlspecialchars($row['clientname']) ?></td>
              <td class="px-6 py-4 text-lg"><?= htmlspecialchars($row['nameproject']) ?></td>
              <td class="px-6 py-4 text-lg"><?= htmlspecialchars($row['status']) ?></td>
              <td class="px-6 py-4 text-lg"><?= htmlspecialchars($row['reference_number']) ?></td>
              <td class="px-6 py-4 text-right">
                <form method="GET" action="../installation_quotation/quotation.php">
                  <!-- Pass only the user_info record ID -->
                  <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                  <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md">
                    Open Quotation
                  </button>
                </form>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
        <p class="text-center text-gray-500 text-lg">No quotation requests found.</p>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>
