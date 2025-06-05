<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Account Types</title>
    <link rel="stylesheet" href="nav.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #003366;
        }
        
        header.hero-header {
            width: 100vw;
            background: linear-gradient(120deg, #D6C4A1 0%, #F4F1E1 100%);
            padding: 70px 20px 60px;
            text-align: center;
            animation: fadeIn 1.5s ease-in;
            margin-top: 60px;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .hero-header h1 {
            font-size: 40px;
            margin-bottom: 10px;
            color: #800020;
        }

        .hero-header p {
            font-size: 18px;
        }

        .account-types {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            padding: 60px 40px;
        }

        .card {
            background-color: #ffffff;
            border: 2px solid #B0B0B0;
            border-radius: 15px;
            width: 300px;
            padding: 30px 25px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            animation: slideUp 1.5s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.2);
        }

        .card h2 {
            color: #003366;
            margin-bottom: 10px;
        }

        .card p {
            color: #555;
            font-size: 15px;
            line-height: 1.6;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #003366;
            color: white;
            font-size: 14px;
            width: 100%;
            position: relative;
            bottom: 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <ul class="navbar">
        <li><a href="homepage.php">HOME</a></li>
        <li><a href="profile_Template.php">MY PROFILE</a></li>
        <li><a href="#notifications">NOTIFICATION</a></li>
        <li><a href="helpMembs.php">HELP</a></li>
        <li><a href="index.php">LOG OUT</a></li>
    </ul>

    <header class="hero-header">
        <h1>Types of Member Accounts</h1>
        <p>Understand each account type and how it supports your financial goals.</p>
    </header>

    <div class="account-types">
        <div class="card">
            <h2>Savings Account</h2>
            <p>This is the most flexible account type. Members can deposit and withdraw anytime. It earns low but steady interest and qualifies members for loan applications.</p>
        </div>

        <div class="card">
            <h2>Time Deposit</h2>
            <p>This account is for members who want higher interest rates. The money is locked in for a set period (e.g., 6 months, 1 year). No withdrawals during the term. Usually not eligible for loan application.</p>
        </div>

        <div class="card">
            <h2>Fixed Account</h2>
            <p>Similar to Time Deposit but stricter. Funds are committed for long-term. Offers higher interest. Limited or no withdrawals. Typically not eligible for loans unless against deposit.</p>
        </div>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> UniHub Multipurpose Cooperative. All rights reserved.
    </footer>

</body>
</html>