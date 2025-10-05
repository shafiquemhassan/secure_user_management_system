<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
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
    table img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
    }
  </style>
</head>
<body class="bg-light">

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <h4 class="text-center fw-bold mb-4">Admin Panel</h4>
    <a href="admin_dashboard.php" class="active"><i class="fa-solid fa-gauge"></i>Dashboard</a>
    <a href="mange_users.php"><i class="fa-solid fa-users"></i>Manage Users</a>
    <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>
  </div>

 

  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    <div class="container-fluid">
      <h2 class="fw-bold mb-4">Users Details</h2>

      <?php
      // Database Connection
      $conn = new mysqli("localhost", "root", "", "user_management");
      if ($conn->connect_error) {
          die("<div class='alert alert-danger'>Database connection failed: " . $conn->connect_error . "</div>");
      }

      $sql = "SELECT id, fullname, father_name, cnic, phone, address, picture FROM users WHERE role='user'";
      $result = $conn->query($sql);
      ?>

      <div class="table-responsive shadow-sm">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Profile</th>
              <th scope="col">Name</th>
              <th scope="col">Father Name</th>
              <th scope="col">CNIC</th>
              <th scope="col">Phone</th>
              <th scope="col">Address</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
          <?php
          if ($result && $result->num_rows > 0) {
              $i = 1;
              while ($row = $result->fetch_assoc()) {
                  echo "
                  <tr>
                    <th scope='row'>{$i}</th>
                    <td><img src='uploads/{$row['picture']}' alt='Profile'></td>
                    <td>{$row['fullname']}</td>
                    <td>{$row['father_name']}</td>
                    <td>{$row['cnic']}</td>
                    <td>{$row['phone']}</td>
                    <td>{$row['address']}</td>
                    <td>
                      <button class='btn btn-sm btn-primary editBtn'
                              data-id='{$row['id']}'
                              data-name='{$row['fullname']}'
                              data-father='{$row['father_name']}'
                              data-cnic='{$row['cnic']}'
                              data-phone='{$row['phone']}'
                              data-address='{$row['address']}'
                              data-bs-toggle='modal'
                              data-bs-target='#editModal'>
                        <i class='fa-solid fa-pen'></i> Edit
                      </button>
                      <a href='delete_user.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure?');\">
                        <i class='fa-solid fa-trash'></i> Del
                      </a>
                    </td>
                  </tr>";
                  $i++;
              }
          } else {
              echo "<tr><td colspan='8' class='text-center text-muted'>No users found.</td></tr>";
          }
          $conn->close();
          ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Edit Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="update_user.php" method="POST">
          <div class="modal-header bg-primary text-white">
            <h1 class="modal-title fs-5" id="editModalLabel">Edit User</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id" id="edit-id">
            <div class="mb-3">
              <label>Name</label>
              <input type="text" class="form-control" id="edit-name" name="fullname" required>
            </div>
            <div class="mb-3">
              <label>Father Name</label>
              <input type="text" class="form-control" id="edit-father" name="father_name" required>
            </div>
            <div class="mb-3">
              <label>CNIC</label>
              <input type="text" class="form-control" id="edit-cnic" name="cnic" required>
            </div>
            <div class="mb-3">
              <label>Phone</label>
              <input type="text" class="form-control" id="edit-phone" name="phone" required>
            </div>
            <div class="mb-3">
              <label>Address</label>
              <textarea class="form-control" id="edit-address" name="address" required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
   

    // Fill modal data
    document.querySelectorAll('.editBtn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('edit-id').value = btn.dataset.id;
        document.getElementById('edit-name').value = btn.dataset.name;
        document.getElementById('edit-father').value = btn.dataset.father;
        document.getElementById('edit-cnic').value = btn.dataset.cnic;
        document.getElementById('edit-phone').value = btn.dataset.phone;
        document.getElementById('edit-address').value = btn.dataset.address;
      });
    });
  </script>
</body>
</html>
