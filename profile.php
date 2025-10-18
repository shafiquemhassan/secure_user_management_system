<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f9fafb;
      overflow-x: hidden;
    }

    .sidebar {
      height: 100vh;
      width: 250px;
      position: fixed;
      top: 0;
      left: 0;
      background-color: #1f2937;
      color: white;
      padding-top: 60px;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 12px 20px;
      font-size: 15px;
      transition: 0.3s;
    }

    .sidebar a:hover, .sidebar a.active {
      background-color: #2563eb;
    }

    .main-content {
      margin-left: 250px;
      padding: 30px;
      transition: 0.3s;
    }

    .profile-card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
      padding: 25px;
      max-width: 850px;
      margin: auto;
    }

    .profile-image {
      width: 130px;
      height: 130px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #2563eb;
    }

    .form-label {
      font-weight: 600;
      color: #374151;
    }

    .btn-primary {
      background-color: #2563eb;
      border: none;
      transition: 0.3s;
    }

    .btn-primary:hover {
      background-color: #1e40af;
    }

    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
        padding: 15px;
      }
      .sidebar {
        display: none;
      }
    }
  </style>
</head>

<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h4 class="text-center fw-bold mb-4">User Panel</h4>
    <a href="user_dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a>
    <a href="profile.php" class="active"><i class="fa-solid fa-user"></i> Profile</a>
    <a href="change_password.php"><i class="fa-solid fa-key"></i> Change Password</a>
    <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="profile-card">
      <h3 class="fw-bold mb-4 text-center text-primary"><i class="fa-solid fa-user-circle"></i> User Profile</h3>

      <form action="update_profile.php" method="POST" enctype="multipart/form-data">
        <div class="text-center mb-4">
          <img id="previewImg" src="https://via.placeholder.com/130" alt="Profile Image" class="profile-image mb-3">
          <div>
            <label class="btn btn-sm btn-outline-primary">
              <i class="fa-solid fa-upload"></i> Upload Image
              <input type="file" name="picture" accept="image/*" hidden onchange="previewFile(this)">
            </label>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Full Name</label>
            <input type="text" name="fullname" class="form-control" placeholder="Enter your full name" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Father's Name</label>
            <input type="text" name="father_name" class="form-control" placeholder="Enter your father's name" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">CNIC</label>
            <input type="text" name="cnic" class="form-control" placeholder="12345-6789012-3" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" placeholder="Enter your phone number" required>
          </div>

          <div class="col-12">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="2" placeholder="Enter your address" required></textarea>
          </div>

          <div class="col-12 text-center mt-4">
            <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-save"></i> Save Details</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Show image preview
    function previewFile(input) {
      const file = input.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('previewImg').src = e.target.result;
        reader.readAsDataURL(file);
      }
    }
  </script>

</body>
</html>
