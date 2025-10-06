<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger'>Session expired. Please log in again.</div>";
    exit();
}

include 'conn.php'; // Uses PDO connection

$user_id = $_POST['user_id'];
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
if (!empty($_FILES['profile_pic']['name'])) {
    $filename = basename($_FILES['profile_pic']['name']);
    $target_file = $target . $filename;

    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
        $profile_pic = $filename; // store only filename in DB
    }
}

try {
    // If profile picture is uploaded, include it in update
    if (!empty($profile_pic)) {
        $sql = "UPDATE users 
                SET fullname = :fullname, father_name = :father_name, cnic = :cnic, 
                    phone = :phone, address = :address, picture = :picture 
                WHERE id = :id";
    } else {
        // If no new picture, don't overwrite existing picture
        $sql = "UPDATE users 
                SET fullname = :fullname, father_name = :father_name, cnic = :cnic, 
                    phone = :phone, address = :address 
                WHERE id = :id";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
    $stmt->bindParam(':father_name', $father_name, PDO::PARAM_STR);
    $stmt->bindParam(':cnic', $cnic, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

    if (!empty($profile_pic)) {
        $stmt->bindParam(':picture', $profile_pic, PDO::PARAM_STR);
    }

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Profile updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating profile.</div>";
    }

} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Database error: " . htmlspecialchars($e->getMessage()) . "</div>";
}
?>
