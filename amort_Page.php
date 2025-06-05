<?php
session_start();
include 'db_connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

$query = "SELECT MA.*, LT.name 
          FROM MONTHLY_AMORTIZATION MA
          JOIN LOAN_TYPE LT ON MA.loan_type_id = LT.loan_type_id
          ORDER BY MA.created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Amortization Management</title>
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
    <h1>Manage Monthly Amortization</h1>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Loan Type</th>
          <th>Amount</th>
          <th>Duration (months)</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['amortization_id'] ?></td>
          <td><?= $row['name'] ?></td>
          <td>₱<?= number_format($row['amount'], 2) ?></td>
          <td><?= $row['duration_months'] ?></td>
          <td><?= $row['created_at'] ?></td>
            <td>
            <a href="#" class="action-btn view-btn"
                data-id="<?= $row['amortization_id'] ?>"
                data-type="<?= $row['name'] ?>"
                data-amount="<?= number_format($row['amount'], 2) ?>"
                data-duration="<?= $row['duration_months'] ?>"
                data-date="<?= $row['created_at'] ?>">View</a>
            |
            <a href="edit_Amortization.php?amortization_id=<?= $row['amortization_id'] ?>" class="action-btn">Edit</a>
            |
            <a href="delete_Amortization.php?id=<?= $row['amortization_id'] ?>"
                class="action-btn"
                onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <a href="add_Amortization.php" class="login-btn">Add New Amortization</a>
    <a href="adminPanel.php" class="login-btn" style="background-color: var(--gray); color: var(--navy);">← Back to Admin Panel</a>
  </main>

  <div id="viewModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6);">
  <div style="background:#fff; color:#000; margin:10% auto; padding:20px; width:400px; border-radius:10px; position:relative;">
    <span id="closeModalBtn" style="position:absolute; top:10px; right:15px; cursor:pointer; font-size:18px;">&times;</span>
        <h2>Amortization Details</h2>
        <div class="modal-body">
        <p><strong>ID:</strong> <span id="view-id"></span></p>
        <p><strong>Loan Type:</strong> <span id="view-type"></span></p>
        <p><strong>Amount:</strong> ₱<span id="view-amount"></span></p>
        <p><strong>Duration:</strong> <span id="view-duration"></span> months</p>
        <p><strong>Created At:</strong> <span id="view-date"></span></p>
        </div>
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


  document.querySelectorAll(".view-btn").forEach(button => {
    button.addEventListener("click", () => {
      document.getElementById("view-id").textContent = button.dataset.id;
      document.getElementById("view-type").textContent = button.dataset.type;
      document.getElementById("view-amount").textContent = button.dataset.amount;
      document.getElementById("view-duration").textContent = button.dataset.duration;
      document.getElementById("view-date").textContent = button.dataset.date;
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