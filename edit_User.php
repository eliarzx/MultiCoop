<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in.'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "
    SELECT 
        U.*, 
        M.account_type, 
        A.email 
    FROM USERS U
    LEFT JOIN MEMBER_PROFILE M ON U.user_id = M.user_id
    LEFT JOIN USER_ACCOUNT A ON U.user_id = A.user_id
    WHERE U.user_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="nav.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #001f3f;
            padding: 20px;
        }

        .form-container {
            max-width: 1000px;
            margin: auto;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            color: white;
        }

        h2 {
            color: #FFFFFF;
            text-align: center;
            margin-bottom: 25px;
            font-size: 32px;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .form-group {
            flex: 1 1 45%;
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 18px;
            font-weight: bold;
            color: #FFFFFF;
            margin-bottom: 8px;
        }

        input, select {
            padding: 14px; 
            font-size: 18px;
            border: none;
            border-radius: 6px;
            background-color: rgba(255, 255, 255, 0.9);
            transition: all 0.2s ease;
        }

        input:focus, select:focus {
            outline: none;
            box-shadow: 0 0 6px rgba(255, 255, 255, 0.8);
            background-color: #ffffff;
        }

        select {
            font-weight: 600;
        }

        .form-actions {
            flex-basis: 100%;
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        button {
            font-size: 18px;
            width: 300px;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .save-btn {
            background-color: #F4F1E1;
            color: black;
        }

        .save-btn:hover {
            background-color: #003366;
            transform: scale(1.03);
        }

        .cancel-btn {
            background-color: #D6C4A1;
            color: black;
        }

        .cancel-btn:hover {
            background-color: #5e0000;
            transform: scale(1.03);
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

<div class="form-container">
    <h2>Edit Profile</h2>
    <form action="update_Profile.php" method="POST">
        <input type="hidden" name="account_type" value="Member">

        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Middle Name</label>
            <input type="text" name="middle_name" value="<?= htmlspecialchars($user['middle_name']) ?>">
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Birthdate</label>
            <input type="date" name="birthdate" value="<?= htmlspecialchars($user['birthdate']) ?>" required>
        </div>
        <div class="form-group">
            <label>Gender</label>
            <select name="sex" required>
                <option value="Male" <?= $user['sex'] === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $user['sex'] === 'Female' ? 'selected' : '' ?>>Female</option>
            </select>
        </div>
        <div class="form-group">
            <label>Account Type</label>
            <select name="account_type" required>
                <option value="savings" <?= $user['account_type'] === 'savings' ? 'selected' : '' ?>>Savings</option>
                <option value="time_deposit" <?= $user['account_type'] === 'time_deposit' ? 'selected' : '' ?>>Time Deposit</option>
                <option value="fixed_account" <?= $user['account_type'] === 'fixed_account' ? 'selected' : '' ?>>Fixed Account</option>
            </select>
        </div>
        <div class="form-group">
            <label>Barangay</label>
            <input type="text" name="barangay" value="<?= htmlspecialchars($user['barangay']) ?>">
        </div>
        <div class="form-group">
            <label>Municipality Code</label>
            <input type="text" name="municipality_code" value="<?= htmlspecialchars($user['municipality_code']) ?>">
        </div>
        <div class="form-group">
            <label>Province</label>
            <input type="text" name="province" value="<?= htmlspecialchars($user['province']) ?>">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="save-btn">Save Changes</button>
            <button type="button" class="cancel-btn" onclick="window.location.href='profile_Template.php'">Cancel</button>
        </div>
    </form>
</div>

</body>
</html>