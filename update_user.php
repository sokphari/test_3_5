<?php
include 'config.php';

// Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate all required inputs
if (empty($_POST['id']) || empty($_POST['name']) || empty($_POST['gender']) || empty($_POST['email']) || empty($_POST['password'])) {
    die("ID, name, gender, email, and password are required. Please check your input.");
}

try {
    $id = intval($_POST['id']);
    $name = trim($_POST['name'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = password_hash(trim($_POST['password'] ?? ''), PASSWORD_BCRYPT);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Use prepared statements to prevent SQL injection
    $sql = "UPDATE `users` SET name = ?, gender = ?, email = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $gender, $email, $password, $id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "success";
        } else {
            echo "No changes made or user not found";
        }
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

$conn->close();
?>