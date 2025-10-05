<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger'>Session expired. Please log in again.</div>";
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "user_management";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("<div class='alert alert-danger'>Database connection failed.</div>");
}

$user_id = $_SESSION['user_id'];
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// Fetch current password hash
$sql = "SELECT password FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo "<div class='alert alert-danger'>User not found.</div>";
    exit();
}

$stmt->bind_result($hashed_password);
$stmt->fetch();

// Verify current password
if (!password_verify($current_password, $hashed_password)) {
    echo "<div class='alert alert-danger'>Current password is incorrect.</div>";
    exit();
}

// Confirm new passwords match
if ($new_password !== $confirm_password) {
    echo "<div class='alert alert-warning'>New password and confirm password do not match.</div>";
    exit();
}

// Hash and update new password
$new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
$update_sql = "UPDATE users SET password = ? WHERE id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("si", $new_hashed, $user_id);

if ($update_stmt->execute()) {
    echo "<div class='alert alert-success'>Password updated successfully!</div>";
} else {
    echo "<div class='alert alert-danger'>Error updating password.</div>";
}

$stmt->close();
$update_stmt->close();
$conn->close();
?>
