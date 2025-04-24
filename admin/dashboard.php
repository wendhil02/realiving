<?php
session_start();

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
    header("Location: ../index.php");
    exit();
}

include 'design/siddebarmain.php';
include '../connection/connection.php';

function getClientProgress($conn, $clientId)
{
    $stmt = $conn->prepare("SELECT COUNT(*) AS completed_steps FROM step_updates WHERE client_id = ?");
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $result = $stmt->get_result();
    $completedSteps = 0;
    if ($result && $row = $result->fetch_assoc()) {
        $completedSteps = (int) $row['completed_steps'];
    }
    $totalSteps = 9;
    $progressPercent = ($completedSteps / $totalSteps) * 100;
    return number_format($progressPercent, 2);
}

$result = $conn->query("SELECT id, clientname FROM user_info ORDER BY created_at DESC");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Real Living Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex bg-gray-200">
    <!-- Main Content -->
    <div class="flex-1 flex flex-col items-center justify-center px-6">
        <!-- Main Section -->
        <main class="w-full max-w-6xl px-6 py-10">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Client Progress Overview</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="overflow-x-auto rounded-lg shadow">
                <table class="min-w-full bg-white rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
                        <tr>
                            <th class="px-6 py-4 text-left">Client Name</th>
                            <th class="px-6 py-4 text-left">Progress</th>
                            <th class="px-6 py-4 text-left">Completion</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm divide-y divide-gray-200">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <?php
                                $clientId = $row['id'];
                                $clientName = htmlspecialchars($row['clientname']);
                                $progress = getClientProgress($conn, $clientId);

                                $progressColor = 'bg-red-500';
                                if ($progress > 70) {
                                    $progressColor = 'bg-green-500';
                                } elseif ($progress > 30) {
                                    $progressColor = 'bg-yellow-400';
                                }
                            ?>
                            <tr class="hover:bg-gray-100 transition-all">
                                <td class="px-6 py-4 font-medium"><?= $clientName ?></td>
                                <td class="px-6 py-4">
                                    <div class="w-full bg-gray-200 rounded-full h-5">
                                        <div class="<?= $progressColor ?> h-5 rounded-full flex items-center justify-center text-black text-xs font-bold transition-all" style="width: <?= $progress ?>%;">
                                            <?= $progress ?>%
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4"><?= $progress ?>% Completed</td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-gray-600 text-center mt-10">No clients found.</p>
        <?php endif; ?>
    </main>

    </div>
</body>

</html>
