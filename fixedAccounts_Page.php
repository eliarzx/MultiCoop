<?php
session_start();
include 'db_connect.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

$query = "SELECT * FROM FIXED_ACCOUNTS ORDER BY account_id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Fixed Accounts Management</title>
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
  <h1>Manage Fixed Accounts</h1>
  <table>
    <thead>
      <tr>
        <th>Account ID</th>
        <th>Member ID</th>
        <th>Account Balance</th>
        <th>Deposit Date</th>
        <th>Maturity Date</th>
        <th>Interest Rate (%)</th>
        <th>Status</th>
        <th>Created At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['account_id'] ?></td>
        <td><?= $row['member_id'] ?></td>
        <td>₱<?= number_format($row['account_balance'], 2) ?></td>
        <td><?= $row['deposit_date'] ?></td>
        <td><?= $row['maturity_date'] ?></td>
        <td><?= $row['interest_rate'] ?>%</td>
        <td><?= ucfirst($row['status']) ?></td>
        <td><?= $row['created_at'] ?></td>
        <td>
          <a href="#" class="view-btn"
             data-id="<?= $row['account_id'] ?>"
             data-member="<?= $row['member_id'] ?>"
             data-balance="<?= number_format($row['account_balance'], 2) ?>"
             data-deposit="<?= $row['deposit_date'] ?>"
             data-maturity="<?= $row['maturity_date'] ?>"
             data-rate="<?= $row['interest_rate'] ?>"
             data-status="<?= ucfirst($row['status']) ?>"
             data-created="<?= $row['created_at'] ?>">View</a> |
          <a href="edit_fixedAccount.php?account_id=<?= $row['account_id'] ?>">Edit</a> |
          <a href="delete_fixedAccount.php?account_id=<?= $row['account_id'] ?>" onclick="return confirm('Delete this fixed account?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <a href="add_fixedAccount.php" class="login-btn">Add New Fixed Account</a>
  <a href="adminPanel.php" class="login-btn" style="background-color: var(--gray); color: var(--navy);">← Back to Admin Panel</a>
</main>

  <div id="viewModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6);">
  <div style="background:#fff; color:#000; margin:10% auto; padding:20px; width:400px; border-radius:10px; position:relative;">
    <span id="closeModalBtn" style="position:absolute; top:10px; right:15px; cursor:pointer; font-size:18px;">&times;</span>
      <h2>Fixed Account Details</h2>
      <p><strong>Account ID:</strong> <span id="view-id"></span></p>
      <p><strong>Member ID:</strong> <span id="view-member"></span></p>
      <p><strong>Balance:</strong> ₱<span id="view-balance"></span></p>
      <p><strong>Deposit Date:</strong> <span id="view-deposit"></span></p>
      <p><strong>Maturity Date:</strong> <span id="view-maturity"></span></p>
      <p><strong>Interest Rate:</strong> <span id="view-rate"></span>%</p>
      <p><strong>Status:</strong> <span id="view-status"></span></p>
      <p><strong>Created At:</strong> <span id="view-created"></span></p>
    </div>
  </div>


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

  const viewModal = document.getElementById("viewModal");
const closeModalBtn = document.getElementById("closeModalBtn");

  document.querySelectorAll(".view-btn").forEach(button => {
    button.addEventListener("click", (e) => {
      e.preventDefault();
      document.getElementById("view-id").textContent = button.dataset.id;
      document.getElementById("view-member").textContent = button.dataset.member;
      document.getElementById("view-balance").textContent = button.dataset.balance;
      document.getElementById("view-deposit").textContent = button.dataset.deposit;
      document.getElementById("view-maturity").textContent = button.dataset.maturity;
      document.getElementById("view-rate").textContent = button.dataset.rate;
      document.getElementById("view-status").textContent = button.dataset.status;
      document.getElementById("view-created").textContent = button.dataset.created;
      viewModal.style.display = "block";
    });
  });

  closeModalBtn.addEventListener("click", () => {
    viewModal.style.display = "none";
  });

  window.addEventListener("click", (event) => {
    if (event.target === viewModal) {
      viewModal.style.display = "none";
    }
  });

</script>

</body>
</html>