<?php
session_start();
include '../../connection/connection.php';

// ✅ Update description logic (edit form)
if (isset($_POST['update_description'])) {
    $update_id = (int)$_POST['update_id'];
    $description = trim($_POST['description']);

    // Ensure description is not empty
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

    // Redirect back to the client update page after update
    header("Location: client_update.php?id=" . (int)$_POST['client_id']);
    exit;
}

// ✅ New step insert logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $client_id = isset($_POST['client_id']) ? (int)$_POST['client_id'] : 0;
    $step = isset($_POST['step']) ? (int)$_POST['step'] : 0;
    $update_date = $_POST['update_date'] ?? '';
    $update_time = $_POST['update_time'] ?? '';
    $update_datetime = $update_date . ' ' . $update_time;
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
    $description = !empty($_POST['description']) ? trim($_POST['description']) : null;

    // Ensure required fields are provided
    if ($client_id && $step && $update_date && $update_time) {
        // Insert new update record even for duplicate steps
        $stmt = $conn->prepare("INSERT INTO step_updates (client_id, step, update_time, end_date, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $client_id, $step, $update_datetime, $end_date, $description);

        if ($stmt->execute()) {
            // Optional: Update overall status if it's the final step (step 9)
            if ($step === 9) {
                $statusStmt = $conn->prepare("UPDATE user_info SET updatestatus = 2 WHERE id = ?");
                $statusStmt->bind_param("i", $client_id);
                if (!$statusStmt->execute()) {
                    $_SESSION['error'] = "Error updating client status: " . $statusStmt->error;
                }
                $statusStmt->close();
            }

            $_SESSION['success'] = "Successfully!";
        } else {
            $_SESSION['error'] = "Error inserting step update: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Missing required data.";
    }

    // Redirect back to the client update page after inserting step
    header("Location: client_update.php?id=$client_id");
    exit;
}
?>
