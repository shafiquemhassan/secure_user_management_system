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
      <h2 class="fw-bold">Dashboard</h2>
      <p class="text-muted">Welcome, <strong><?php echo $_SESSION['username']; ?></strong> (User)</p>

      <div class="card-stats">
        <div class="card text-white bg-primary shadow-sm p-3">
          <h5>Total Registered Users</h5>
          <h3>2</h3>
        </div>

        <div class="card text-dark bg-warning shadow-sm p-3">
          <h5>Yesterday Registered Users</h5>
          <h3>0</h3>
         
        </div>

        <div class="card text-white bg-success shadow-sm p-3">
          <h5>Registered in Last 7 Days</h5>
          <h3>0</h3>
          
        </div>

        <div class="card text-white bg-danger shadow-sm p-3">
          <h5>Registered in Last 30 Days</h5>
          <h3>0</h3>
          
        </div>

      </div>
    </div>
  </div>

  </body>
</html>
