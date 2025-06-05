<?php
session_start();
include 'db_connect.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password !== $confirmPassword) {
        echo "Passwords do not match. Please try again.";
    } else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO USER_ACCOUNT (user_id, email, password) 
                VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $_SESSION['ID'], $email, $hashedPassword);

        if ($stmt->execute()) {
            echo "Account Created!";
            header("Location: adminPanel.php");
            exit();
        } else {
            echo "Error inserting into USER_ACCOUNT table: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User Account</title>
    <link rel="stylesheet" href="admincss.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    height: 100vh;
    background: #121421;
    color: #f0f0f0;
    display: flex;
    flex-direction: row;
}

.container {
    display: flex;
    justify-content: flex-start; /* align items from the left */
    align-items: center;
    height: 100vh;
    width: 100%;
    padding-left: 280px; /* pushes it to the right */
    box-sizing: border-box;
}

.add-profile {
    background-color: #1c1f2e;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    width: 100%;
    max-width: 450px;
    padding: 40px;
    box-sizing: border-box;
}

h3 {
    text-align: center;
    color: #ffcc00;
    font-size: 28px;
    margin-bottom: 30px;
}

label {
    font-size: 14px;
    color: #cccccc;
    font-weight: 500;
    margin-bottom: 6px;
    display: block;
}

input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 12px 10px;
    margin-bottom: 20px;
    border: 1px solid #444;
    background-color: #2a2d3e;
    color: #f0f0f0;
    border-radius: 6px;
    font-size: 14px;
}

input[type="email"]:focus,
input[type="password"]:focus {
    outline: none;
    border-color: #ffcc00;
}

button {
    width: 100%;
    padding: 12px;
    font-size: 15px;
    border: none;
    border-radius: 6px;
    margin-bottom: 12px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.login-btn {
    background-color: #003366;
    color: white;
}

.login-btn:hover {
    background-color: #002244;
}

.cancel-btn {
    background-color: #666;
    color: white;
}

.cancel-btn:hover {
    background-color: #800020;
}
    </style>
</head>
<body>

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

 <div class="container">
        <div class="add-profile">
            <h3>ADD USER ACCOUNT</h3>
            <form method="POST">
                <label>EMAIL</label>
                <input type="email" name="email" required> 

                <label>PASSWORD</label>
                <input type="password" name="password" required>

                <label>CONFIRM PASSWORD</label>
                <input type="password" name="confirmPassword" required>

                <button type="submit" class="login-btn">SUBMIT</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='add_Profile.php';">CANCEL</button>
            </form>
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
</script>
</body>
</html>
