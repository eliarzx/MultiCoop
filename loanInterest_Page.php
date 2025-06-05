<?php
session_start();
include 'db_connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

$query = "SELECT * FROM LOAN_INTEREST ORDER BY calculated_on DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Loan Interest Management</title>
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

    body {
      background-color: var(--navy);
      color: var(--cream);
      display: flex;
      height: 100vh;
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
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid var(--gray);
    }

    td a {
      text-decoration: none;
      font-weight: bold;
      color: var(--burgundy);
      padding: 6px 10px;
      border-radius: 4px;
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
      margin-top: 20px;
      transition: 0.3s;
    }

    .login-btn:hover {
      background-color: var(--yellowish);
      color: var(--navy);
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
      background-color: #fff;
      margin: 10% auto;
      padding: 20px;
      border-radius: 10px;
      width: 50%;
      color: var(--navy);
    }

    .close-btn {
      color: red;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    .close-btn:hover {
      color: darkred;
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
                <li><a href="loanInterest_Page.php">Loan Interest</a></li>
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
  <h1>Manage Loan Interest</h1>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Loan ID</th>
        <th>Type</th>
        <th>Rate (%)</th>
        <th>Date</th>
        <th>Amount</th>
        <th>Notes</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['loan_interest_id'] ?></td>
        <td><?= $row['loan_id'] ?></td>
        <td><?= htmlspecialchars($row['interest_type']) ?></td>
        <td><?= $row['rate'] ?>%</td>
        <td><?= $row['calculated_on'] ?></td>
        <td>₱<?= number_format($row['interest_amount'], 2) ?></td>
        <td><?= htmlspecialchars($row['notes']) ?></td>
        <td>
          <a href="#" class="view-btn"
             data-id="<?= $row['loan_interest_id'] ?>"
             data-loanid="<?= $row['loan_id'] ?>"
             data-type="<?= htmlspecialchars($row['interest_type']) ?>"
             data-rate="<?= $row['rate'] ?>"
             data-date="<?= $row['calculated_on'] ?>"
             data-amount="<?= number_format($row['interest_amount'], 2) ?>"
             data-notes="<?= htmlspecialchars($row['notes']) ?>">View</a>
          |
          <a href="edit_Interest.php?loan_interest_id=<?= $row['loan_interest_id'] ?>">Edit</a>
          |
        <a href="delete_Interest.php?loan_interest_id=<?= $row['loan_interest_id'] ?>" onclick="return confirm('Are you sure you want to delete this loan interest?');">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <div id="viewModal" class="modal">
    <div class="modal-content">
      <span class="close-btn">&times;</span>
      <h2>Loan Interest Details</h2>
      <p><strong>ID:</strong> <span id="view-id"></span></p>
      <p><strong>Loan ID:</strong> <span id="view-loanid"></span></p>
      <p><strong>Type:</strong> <span id="view-type"></span></p>
      <p><strong>Rate:</strong> <span id="view-rate"></span>%</p>
      <p><strong>Date:</strong> <span id="view-date"></span></p>
      <p><strong>Amount:</strong> ₱<span id="view-amount"></span></p>
      <p><strong>Notes:</strong> <span id="view-notes"></span></p>
    </div>
  </div>

  <a href="add_Interest.php" class="login-btn">Add New Interest</a>
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

  document.querySelectorAll(".view-btn").forEach(button => {
    button.addEventListener("click", () => {
      document.getElementById("view-id").textContent = button.dataset.id;
      document.getElementById("view-loanid").textContent = button.dataset.loanid;
      document.getElementById("view-type").textContent = button.dataset.type;
      document.getElementById("view-rate").textContent = button.dataset.rate;
      document.getElementById("view-date").textContent = button.dataset.date;
      document.getElementById("view-amount").textContent = button.dataset.amount;
      document.getElementById("view-notes").textContent = button.dataset.notes;
      document.getElementById("viewModal").style.display = "block";
    });
  });

  document.querySelector(".close-btn").addEventListener("click", () => {
    document.getElementById("viewModal").style.display = "none";
  });

  window.addEventListener("click", (e) => {
    if (e.target == document.getElementById("viewModal")) {
      document.getElementById("viewModal").style.display = "none";
    }
  });
</script>

</body>
</html>