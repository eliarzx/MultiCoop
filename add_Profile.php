<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $middleName = $_POST['middle_name'];
    $gender = $_POST['sex'];
    $birthdate = $_POST['birthdate'];
    $role = $_POST['role'];

    $sql = "INSERT INTO USERS (first_name, middle_name, last_name, birthdate, sex, role)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $firstName, $middleName, $lastName, $birthdate, $gender, $role);

    if ($stmt->execute()) {
        $userId = $conn->insert_id;

        $_SESSION['ID'] = $userId;
        $_SESSION['FirstName'] = $firstName;
        $_SESSION['LastName'] = $lastName;
        $_SESSION['MiddleName'] = $middleName;

        header("Location: add_User.php");
        exit();
    } else {
        echo "Error inserting user into USERS table: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Profile</title>
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

.main-container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 90vh;
    width: 100%;
    max-width: 700px;
    margin: 0 auto;
    padding: 40px;
    background-color: #1c1f2e; 
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    z-index: 2;
}

.registration {
    width: 100%;
}

h2 {
    text-align: center;
    color: #ffcc00;
    font-size: 32px;
    margin-bottom: 30px;
}

label {
    font-size: 16px;
    color: #cccccc;
    margin-bottom: 6px;
    display: block;
}

input,
select {
    width: 100%;
    padding: 12px;
    margin-bottom: 16px;
    border: 1px solid #444;
    background-color: #2a2d3e; /* darker than container */
    color: #f0f0f0;
    border-radius: 6px;
    font-size: 16px;
}

input:focus,
select:focus {
    outline: none;
    border-color: #ffcc00;
}

.radio-container {
    display: flex;
    justify-content: space-between;
    margin: 10px 0;
    flex-wrap: wrap;
}

.radio-container label {
    font-size: 16px;
    color: #ccc;
    display: flex;
    align-items: center;
    margin-right: 10px;
}

.radio-container input {
    margin-right: 8px;
    width: auto;
    height: auto;
}

button {
    width: 100%;
    padding: 12px;
    font-size: 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    margin-bottom: 10px;
    transition: background-color 0.3s ease;
}

.submit-btn {
    background-color: #ffcc00;
    color: #1c1f2e;
    font-weight: bold;
}

.submit-btn:hover {
    background-color: #e6b800;
}

.cancel-btn {
    background-color: #3a3d4d;
    color: #f0f0f0;
}

.cancel-btn:hover {
    background-color: #555a6d;
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

    <div class="main-container">

        <div class="registration">
            <h2>ADD PROFILE</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="last-name">LAST NAME</label>
                    <input type="text" id="last-name" name="last_name" placeholder="Enter Last Name" required>
                </div>

                <div class="form-group">
                    <label for="first-name">FIRST NAME</label>
                    <input type="text" id="first-name" name="first_name" placeholder="Enter First Name" required>
                </div>

                <div class="form-group">
                    <label for="middle-name">MIDDLE NAME</label>
                    <input type="text" id="middle-name" name="middle_name" placeholder="Enter Middle Name">
                </div>

                <div class="form-group">
                    <label for="gender">GENDER</label>
                    <select name="sex" id="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="birthdate">BIRTHDATE</label>
                    <input type="date" id="birthdate" name="birthdate" required>
                </div>

                <div class="form-group">
                    <label>ROLE</label>
                    <div class="radio-container">
                        <input type="radio" id="admin" name="role" value="admin" required>
                        <label for="admin">Admin</label>
                        <input type="radio" id="accountant" name="role" value="accountant" required>
                        <label for="admin">Accountant</label>
                        <input type="radio" id="member" name="role" value="member" required>
                        <label for="member">Member</label>
                    </div>
                </div>

                <button type="submit" name="submit" class="submit-btn">SUBMIT</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='adminPanel.php';">CANCEL</button>
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