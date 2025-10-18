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

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #2563eb;
    }

    .main-content {
      margin-left: 250px;
      padding: 40px;
    }

    .password-card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
      padding: 35px;
      max-width: 600px;
      margin: 40px auto;
      animation: fadeInUp 0.5s ease-in-out;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
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
        padding: 20px;
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
    <a href="profile.php"><i class="fa-solid fa-user"></i> Profile</a>
    <a href="change_password.php" class="active"><i class="fa-solid fa-lock"></i> Change Password</a>
    <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="password-card">
      <h3 class="fw-bold mb-4 text-center text-primary">
        <i class="fa-solid fa-lock"></i> Change Password
      </h3>

      <!-- Message -->
      <div id="passwordMsg"></div>

      <form id="changePasswordForm">
        <div class="mb-3">
          <label class="form-label">Current Password</label>
          <div class="input-group">
            <input name="current_password" class="form-control" type="password" placeholder="Enter current password" required>
            <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">New Password</label>
          <div class="input-group">
            <input name="new_password" class="form-control" type="password" placeholder="Enter new password" required>
            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <div class="input-group">
            <input name="confirm_password" class="form-control" type="password" placeholder="Confirm new password" required>
            <span class="input-group-text"><i class="fa-solid fa-check"></i></span>
          </div>
        </div>

        <div class="text-center mt-4">
          <button type="submit" class="btn btn-primary px-4">
            <i class="fa-solid fa-save"></i> Update Password
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    $(document).ready(function () {
      $("#changePasswordForm").on("submit", function (e) {
        e.preventDefault();

        $.ajax({
          url: "update_password.php",
          type: "POST",
          data: $(this).serialize(),
          beforeSend: function () {
            $("#passwordMsg").html('<div class="alert alert-info">Updating password...</div>');
          },
          success: function (response) {
            $("#passwordMsg").html(response);
            setTimeout(() => {
              $("#passwordMsg").fadeOut();
            }, 4000);
          },
          error: function () {
            $("#passwordMsg").html('<div class="alert alert-danger">Error updating password.</div>');
          }
        });
      });
    });
  </script>

</body>
</html>
