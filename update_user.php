<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $fullname = $_POST['fullname'];
    $father_name = $_POST['father_name'];
    $cnic = $_POST['cnic'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $conn = new mysqli("localhost", "root", "", "user_management");
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

    $stmt = $conn->prepare("UPDATE users SET fullname=?, father_name=?, cnic=?, phone=?, address=? WHERE id=?");
    $stmt->bind_param("sssssi", $fullname, $father_name, $cnic, $phone, $address, $id);
    $stmt->execute();

    header("Location: mange_users.php?msg=updated");
    $stmt->close();
    $conn->close();
}
?>
