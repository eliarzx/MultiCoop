<?php
include 'db_connect.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $term_months = $_POST['term_months'];
    $interest_rate = $_POST['interest_rate'];
    $created_at = date('Y-m-d H:i:s'); 

    $stmt = $conn->prepare("INSERT INTO TIME_DEPOSIT_INTEREST (term_months, interest_rate, created_at) 
                            VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $term_months, $interest_rate, $created_at);

    if ($stmt->execute()) {
        echo "<script>alert('Time deposit interest record added successfully.'); window.location.href='timeDepositInterest_Page.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to add time deposit interest.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Time Deposit Interest</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #001f3f;
            padding: 20px;
        }

        .form-container {
            max-width: 800px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            color: white;
        }

        h2 {
            color: #FFFFFF;
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 18px;
            font-weight: bold;
            color: #FFFFFF;
            margin-bottom: 8px;
        }

        input {
            padding: 14px;
            font-size: 18px;
            border: none;
            border-radius: 6px;
            background-color: #ffffff;
            font-weight: 600;
            color: #000;
            transition: all 0.2s ease;
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 6px rgba(255, 255, 255, 0.8);
        }

        .form-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        button {
            font-size: 18px;
            font-weight: bold;
            width: 220px;
            height: 56px;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .add-btn {
            background-color: #F4F1E1;
            color: black;
        }

        .add-btn:hover {
            background-color: #003366;
            color: white;
            transform: scale(1.03);
        }

        .cancel-btn {
            background-color: #D6C4A1;
            color: black;
        }

        .cancel-btn:hover {
            background-color: #5e0000;
            color: white;
            transform: scale(1.03);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add Time Deposit Interest</h2>
        <form method="POST">
            <div class="form-group">
                <label for="term_months">Term (in Months):</label>
                <input type="number" name="term_months" id="term_months" min="1" required>
            </div>

            <div class="form-group">
                <label for="interest_rate">Interest Rate (%):</label>
                <input type="number" step="0.01" name="interest_rate" id="interest_rate" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="add-btn">Add Interest</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='timeDepositInterest_Page.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>