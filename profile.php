<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <style>
    body {
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
      transition: all 0.3s;
      padding-top: 60px;
    }
    .sidebar.collapsed {
      width: 70px;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 12px 20px;
      transition: 0.3s;
      font-size: 15px;
    }
    .sidebar a:hover, .sidebar a.active {
      background-color: #2563eb;
    }
    .sidebar i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }
    .main-content {
      margin-left: 250px;
      transition: all 0.3s;
      padding: 20px;
    }
    .collapsed + .main-content {
      margin-left: 70px;
    }
    .toggle-btn {
      position: fixed;
      top: 15px;
      left: 15px;
      font-size: 22px;
      color: #2563eb;
      cursor: pointer;
      z-index: 1000;
    }
    .card-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }
  </style>
</head>
<body class="bg-light">

  <div class="sidebar" id="sidebar">
    <h4 class="text-center fw-bold mb-4">User Panel</h4>
    <a href="user_dashboard.php" class="active"><i class="fa-solid fa-gauge"></i>Dashboard</a>
    <a href="profile.php"><i class="fa-solid fa-users"></i>Profile</a>
    <a href="change_password.php"><i class="fa-solid fa-users"></i>Change Password</a>
    <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i>logout Out</a>
  </div>

  

  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    <div class="container-fluid">
      <h2 class="fw-bold"><?php echo $_SESSION['username']; ?> Profile</h2>
      <div id="profileMsg"></div>
        <form id="userProfileForm" enctype="multipart/form-data">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="fullname" class="form-control" placeholder="Enter your full name" required>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Father's Name</label>
            <input type="text" name="father_name" class="form-control" placeholder="Enter your father's name" required>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">CNIC</label>
            <input type="text" name="cnic" class="form-control" placeholder="Enter CNIC e.g., 12345-6789012-3" required>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" placeholder="Enter your phone number" required>
          </div>

          <div class="col-12 mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" placeholder="Enter your address" rows="2" required></textarea>
          </div>

          <div class="col-12 mb-3">
            <label class="form-label">Profile Image</label>
            <input type="file" name="profile_pic" class="form-control" accept="image/*">
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Save Details</button>
      </form>
      </div>
    </div>
  </div>
<script>
    $(document).ready(function() {
      $("#userProfileForm").on("submit", function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append("user_id", "<?php echo $_SESSION['user_id']; ?>");

        $.ajax({
          url: "update_profile.php",
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,
          beforeSend: function() {
            $("#profileMsg").html('<div class="alert alert-info">Updating...</div>');
          },
          success: function(response) {
            $("#profileMsg").html(response);
          },
          error: function() {
            $("#profileMsg").html('<div class="alert alert-danger">Error updating profile.</div>');
          }
        });
      });
    });
  </script>

  </body>
</html>
