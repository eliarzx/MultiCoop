<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'Please login first.';
    $_SESSION['message_type'] = 'error';
    header('Location: login.php');
    exit();
}

if (isset($_GET['action'], $_GET['loan_application_id'])) {
    $action = $_GET['action'];
    $loan_app_id = intval($_GET['loan_application_id']);

    $new_status = '';
    if ($action === 'approve') {
        $new_status = 'Approved';
    } elseif ($action === 'reject') {
        $new_status = 'Rejected';
    } else {
        $_SESSION['message'] = 'Invalid action specified.';
        $_SESSION['message_type'] = 'error';
        header('Location: loan_Application.php');
        exit();
    }

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("SELECT member_id, amount_requested, status FROM LOAN_APPLICATION WHERE loan_application_id = ? FOR UPDATE");
        $stmt->bind_param("i", $loan_app_id);
        $stmt->execute();
        $stmt->bind_result($member_id, $amount_requested, $current_status);
        if (!$stmt->fetch()) {
            throw new Exception("Loan application not found.");
        }
        $stmt->close();

        if ($current_status === $new_status) {
            throw new Exception("Loan application is already $new_status.");
        }
        if ($current_status === 'Paid') {
            throw new Exception("Cannot change status of a paid loan.");
        }

        $stmt = $conn->prepare("UPDATE LOAN_APPLICATION SET status = ? WHERE loan_application_id = ?");
        $stmt->bind_param("si", $new_status, $loan_app_id);
        $stmt->execute();
        $stmt->close();

        if ($new_status === 'Approved') {
            $amount_requested_float = floatval($amount_requested);

            $stmtCheck = $conn->prepare("SELECT balance FROM MEMBER_PROFILE WHERE member_id = ? FOR UPDATE");
            $stmtCheck->bind_param("i", $member_id);
            $stmtCheck->execute();
            $stmtCheck->bind_result($current_balance);
            $stmtCheck->fetch();
            $stmtCheck->close();
            
            $updated_balance = (is_null($current_balance) ? 0 : $current_balance) + $amount_requested_float;

            $stmt = $conn->prepare("UPDATE MEMBER_PROFILE SET balance = ? WHERE member_id = ?");
            $stmt->bind_param("di", $updated_balance, $member_id);
            if (!$stmt->execute()) {
                throw new Exception("Failed to update MEMBER_PROFILE balance: " . $stmt->error);
            }
            $stmt->close();

            $notif_message = "Your loan application (ID: $loan_app_id) for PHP " . number_format($amount_requested_float, 2) . " has been **Approved**. Your balance has been updated.";
        } elseif ($new_status === 'Rejected') {
            $notif_message = "Your loan application (ID: $loan_app_id) for PHP " . number_format($amount_requested, 2) . " has been **Rejected**.";
        }

        $stmt_notif = $conn->prepare("INSERT INTO NOTIFICATIONS (user_id, message) SELECT user_id, ? FROM MEMBER_PROFILE WHERE member_id = ?");
        $stmt_notif->bind_param("si", $notif_message, $member_id);
        if (!$stmt_notif->execute()) {
            throw new Exception("Failed to create notification.");
        }
        $stmt_notif->close();


        $conn->commit();
        $_SESSION['message'] = "Loan application $new_status successfully!";
        $_SESSION['message_type'] = 'success';
        header('Location: loan_Application.php');
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['message'] = "Error: {$e->getMessage()}";
        $_SESSION['message_type'] = 'error';
        header('Location: loan_Application.php');
        exit();
    }
}

$sql = "SELECT la.loan_application_id, la.member_id, lt.name as loan_type_name, la.amount_requested, la.term_months, la.application_date, la.status, la.notes FROM LOAN_APPLICATION la JOIN LOAN_TYPE lt ON la.loan_type_id = lt.loan_type_id";
$result = $conn->query($sql);

