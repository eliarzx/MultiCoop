<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

$query = "SELECT * FROM USER_ACCOUNT ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Account Management</title>
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
      <li><a href="user_Page.php"><i class="fas fa-users"></i><span> Users</span></a></li>
      <li><a href="account_Page.php"><i class="fas fa-user-cog"></i><span> Accounts</span></a></li>
      <li><a href="member_Page.php"><i class="fas fa-id-badge"></i><span> Members</span></a></li>
      <li><a href="loan_Page.php"><i class="fas fa-hand-holding-usd"></i><span> Loan Types</span></a></li>
      <li><a href="amort_Page.php"><i class="fas fa-chart-line"></i><span> Amortization</span></a></li>
        <li>
          <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">
            <i class="fas fa-sign-out-alt"></i><span> Logout</span>
          </a>
        </li>
  </ul>
  </nav>

  <main class="main-content">
    <h1>Manage Accounts</h1>
    <table>
      <thead>
        <tr>
          <th>Account ID</th>
          <th>User ID</th>
          <th>Email</th>
          <th>Password</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['account_id'] ?></td>
          <td><?= $row['user_id'] ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td>••••••••</td>
          <td><?= $row['created_at'] ?></td>
          <td>
            <a href="edit_Account.php?id=<?= $row['account_id'] ?>">Edit</a> |
            <a href="delete_Account.php?id=<?= $row['account_id'] ?>" onclick="return confirm('Delete this account?')">Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <a href="add_Account.php" class="login-btn">Add New Account</a>
    <a href="adminPanel.php" class="login-btn" style="background-color: var(--gray); color: var(--navy);">← Back to Admin Panel</a>
  </main>
</div>

<script>
  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('collapsed');
  }
</script>
</body>
</html>