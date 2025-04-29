<?php
session_start();

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
    header("Location: ../loginpage/index.php");
    exit();
}

include 'design/mainbody.php';
include '../connection/connection.php';

// Function to calculate progress
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
    if ($totalSteps === 0) return 0;

    $progressPercent = ($completedSteps / $totalSteps) * 100;
    return round($progressPercent, 1);
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
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .progress-bar {
            transition: width 0.5s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex-1 flex flex-col items-center justify-start px-4 py-12">
        <main class="w-full max-w-6xl bg-white p-8 rounded-xl shadow-md">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-10">📈 Client Progress Overview</h2>

            <?php if ($result->num_rows > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white text-sm text-left text-gray-800 border border-gray-200 rounded-lg overflow-hidden">
                        <thead class="bg-gray-100 text-gray-600 uppercase">
                            <tr>
                                <th class="px-6 py-4">Client Name</th>
                                <th class="px-6 py-4">Progress</th>
                                <th class="px-6 py-4">Completion</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <?php
                                    $clientId = $row['id'];
                                    $clientName = htmlspecialchars($row['clientname']);
                                    $progress = getClientProgress($conn, $clientId);

                                    $progressColor = 'bg-red-500';
                                    if ($progress >= 80) {
                                        $progressColor = 'bg-green-500';
                                    } elseif ($progress >= 50) {
                                        $progressColor = 'bg-yellow-400';
                                    } elseif ($progress >= 20) {
                                        $progressColor = 'bg-orange-400';
                                    }
                                ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-medium"><?= $clientName ?></td>
                                    <td class="px-6 py-4 w-3/5">
                                        <div class="w-full bg-gray-200 rounded-full h-6 overflow-hidden">
                                            <div class="<?= $progressColor ?> h-6 text-center text-white text-xs font-semibold flex items-center justify-center progress-bar" style="width: <?= $progress ?>%;">
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
                <p class="text-center text-gray-500 mt-8">No client records found.</p>
            <?php endif; ?>
        </main>
    </div>

</body>
</html>
