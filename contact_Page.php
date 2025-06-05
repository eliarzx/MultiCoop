<!DOCTYPE html>
<html lang="en">
<?php include 'db_connect.php'; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
  <link rel="stylesheet" href="admincss.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Segoe UI", sans-serif;
}

body {
    background-color: #0f172a;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    padding-top: 80px;
    color: #e2e8f0;
}

.container {
    display: flex;
    background: #1e293b;
    width: 90%;
    max-width: 1100px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    overflow: hidden;
    margin-top: 30px;
    animation: slideIn 0.8s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.left-side {
    width: 45%;
    background: linear-gradient(135deg, #111827, #1f2937);
    color: #f8fafc;
    position: relative;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.mini-container {
    position: relative;
    background-color: #334155;
    color: #f8fafc;
    padding: 20px;
    border-radius: 15px;
    width: 100%;
    max-width: 320px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
    text-align: center;
}

.profile-image {
    width: 180px;
    height: 180px;
    border-radius: 50%;
    object-fit: cover;
    border: 6px solid #64748b;
    margin-bottom: 20px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.6);
}

.mini-container p {
    margin: 10px 0;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.mini-container i {
    margin-right: 10px;
    color: #38bdf8;
}

.right-side {
    width: 55%;
    padding: 40px 30px;
    background-color: #1e293b;
}

.right-side h2 {
    font-size: 36px;
    text-align: center;
    color: #f1f5f9;
    margin-bottom: 10px;
}

.right-side p {
    text-align: center;
    color: #cbd5e1;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-weight: bold;
    color: #f1f5f9;
    margin-bottom: 6px;
    display: block;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #475569;
    border-radius: 8px;
    font-size: 14px;
    background-color: #0f172a;
    color: #f8fafc;
    transition: box-shadow 0.3s, border-color 0.3s;
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: #94a3b8;
}

.form-group input:focus,
.form-group textarea:focus {
    box-shadow: 0 0 6px #38bdf8;
    border-color: #38bdf8;
    outline: none;
}

.form-group textarea {
    resize: none;
    height: 100px;
}

.send-btn {
    display: block;
    width: 120px;
    padding: 12px;
    background-color: #38bdf8;
    color: #0f172a;
    text-align: center;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    margin: 0 auto;
    cursor: pointer;
    transition: background 0.3s, transform 0.3s;
}

.send-btn:hover {
    background-color: #0ea5e9;
    transform: scale(1.05);
}

@media (max-width: 768px) {
    .container {
        flex-direction: column;
    }

    .left-side,
    .right-side {
        width: 100%;
    }

    .left-side {
        padding: 30px 20px;
    }
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

    <div class="container">
        <div class="left-side">
            <div class="mini-container">
                <img src="map.svg" alt="Map Image" class="profile-image">
                <p><i class="fas fa-building"></i> <strong>Company:</strong> Bulacan State University</p>
                <p><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> Malolos, Bulacan</p>
                <p><i class="fas fa-phone"></i> <strong>Contact:</strong> 8934-3930</p>
                <p><i class="fas fa-envelope"></i> <strong>Email:</strong> 2023106199@google.com</p>
            </div>
        </div>
        <div class="right-side">
            <h2>GET IN TOUCH</h2>
            <p>Send us a message!</p>

            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" placeholder="Write your message here..."></textarea>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" placeholder="Enter your email">
            </div>

            <button class="send-btn">SEND</button>
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