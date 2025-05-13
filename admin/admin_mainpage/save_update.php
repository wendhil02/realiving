<?php
session_start();
include '../../connection/connection.php';

// ✅ Update description logic (edit form)
if (isset($_POST['update_description'])) {
    $update_id = (int)$_POST['update_id'];
    $description = trim($_POST['description']);

    if (!empty($description)) {
        $stmt = $conn->prepare("UPDATE step_updates SET description = ? WHERE id = ?");
        $stmt->bind_param("si", $description, $update_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Description updated successfully!";
        } else {
            $_SESSION['error'] = "Error updating description: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Description cannot be empty.";
    }

    header("Location: client_update.php?id=" . (int)$_POST['client_id']);
    exit;
}

// ✅ Insert new step logic with optional revision_count
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = isset($_POST['client_id']) ? (int)$_POST['client_id'] : 0;
    $step = isset($_POST['step']) ? (int)$_POST['step'] : 0;
    $update_date = $_POST['update_date'] ?? '';
    $update_time = $_POST['update_time'] ?? '';
    $update_datetime = $update_date . ' ' . $update_time;
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
    $description = !empty($_POST['description']) ? trim($_POST['description']) : null;
    $revisionCount = ($step === 4 && isset($_POST['revisionCount']) && is_numeric($_POST['revisionCount'])) ? (int)$_POST['revisionCount'] : null;

    if ($client_id && $step && $update_date && $update_time) {
        if ($step === 4) {
            // Step 4 needs revision_count
            $stmt = $conn->prepare("INSERT INTO step_updates (client_id, step, update_time, end_date, description, revision_count) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisssi", $client_id, $step, $update_datetime, $end_date, $description, $revisionCount);

            // Save the revision_count in user_info (assuming there's a revision_count column in user_info)
            $updateRevisionStmt = $conn->prepare("UPDATE user_info SET revision_count = ? WHERE id = ?");
            $updateRevisionStmt->bind_param("ii", $revisionCount, $client_id);
            $updateRevisionStmt->execute();
            $updateRevisionStmt->close();
        } else {
            // Other steps don't use revision_count
            $stmt = $conn->prepare("INSERT INTO step_updates (client_id, step, update_time, end_date, description) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iisss", $client_id, $step, $update_datetime, $end_date, $description);
        }

        if ($stmt->execute()) {
            // Optionally update status in user_info
            $updateStatusStmt = $conn->prepare("UPDATE user_info SET updatestatus = ? WHERE id = ?");
            $updateStatusStmt->bind_param("ii", $step, $client_id);
            $updateStatusStmt->execute();
            $updateStatusStmt->close();

            $_SESSION['success'] = "Step update saved successfully!";
        } else {
            $_SESSION['error'] = "Error inserting step update: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Missing required fields.";
    }

    header("Location: client_update.php?id=$client_id");
    exit;
}
?>
