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
    die("<div class='alert alert-danger'>Database connection failed: " . $conn->connect_error . "</div>");
}

$user_id = $_POST['user_id'];
$fullname = $_POST['fullname'];
$father_name = $_POST['father_name'];
$cnic = $_POST['cnic'];
$phone = $_POST['phone'];
$address = $_POST['address'];

$target = "uploads/";
if (!is_dir($target)) {
    mkdir($target);
}

$profile_pic = "";
if (!empty($_FILES['profile_pic']['name'])) {
    $filename = basename($_FILES['profile_pic']['name']);
    $target_file = $target . $filename;

    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
        $profile_pic = $filename; // ✅ store only the filename in DB
    }
}

$sql = "UPDATE users SET fullname=?, father_name=?, cnic=?, phone=?, address=?, picture=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $fullname, $father_name, $cnic, $phone, $address, $profile_pic, $user_id);

if ($stmt->execute()) {
    echo "<div class='alert alert-success'>Profile updated successfully!</div>";
} else {
    echo "<div class='alert alert-danger'>Error updating profile.</div>";
}

$stmt->close();
$conn->close();
?>
