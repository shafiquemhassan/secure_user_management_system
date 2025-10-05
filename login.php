<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "user_management";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("<div class='alert alert-danger'>Database connection failed: " . $conn->connect_error . "</div>");
}

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

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

$conn->close();
?>
