<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "user_management";

try {
    // Create PDO connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: show success message (useful for debugging)
    // echo "<div class='alert alert-success'>Database connected successfully!</div>";

} catch (PDOException $e) {
    // Display error message if connection fails
    die("<div class='alert alert-danger'>Database connection failed: " . $e->getMessage() . "</div>");
}
?>
