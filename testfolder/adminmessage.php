<?php
include '../connection/connection.php';

// Only allow POST
if ($_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST['ref'], $_POST['sender'], $_POST['message'])) {

    // Sanitize inputs
    $ref = trim($_POST['ref']);
    $sender = trim($_POST['sender']);
    $message = trim($_POST['message']);

    // Basic validation
    if (empty($ref) || empty($sender) || empty($message)) {
        die("All fields are required.");
    }

    // Validate if reference number exists
    $stmt = $conn->prepare("SELECT id FROM user_info WHERE reference_number = ?");
    $stmt->bind_param("s", $ref);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        die("Invalid Reference Number.");
    }

    // Insert the message (no reply feature)
    $stmt = $conn->prepare(
        "INSERT INTO messages (reference_number, sender, message) 
         VALUES (?, ?, ?)"
    );
    $stmt->bind_param("sss", $ref, $sender, $message);
    $stmt->execute();

    // Redirect back to the chat page
    header("Location: adminmessage.php?ref=" . urlencode($ref));
    exit;

} else {
    echo "Form not submitted correctly.";
}
?>
