<?php
session_start();
include 'db_connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in.'); window.location.href='index.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT first_name FROM USERS WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$first_name = $user['first_name'] ?? 'Admin';

$account_types = ['Loan', 'Savings', 'Time Deposit', 'Fixed'];
$account_data = [];

foreach ($account_types as $type) {
    $table = strtoupper(str_replace(" ", "_", $type)) . "_ACCOUNTS";
    $query = "SELECT COUNT(*) AS total_accounts FROM $table";
    $result = $conn->query($query);

    if ($result) {
        $data = $result->fetch_assoc();
        $account_data[$type] = $data['total_accounts'];
    } else {
        $account_data[$type] = 0;
    }
}

$loan_statuses = ['pending', 'approved', 'rejected'];
$loan_data = [];

foreach ($loan_statuses as $status) {
    $query = "SELECT COUNT(*) AS total_loans FROM LOAN_APPLICATION WHERE status = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $data = $result->fetch_assoc();
        $loan_data[ucfirst($status)] = $data['total_loans']; // Capitalize for display
    } else {
        $loan_data[ucfirst($status)] = 0;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel - Dashboard</title>
    <link rel="stylesheet" href="admincss.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 10px;
            margin-top: 20px;
        }

        .chart-card {
            background: #1c1f2e;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            max-width: 100%;
            margin: 0;
        }

        .chart-card canvas {
            max-width: 100%;
            height: 350px !important;
        }
        .kpi-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .kpi-card {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: transform 0.2s ease;
        }

        .kpi-card:hover {
            transform: translateY(-4px);
        }

        .kpi-card h4 {
            font-size: 16px;
            font-weight: 500;
            color: #555;
            margin-bottom: 10px;
        }

        .kpi-card p {
            font-size: 26px;
            font-weight: 600;
            color: #003366;
            margin: 0;
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
   
   <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
    <div>
        <h1 style="margin: 0;">Welcome, <?php echo htmlspecialchars($first_name); ?>!</h1>
        <p style="margin: 5px 0 0;">Start managing UniHub data.</p>
    </div>
    <div style="display: flex; align-items: center; gap: 15px;">
        <a href="add_Profile.php" style="
            background-color: #003366;
            color: #fff;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            white-space: nowrap;
            transition: background-color 0.3s ease;
        " onmouseover="this.style.backgroundColor='#002244'" onmouseout="this.style.backgroundColor='#003366'">
            + Add a User
        </a>
        <div id="clock-box">
            <span id="clock"></span>
        </div>
    </div>
</div>

    <section class="kpi-section">
        <div class="kpi-card">
            <h4>Loan Accounts</h4>
            <p><?php echo $account_data['Loan']; ?></p>
        </div>
        <div class="kpi-card">
            <h4>Savings Accounts</h4>
            <p><?php echo $account_data['Savings']; ?></p>
        </div>
        <div class="kpi-card">
            <h4>Time Deposit Accounts</h4>
            <p><?php echo $account_data['Time Deposit']; ?></p>
        </div>
        <div class="kpi-card">
            <h4>Fixed Accounts</h4>
            <p><?php echo $account_data['Fixed']; ?></p>
        </div>
    </section>

    <section class="dashboard">
        <div class="chart-card">
            <h3>Account Types</h3>
            <canvas id="accountPieChart"></canvas>
        </div>
        <div class="chart-card">
            <h3>Loan Status</h3>
            <canvas id="loanStatusChart"></canvas>
        </div>
    </section>
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

function updateClock() {
    const clockElement = document.getElementById("clock");
    const now = new Date();
    const time = now.toLocaleTimeString();
    clockElement.textContent = `Current time: ${time}`;
}
setInterval(updateClock, 1000);
updateClock();

const accountCounts = <?php echo json_encode(array_values($account_data)); ?>;
const accountLabels = <?php echo json_encode(array_keys($account_data)); ?>;

new Chart(document.getElementById('accountPieChart'), {
    type: 'pie',
    data: {
        labels: accountLabels,
        datasets: [{
            label: 'Account Distribution',
            data: accountCounts,
            backgroundColor: ['#003366', '#D6C4A1', '#800020', '#F4F1E1'],
            borderColor: '#fff',
            borderWidth: 1
        }]
    },
    options: {
        plugins: {
            legend: { position: 'top' },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `${context.label}: ${context.raw} accounts`;
                    }
                }
            }
        }
    }
});

const loanCounts = <?php echo json_encode(array_values($loan_data)); ?>;
const loanLabels = <?php echo json_encode(array_keys($loan_data)); ?>;

new Chart(document.getElementById('loanStatusChart'), {
    type: 'polarArea',
    data: {
        labels: loanLabels,
        datasets: [{
            label: 'Loan Application Status',
            data: loanCounts,
            backgroundColor: ['#FFA500', '#28A745', '#DC3545'],
            borderColor: '#fff',
            borderWidth: 1
        }]
    },
    options: {
        plugins: {
            legend: { position: 'top' },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `${context.label}: ${context.raw} applications`;
                    }
                }
            }
        }
    }
});
</script>
</body>
</html>