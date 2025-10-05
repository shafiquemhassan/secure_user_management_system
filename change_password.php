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
  <title>Change Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <style>
    body { overflow-x: hidden; }
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
    .main-content {
      margin-left: 250px;
      transition: all 0.3s;
      padding: 20px;
    }
  </style>
</head>
<body class="bg-light">

  <!-- Sidebar -->
  <div class="sidebar">
    <h4 class="text-center fw-bold mb-4">User Panel</h4>
    <a href="user_dashboard.php"><i class="fa-solid fa-gauge"></i>Dashboard</a>
    <a href="profile.php"><i class="fa-solid fa-user"></i>Profile</a>
    <a href="change_password.php" class="active"><i class="fa-solid fa-lock"></i>Change Password</a>
    <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container-fluid">
      <h2 class="fw-bold mb-4">Change Password</h2>

      <!-- ✅ Message placeholder -->
      <div id="passwordMsg"></div>

      <form id="changePasswordForm">
        <div class="mb-3">
          <label class="form-label">Current Password</label>
          <input name="current_password" class="form-control" type="password" required>
        </div>

        <div class="mb-3">
          <label class="form-label">New Password</label>
          <input name="new_password" class="form-control" type="password" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input name="confirm_password" class="form-control" type="password" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Password</button>
      </form>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $("#changePasswordForm").on("submit", function(e) {
        e.preventDefault();

        $.ajax({
          url: "update_password.php",
          type: "POST",
          data: $(this).serialize(),
          beforeSend: function() {
            $("#passwordMsg").html('<div class="alert alert-info">Updating password...</div>');
          },
          success: function(response) {
            $("#passwordMsg").html(response);
            setTimeout(() => { $("#passwordMsg").fadeOut(); }, 4000);
          },
          error: function() {
            $("#passwordMsg").html('<div class="alert alert-danger">Error updating password.</div>');
          }
        });
      });
    });
  </script>

</body>
</html>
