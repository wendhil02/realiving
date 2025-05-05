<?php
include '../design/mainbody.php';
include '../../connection/connection.php';
session_start();

// Fetch inquiries
$inquiries = $conn->query("SELECT * FROM contact_inquiries ORDER BY created_at DESC");

// Fetch admin emails, client_status, and role
$admins = $conn->query("SELECT id, email, client_status, role FROM account WHERE role LIKE 'admin%'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inquiries Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-center mb-8 text-blue-800">📥 New Inquiries</h1>

        <div class="space-y-6">
            <?php while ($row = $inquiries->fetch_assoc()): ?>
                <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
                    <p class="text-gray-700"><strong>Name:</strong> <?= htmlspecialchars($row['full_name']) ?></p>
                    <p class="text-gray-700"><strong>Phone:</strong> <?= htmlspecialchars($row['phone_number']) ?></p>
                    <p class="text-gray-700"><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
                    <p class="text-gray-600 text-sm italic mt-1">
                        <strong>Submitted:</strong>
                        <?= date('F j, Y \a\t g:i A', strtotime($row['created_at'])) ?>
                    </p>

                    <?php if ($row['sent_to_admin']): ?>
                        <div class="mt-4 text-green-600 font-medium">Sent to Admin</div>
                    <?php else: ?>
                        <form action="send_inquiry.php" method="POST" class="mt-4">
                            <input type="hidden" name="inquiry_id" value="<?= $row['id'] ?>">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select the admin to send to:</label>
                            <select name="recipient_email" required class="border rounded w-full p-2 focus:ring focus:ring-blue-200">
                                <option value="">-- Select Admin --</option>
                                <?php foreach ($admins as $admin): ?>
                                    <option value="<?= htmlspecialchars($admin['email']) ?>">
                                        <?= htmlspecialchars($admin['email']) ?> - <?= htmlspecialchars($admin['client_status']) ?> (<?= htmlspecialchars($admin['role']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow-sm">
                                Send 
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>


