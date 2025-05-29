<?php
header('Content-Type: application/json');
include '../../../connection/connection.php';

if (isset($_GET['code'])) {
    $ref = $conn->real_escape_string($_GET['code']);

    $sql = "SELECT clientname, total_project_cost, remaining_balance FROM user_info WHERE reference_number = '$ref' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        echo json_encode([
            "success" => true,
            "clientname" => $row['clientname'],
            "total_project_cost" => $row['total_project_cost'],
            "remaining_balance" => $row['remaining_balance']
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Not found"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No code provided"]);
}
