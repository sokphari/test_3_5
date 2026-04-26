<?php
include "config.php";

// Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    die("UserID is required");
}

$id = intval($_GET['id']);

$sql = "SELECT id, name, gender, email FROM `users` WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Prepare failed"]);
    exit;
}

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo json_encode(["error" => "User not found"]);
    }
} else {
    echo json_encode(["error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>