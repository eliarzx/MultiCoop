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
$first_name = $user['first_name'] ?? 'Accountant';

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
        $loan_data[ucfirst($status)] = $data['total_loans'];
    } else {
        $loan_data[ucfirst($status)] = 0;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Accountant Dashboard</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    nav {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background-color: #00796b;
        display: flex;
        justify-content: center;
        gap: 40px;
        padding: 14px 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        z-index: 1000;
    }
    nav a {
        color: #e0f2f1;
        font-weight: 600;
        font-size: 1.1rem;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 6px;
        transition: background-color 0.3s ease;
    }
    nav a:hover {
        background-color: #004d40;
        color: #a7ffeb;
    }

    /* Main body - shifted down to prevent overlap with navbar */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9fafb;
        color: #333;
        margin: 0;
        padding: 80px 20px 20px; /* 80px top padding for navbar */
    }
    header {
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }
    header h1 {
        font-weight: 600;
        color: #005f73;
        margin: 0;
    }
    header p {
        color: #0a9396;
        margin: 5px 0 0 0;
        font-size: 1.1rem;
    }
    #clock {
        font-weight: 500;
        color: #94d2bd;
    }
    .kpi-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }
    .kpi-card {
        background: #e0f7fa;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        text-align: center;
        transition: transform 0.2s ease;
    }
    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    .kpi-card h4 {
        font-size: 18px;
        color: #00796b;
        margin-bottom: 10px;
    }
    .kpi-card p {
        font-size: 32px;
        font-weight: 700;
        color: #004d40;
        margin: 0;
    }
    .dashboard {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
    }
    canvas {
        max-width: 100% !important;
        height: 350px !important;
    }
    a.logout-btn {
        display: inline-block;
        background-color: #00796b;
        color: #fff;
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }
    a.logout-btn:hover {
        background-color: #004d40;
    }

    .accountant-view {
    margin-bottom: 40px;
    text-align: center;
}

.accountant-view h2 {
    color: #00796b;
    font-weight: 700;
    margin-bottom: 25px;
    font-size: 1.8rem;
}

.button-group {
    display: flex;
    justify-content: center;
    gap: 30px;
    flex-wrap: wrap;
}

.action-btn {
    background-color: #00796b;
    color: white;
    padding: 18px 36px;
    font-size: 1.2rem;
    font-weight: 600;
    border-radius: 14px;
    box-shadow: 0 6px 15px rgba(0, 121, 107, 0.4);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

.action-btn i {
    font-size: 1.4rem;
}

.action-btn:hover {
    background-color: #004d40;
    box-shadow: 0 8px 20px rgba(0, 77, 64, 0.7);
    color: #a7ffeb;
}
</style>
</head>
<body>

    <nav>
    <a href="accountant.php"><i class="fas fa-home"></i> Home</a>
    <a href="add_invoice.php"><i class="fas fa-file-invoice"></i> Manage</a>
    </nav>

<header>
    <div>
        <h1>Welcome, <?php echo htmlspecialchars($first_name); ?>!</h1>
        <p>Manage your account data efficiently.</p>
    </div>
    <div>
        <span id="clock"></span>
        &nbsp;&nbsp;&nbsp;
        <a href="logout.php" class="logout-btn" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
    </div>
</header>

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

<section class="accountant-view">
    <h2>Accountant View</h2>
    <div class="button-group">
        <a href="update_invoices.php" class="action-btn">
            <i class="fas fa-edit"></i> Update Invoices
        </a>
        <a href="add_invoice.php" class="action-btn">
            <i class="fas fa-file-invoice"></i> Add Invoices
        </a>
    </div>
</section>


<script>
function updateClock() {
    const clockElement = document.getElementById("clock");
    const now = new Date();
    clockElement.textContent = `Current time: ${now.toLocaleTimeString()}`;
}
setInterval(updateClock, 1000);
updateClock();
</script>

</body>
</html>