<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'config.php';

// Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate all required inputs
if (empty($_POST['name']) || empty($_POST['gender']) || empty($_POST['email']) || empty($_POST['password'])) {
    die("Name, gender, email, and password are required. Please check your input.");
}

try {
    $name = trim($_POST['name'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = password_hash(trim($_POST['password'] ?? ''), PASSWORD_BCRYPT);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Use prepared statements to prevent SQL injection
    $sql = "INSERT INTO `users` (name, gender, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $gender, $email, $password);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

$conn->close();
?>
