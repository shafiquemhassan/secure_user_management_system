<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

      
      include 'conn.php'; // Uses PDO

      try {
          $stmt = $conn->prepare("SELECT id, fullname, father_name, cnic, phone, address, picture FROM users WHERE role = 'user'");
          $stmt->execute();
          $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
      } catch (PDOException $e) {
          echo "<div class='alert alert-danger'>Error fetching users: " . htmlspecialchars($e->getMessage()) . "</div>";
          $users = [];
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
    table img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
    }
    .table thead th {
  vertical-align: middle;
  font-weight: 600;
  letter-spacing: 0.3px;
}

.table tbody tr:hover {
  background-color: #f3f4f6;
  transition: background-color 0.3s ease;
}

.card {
  border-radius: 16px;
  overflow: hidden;
}

.btn {
  border-radius: 10px;
}

  </style>
</head>
<body class="bg-light">

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <h4 class="text-center fw-bold mb-4">Admin Panel</h4>
    <a href="admin_dashboard.php" ><i class="fa-solid fa-gauge"></i>Dashboard</a>
    <a href="mange_users.php" class="active"><i class="fa-solid fa-users"></i>Manage Users</a>
    <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    <div class="card shadow-sm border-0 rounded-4">
  <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Users Details</h5>
    <input type="text" id="searchInput" class="form-control w-25" placeholder="Search user...">
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-bordered align-middle mb-0" id="usersTable">
        <thead class="bg-dark text-white text-center">
          <tr>
            <th>#</th>
            <th>Profile</th>
            <th>Name</th>
            <th>Father Name</th>
            <th>CNIC</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <?php if (count($users) > 0): ?>
            <?php $i = 1; foreach ($users as $row): ?>
              <tr>
                <td class="fw-bold"><?= $i++; ?></td>
                <td>
                  <?php if (!empty($row['picture']) && file_exists("uploads/{$row['picture']}")): ?>
                    <img src="uploads/<?= htmlspecialchars($row['picture']); ?>" alt="Profile" class="rounded-circle border" style="width: 50px; height: 50px; object-fit: cover;">
                  <?php else: ?>
                    <img src="https://via.placeholder.com/50" alt="No Image" class="rounded-circle border">
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['fullname']); ?></td>
                <td><?= htmlspecialchars($row['father_name']); ?></td>
                <td><?= htmlspecialchars($row['cnic']); ?></td>
                <td><?= htmlspecialchars($row['phone']); ?></td>
                <td><?= htmlspecialchars($row['address']); ?></td>
                <td>
                  <button class="btn btn-sm btn-primary me-2 editBtn"
                          data-id="<?= $row['id']; ?>"
                          data-name="<?= htmlspecialchars($row['fullname']); ?>"
                          data-father="<?= htmlspecialchars($row['father_name']); ?>"
                          data-cnic="<?= htmlspecialchars($row['cnic']); ?>"
                          data-phone="<?= htmlspecialchars($row['phone']); ?>"
                          data-address="<?= htmlspecialchars($row['address']); ?>"
                          data-bs-toggle="modal"
                          data-bs-target="#editModal">
                    <i class="fa-solid fa-pen"></i> Edit
                  </button>
                  <a href="delete_user.php?id=<?= $row['id']; ?>" 
                     class="btn btn-sm btn-danger"
                     onclick="return confirm('Are you sure you want to delete this user?');">
                    <i class="fa-solid fa-trash"></i> Del
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="8" class="text-center text-muted py-4">No users found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="card-footer d-flex justify-content-center align-items-center bg-light">
    <nav>
      <ul class="pagination mb-0" id="pagination"></ul>
    </nav>
  </div>
</div>

  </div>

  <!-- Edit Modal -->
  <div class="modal fade rounded-4" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="update_user.php" method="POST">
          <div class="modal-header bg-dark text-white ">
            <h1 class="modal-title fs-5" id="editModalLabel">Edit User</h1>
            <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"></button>
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
    // Fill modal data when Edit button is clicked
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
    
  // === Search & Pagination ===
  const rowsPerPage = 5;
  const table = document.getElementById("usersTable");
  const tbody = table.querySelector("tbody");
  const rows = Array.from(tbody.querySelectorAll("tr"));
  const pagination = document.getElementById("pagination");
  const searchInput = document.getElementById("searchInput");

  let currentPage = 1;
  let filteredRows = [...rows];

  function displayRows() {
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    tbody.innerHTML = "";
    filteredRows.slice(start, end).forEach(row => tbody.appendChild(row));
    setupPagination();
  }

  function setupPagination() {
    pagination.innerHTML = "";
    const pageCount = Math.ceil(filteredRows.length / rowsPerPage);
    if (pageCount <= 1) return;

    for (let i = 1; i <= pageCount; i++) {
      const li = document.createElement("li");
      li.classList.add("page-item", i === currentPage ? "active" : "");
      li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
      li.addEventListener("click", (e) => {
        e.preventDefault();
        currentPage = i;
        displayRows();
      });
      pagination.appendChild(li);
    }
  }

  // Search functionality
  searchInput.addEventListener("keyup", () => {
    const term = searchInput.value.toLowerCase();
    filteredRows = rows.filter(row => row.textContent.toLowerCase().includes(term));
    currentPage = 1;
    displayRows();
  });

  // Initial load
  displayRows();

  </script>
  
</body>
</html>
