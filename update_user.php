<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $fullname = $_POST['fullname'];
    $father_name = $_POST['father_name'];
    $cnic = $_POST['cnic'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    include 'conn.php'; // Uses PDO connection

    try {
        // Prepare and execute the update query
        $stmt = $conn->prepare("
            UPDATE users 
            SET fullname = :fullname, 
                father_name = :father_name, 
                cnic = :cnic, 
                phone = :phone, 
                address = :address 
            WHERE id = :id
        ");

        // Bind parameters securely
        $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $stmt->bindParam(':father_name', $father_name, PDO::PARAM_STR);
        $stmt->bindParam(':cnic', $cnic, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        // Redirect after successful update
        header("Location: mange_users.php?msg=updated");
        exit();

    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Error updating user: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}
?>
