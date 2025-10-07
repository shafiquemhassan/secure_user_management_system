<?php
session_start();
include 'conn.php';

$email = $_POST['email'];
$password = $_POST['password'];

try {
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify hashed password
        if (password_verify($password, $user['password'])) {
            // Store user info in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect or send success response
            if ($user['role'] === 'admin') {
                echo "success|admin_dashboard.php";
            } else {
                echo "success|user_dashboard.php";
            }
        } else {
            echo "<div class='alert alert-danger'>Invalid password.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>No account found with this email.</div>";
    }
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Database error: " . htmlspecialchars($e->getMessage()) . "</div>";
}
?>
