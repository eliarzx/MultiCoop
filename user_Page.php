<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

$query = "SELECT * FROM USERS ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Management</title>
    <link rel="stylesheet" href="admincss.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
      --navy: #003366;
      --yellowish: #D6C4A1;
      --gray: #B0B0B0;
      --burgundy: #800020;
      --cream: #F4F1E1;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background-color: var(--navy);
      color: var(--cream);
      display: flex;
      height: 100vh;
    }
      
    .sidebar h2 {
      color: var(--yellowish);
      font-size: 30px;
      margin-bottom: 40px;
      transition: opacity 0.3s;
    }


    .main-content {
      flex-grow: 1;
      padding: 40px;
      background-color: #001a33;
      overflow-y: auto;
    }

    .main-content h1 {
      font-size: 35px;
      margin-bottom: 20px;
      color: var(--yellowish);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: var(--cream);
      color: var(--navy);
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }

    thead {
      background-color: var(--yellowish);
      font-weight: bold;
    }

    thead th {
      padding: 12px;
      text-align: left;
      border-bottom: 2px solid var(--gray);
    }

    tbody td {
      padding: 10px 12px;
      border-bottom: 1px solid var(--gray);
    }

    tbody tr:hover {
      background-color: var(--gray);
      color: var(--navy);
      transition: background-color 0.3s ease;
    }

    td a {
      text-decoration: none;
      font-weight: bold;
      color: var(--burgundy);
      padding: 6px 10px;
      border-radius: 4px;
      transition: background-color 0.2s, color 0.2s;
    }

    td a:hover {
      background-color: var(--yellowish);
      color: var(--navy);
    }

    .login-btn {
      display: inline-block;
      padding: 12px 20px;
      background-color: var(--burgundy);
      color: var(--cream);
      text-decoration: none;
      font-weight: bold;
      border-radius: 6px;
      transition: background-color 0.3s, transform 0.2s;
      margin-right: 10px;
      margin-top: 20px;
    }

    .login-btn:hover {
      background-color: var(--yellowish);
      color: var(--navy);
      transform: scale(1.05);
    }
  </style>
</head>
    
<body>
<div class="admin-container">


<nav class="sidebar" id="sidebar">
    <div class="toggle-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </div>
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="adminPanel.php"><i class="fas fa-home"></i><span> Home</span></a></li>
        <li class="dropdown">
            <a href="#" class="dropdown-btn"><i class="fas fa-hand-holding-usd"></i><span> Loan Accounts</span></a>
            <ul class="submenu" style="display: none;">
                <li><a href="loan_Application.php">Loan Application</a></li>
                <li><a href="loan_Page.php">Loan Type</a></li>
                <li><a href="amort_Page.php">Loan Amortization</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-btn"><i class="fas fa-piggy-bank"></i><span> Fixed Accounts</span></a>
            <ul class="submenu" style="display: none;">
                <li><a href="fixedAccounts_Page.php">Accounts</a></li>
                <li><a href="fixedInterest_Page.php">Interest</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-btn"><i class="fas fa-wallet"></i><span> Savings Accounts</span></a>
            <ul class="submenu" style="display: none;">
                <li><a href="savingsAccounts_Page.php">Accounts</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-btn"><i class="fas fa-coins"></i><span> Time Deposit</span></a>
            <ul class="submenu" style="display: none;">
                <li><a href="timeDepositAccounts_Page.php">Accounts</a></li>
                <li><a href="timeDepositInterest_Page.php">Interest</a></li>
            </ul>
        </li>
        <li><a href="member_Page.php"><i class="fas fa-id-badge"></i><span> Members</span></a></li>
        <li><a href="user_Page.php"><i class="fas fa-users"></i><span> Users</span></a></li>
        <li><a href="about_Page.php"><i class="fas fa-info-circle"></i><span> About Us</span></a></li>
        <li><a href="contact_Page.php"><i class="fas fa-envelope"></i><span> Contact Us</span></a></li>
        <li>
            <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">
                <i class="fas fa-sign-out-alt"></i><span> Logout</span>
            </a>
        </li>
    </ul>
</nav>

  <main class="main-content">
    <h1>Manage Users</h1>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>First</th>
          <th>Middle</th>
          <th>Last</th>
          <th>Birthdate</th>
          <th>Sex</th>
          <th>Barangay</th>
          <th>Municipality</th>
          <th>Province</th>
          <th>Role</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['user_id'] ?></td>
          <td><?= htmlspecialchars($row['first_name']) ?></td>
          <td><?= htmlspecialchars($row['middle_name']) ?></td>
          <td><?= htmlspecialchars($row['last_name']) ?></td>
          <td><?= $row['birthdate'] ?></td>
          <td><?= $row['sex'] ?></td>
          <td><?= $row['barangay'] ?></td>
          <td><?= $row['municipality_code'] ?></td>
          <td><?= $row['province'] ?></td>
          <td><?= $row['role'] ?></td>
          <td><?= $row['created_at'] ?></td>
          <td>
            <a href="edit_User.php?id=<?= $row['user_id'] ?>">Edit</a> | 
            <a href="delete_User.php?id=<?= $row['user_id'] ?>" onclick="return confirm('Delete this user?')">Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <a href="add_User2.php" class="login-btn">Add New User</a>
    <a href="adminPanel.php" class="login-btn" style="background-color: var(--gray); color: var(--navy);">← Back to Admin Panel</a>
  </main>
</div>

<script>


function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  sidebar.classList.toggle("collapsed");

  if (window.innerWidth <= 768) {
    sidebar.classList.toggle("open");
  }
}

document.querySelectorAll(".dropdown-btn").forEach(btn => {
    btn.addEventListener("click", function (e) {
        e.preventDefault(); // Prevent '#' jump
        const parentLi = this.parentElement;
        const submenu = parentLi.querySelector(".submenu");

        if (submenu) {
            submenu.style.display = submenu.style.display === "block" ? "none" : "block";
            parentLi.classList.toggle("open");
        }
    });
});
</script>
</body>
</html>