$displayMessage = '';
$messageType = '';
if (isset($_SESSION['message'])) {
    $displayMessage = $_SESSION['message'];
    $messageType = $_SESSION['message_type'] ?? 'info';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Loan Application Management</title>
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

    thead th, tbody td {
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

    .modal {
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.6);
      display: none;
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background-color: var(--cream);
      padding: 30px;
      border-radius: 10px;
      color: var(--navy);
      width: 500px;
      position: relative;
    }

    .close-btn {
      position: absolute;
      right: 15px;
      top: 10px;
      font-size: 20px;
      cursor: pointer;
      color: var(--burgundy);
    }

    #messageModal .modal-content {
        background-color: var(--cream);
        border: 2px solid;
    }
    #messageModal.success .modal-content {
        border-color: green;
    }
    #messageModal.error .modal-content {
        border-color: var(--burgundy);
    }

    /* Professional Details Styling */
    .detail-grid {
        display: grid;
        grid-template-columns: auto 1fr; /* Label then value */
        gap: 10px 15px;
        margin-top: 20px;
    }

    .detail-grid strong {
        color: var(--navy); /* Keep strong tags in details readable */
        text-align: right; /* Align labels to the right */
    }

    .detail-grid span {
        color: #333; /* Slightly darker for values */
        text-align: left;
    }
    .modal-notes {
        grid-column: 1 / -1; /* Span across both columns */
        margin-top: 15px;
        padding-top: 10px;
        border-top: 1px solid var(--gray);
    }
    .modal-notes strong {
        display: block; /* Make "Notes" label take its own line */
        text-align: left;
        margin-bottom: 5px;
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
  <h1>Manage Loan Applications</h1>
  <table>
    <thead>
      <tr>
        <th>Application ID</th>
        <th>Member ID</th>
        <th>Loan Type</th>
        <th>Amount</th>
        <th>Term (months)</th>
        <th>Application Date</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['loan_application_id'] ?></td>
          <td><?= $row['member_id'] ?></td>
          <td><?= $row['loan_type_name'] ?></td>
          <td>₱<?= number_format($row['amount_requested'], 2) ?></td>
          <td><?= $row['term_months'] ?></td>
          <td><?= $row['application_date'] ?></td>
          <td><?= ucfirst($row['status']) ?></td>
          <td>
            <a href="#" class="view-btn"
              data-id="<?= $row['loan_application_id'] ?>"
              data-member="<?= $row['member_id'] ?>"
              data-type="<?= htmlspecialchars($row['loan_type_name']) ?>"
              data-amount="<?= number_format($row['amount_requested'], 2) ?>"
              data-term="<?= $row['term_months'] ?>"
              data-date="<?= $row['application_date'] ?>"
              data-status="<?= ucfirst($row['status']) ?>"
              data-notes="<?= htmlspecialchars($row['notes']) ?>">View</a>
              |
                <a href="loan_Application.php?action=approve&loan_application_id=<?= $row['loan_application_id'] ?>" onclick="return confirm('Approve this application?')" >Approve</a> |
    <a href="loan_Application.php?action=reject&loan_application_id=<?= $row['loan_application_id'] ?>" onclick="return confirm('Reject this application?')" >Reject</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <div id="viewModal" class="modal">
    <div class="modal-content">
      <span class="close-btn">&times;</span>
      <h2>Loan Application Details</h2>
      <div class="detail-grid">
        <strong>Application ID:</strong> <span id="v-id"></span>
        <strong>Member ID:</strong> <span id="v-member"></span>
        <strong>Loan Type:</strong> <span id="v-type"></span>
        <strong>Amount Requested:</strong> ₱<span id="v-amount"></span>
        <strong>Term (months):</strong> <span id="v-term"></span>
        <strong>Application Date:</strong> <span id="v-date"></span>
        <strong>Status:</strong> <span id="v-status"></span>
        <div class="modal-notes">
          <strong>Notes:</strong> <span id="v-notes"></span>
        </div>
      </div>
    </div>
  </div>

  <div id="messageModal" class="modal">
    <div class="modal-content">
      <span class="close-btn" id="closeMessageModal">&times;</span>
      <h2 id="messageTitle"></h2>
      <p id="messageText"></p>
    </div>
  </div>

  <a href="add_LoanApplication.php" class="login-btn">Add New Application</a>
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
        e.preventDefault();
        const parentLi = this.parentElement;
        const submenu = parentLi.querySelector(".submenu");

        if (submenu) {
            submenu.style.display = submenu.style.display === "block" ? "none" : "block";
            parentLi.classList.toggle("open");
        }
    });
});

document.addEventListener("DOMContentLoaded", () => {
  const viewModal = document.getElementById("viewModal");
  const closeViewModal = viewModal.querySelector(".close-btn");

  document.querySelectorAll(".view-btn").forEach(btn => {
    btn.addEventListener("click", e => {
      e.preventDefault();
      document.getElementById("v-id").textContent = btn.dataset.id;
      document.getElementById("v-member").textContent = btn.dataset.member;
      document.getElementById("v-type").textContent = btn.dataset.type;
      document.getElementById("v-amount").textContent = btn.dataset.amount;
      document.getElementById("v-term").textContent = btn.dataset.term;
      document.getElementById("v-date").textContent = btn.dataset.date;
      document.getElementById("v-status").textContent = btn.dataset.status;
      document.getElementById("v-notes").textContent = btn.dataset.notes;
      viewModal.style.display = "flex";
    });
  });

  closeViewModal.addEventListener("click", () => {
    viewModal.style.display = "none";
  });

  window.addEventListener("click", event => {
    if (event.target === viewModal) viewModal.style.display = "none";
  });

  const messageModal = document.getElementById("messageModal");
  const closeMessageModal = document.getElementById("closeMessageModal");
  const messageTitle = document.getElementById("messageTitle");
  const messageText = document.getElementById("messageText");

  const displayMessage = "<?php echo $displayMessage; ?>";
  const messageType = "<?php echo $messageType; ?>";

  if (displayMessage !== "") {
    messageText.textContent = displayMessage;
    if (messageType === 'success') {
        messageTitle.textContent = 'Success!';
        messageModal.classList.add('success');
    } else if (messageType === 'error') {
        messageTitle.textContent = 'Error!';
        messageModal.classList.add('error');
    } else {
        messageTitle.textContent = 'Information';
    }
    messageModal.style.display = 'flex';
  }

  closeMessageModal.addEventListener('click', () => {
    messageModal.style.display = 'none';
    messageModal.classList.remove('success', 'error');
  });

  window.addEventListener('click', (event) => {
    if (event.target === messageModal) {
      messageModal.style.display = 'none';
      messageModal.classList.remove('success', 'error');
    }
  });
});
</script>
</body>
</html>