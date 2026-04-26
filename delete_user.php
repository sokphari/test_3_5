<?php
include "config.php";

// Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    die("UserID is required");
}

$id = intval($_GET['id']); // Ensure it's an integer

$sql = "DELETE FROM `users` WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "Error preparing statement";
    exit;
}

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo "success";
    } else {
        echo "No user found with that ID";
    }
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>