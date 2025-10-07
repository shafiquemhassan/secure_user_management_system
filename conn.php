<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "user_management";

try {
   
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

   
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


} catch (PDOException $e) {
    
    die("<div class='alert alert-danger'>Database connection failed: " . $e->getMessage() . "</div>");
}
?>
