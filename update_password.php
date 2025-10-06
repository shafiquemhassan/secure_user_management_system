<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger'>Session expired. Please log in again.</div>";
    exit();
}

include 'conn.php'; // Uses PDO connection

$user_id = $_SESSION['user_id'];
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

try {
    // Fetch current password hash from database
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "<div class='alert alert-danger'>User not found.</div>";
        exit();
    }

    // Verify current password
    if (!password_verify($current_password, $user['password'])) {
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
    $update = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
    $update->bindParam(':password', $new_hashed, PDO::PARAM_STR);
    $update->bindParam(':id', $user_id, PDO::PARAM_INT);

    if ($update->execute()) {
        echo "<div class='alert alert-success'>Password updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating password.</div>";
    }

} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Database error: " . htmlspecialchars($e->getMessage()) . "</div>";
}
?>
