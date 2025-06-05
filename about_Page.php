<!DOCTYPE html>
<html lang="en">

<?php include 'db_connect.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Dark</title>
    <link rel="stylesheet" href="admincss.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #1b1b1b;
            margin: 0;
            padding: 0;
            color: #f0f0f0;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #2c2c2c;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        }

        .profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .profile img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #800020;
            margin-bottom: 20px;
        }

        .profile h2 {
            font-size: 32px;
            color: #f0f0f0;
            margin-bottom: 10px;
        }

        .profile .bio {
            font-size: 18px;
            max-width: 750px;
            margin: 15px auto;
            line-height: 1.8;
            color: #e0e0e0;
        }

        .profile .contact-info {
            margin-top: 25px;
            font-size: 16px;
            color: #ddd;
        }

        .profile .contact-info p {
            margin: 4px 0;
        }

        .toggle-section {
            margin-top: 40px;
        }

        .toggle-header {
            font-size: 20px;
            font-weight: bold;
            background-color: #800020;
            color: #fff;
            padding: 15px;
            border-radius: 10px;
            cursor: pointer;
            text-align: center;
        }

        .toggle-content {
            display: none;
            margin-top: 15px;
            padding: 20px;
            background-color: #3b3b3b;
            border-radius: 10px;
            line-height: 1.6;
            color: #ddd;
        }

        .toggle-content.show {
            display: block;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 600px) {
            .profile h2 {
                font-size: 24px;
            }

            .profile img {
                width: 150px;
                height: 150px;
            }

            .container {
                padding: 20px;
            }

            .profile .bio {
                font-size: 16px;
                line-height: 1.6;
            }

            .toggle-header {
                font-size: 18px;
                padding: 12px;
            }

            .toggle-content {
                padding: 15px;
            }
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

    <div class="profile">
        <img src="profileKarell.jpg" alt="My Photo">
        <h2>Karell Jane M. de Guzman</h2>
        <p class="bio">
            I am a second-year BSIT student from Bulacan State University with a passion for building systems that make a difference and contribute to the community.
        </p>
        <p class="bio">
            This website was developed as part of the final requirements of the course Web Systems and Technologies I. This aims to support the digital transformation of the online account receivable system. <br><br>
            This website is designed using <strong>PHP, MySQL, HTML, CSS,</strong> and <strong>JavaScript</strong>.
        </p>
        <div class="contact-info">
            <p><strong>Contact:</strong> 0968-890-6796</p>
            <p><strong>Email:</strong> 2023106199@ms.bulsu.edu.ph</p>
        </div>
    </div>

    <div class="toggle-section">
        <div class="toggle-header" onclick="toggleContent('coop')">Project Overview</div>
        <div id="coop" class="toggle-content">
            To design and implement a secure, efficient, and user-friendly online system to manage the accounts receivable processes of a multipurpose cooperative. The system will streamline invoicing, payment tracking, and reporting, offering transparency and accessibility to both administrators and members.
        </div>
    </div>

    <div class="toggle-section">
        <div class="toggle-header" onclick="toggleContent('features')">System Highlights</div>
        <div id="features" class="toggle-content">
            ✦ User Management<br>
            ✦ Accounts Receivable Management<br>
            ✦ Financial Reporting<br>
            ✦ Notifications and Alerts<br>
            ✦ Data Security and Backup
        </div>
    </div>
</div>

<script>
    function toggleContent(id) {
        const content = document.getElementById(id);
        content.classList.toggle('show');
    }


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