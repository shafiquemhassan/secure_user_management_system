<?php
include 'conn.php'; // Uses PDO connection

// Get form data
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];

try {
    // Check if email already exists
    $check = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $check->bindParam(':email', $email, PDO::PARAM_STR);
    $check->execute();

    if ($check->rowCount() > 0) {
        echo "<div class='alert alert-warning'>Email already registered!</div>";
        exit;
    }

    // Hash password before saving
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(':role', $role, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Registration successful!</div>";
    } else {
        echo "<div class='alert alert-danger'>Something went wrong while registering!</div>";
    }

} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Database error: " . htmlspecialchars($e->getMessage()) . "</div>";
}
?>
