<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger'>Session expired. Please log in again.</div>";
    exit();
}

include 'conn.php'; // PDO connection

$user_id = $_SESSION['user_id']; // ✅ Get ID from session
$fullname = $_POST['fullname'];
$father_name = $_POST['father_name'];
$cnic = $_POST['cnic'];
$phone = $_POST['phone'];
$address = $_POST['address'];

$target = "uploads/";
if (!is_dir($target)) {
    mkdir($target, 0777, true);
}

$profile_pic = "";
if (!empty($_FILES['picture']['name'])) { // ✅ Use the same name as in your form
    $filename = time() . "_" . basename($_FILES['picture']['name']);
    $target_file = $target . $filename;

    if (move_uploaded_file($_FILES['picture']['tmp_name'], $target_file)) {
        $profile_pic = $filename;
    }
}

try {
    if (!empty($profile_pic)) {
        $sql = "UPDATE users 
                SET fullname = :fullname, father_name = :father_name, cnic = :cnic, 
                    phone = :phone, address = :address, picture = :picture 
                WHERE id = :id";
    } else {
        $sql = "UPDATE users 
                SET fullname = :fullname, father_name = :father_name, cnic = :cnic, 
                    phone = :phone, address = :address 
                WHERE id = :id";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':fullname', $fullname);
    $stmt->bindParam(':father_name', $father_name);
    $stmt->bindParam(':cnic', $cnic);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':id', $user_id);

    if (!empty($profile_pic)) {
        $stmt->bindParam(':picture', $profile_pic);
    }

    if ($stmt->execute()) {
         header("Location: user_dashboard.php?updated=success");
    } else {
        echo "<div class='alert alert-danger'>Error updating profile.</div>";
    }

} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Database error: " . htmlspecialchars($e->getMessage()) . "</div>";
}
?>
