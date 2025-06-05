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
    $loan_id = $_POST['loan_id'];
    $interest_type = $_POST['interest_type'];
    $rate = $_POST['rate'];
    $calculated_on = $_POST['calculated_on'];
    $interest_amount = $_POST['interest_amount'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("INSERT INTO LOAN_INTEREST (loan_id, interest_type, rate, calculated_on, interest_amount, notes) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdsds", $loan_id, $interest_type, $rate, $calculated_on, $interest_amount, $notes);

    if ($stmt->execute()) {
        echo "<script>alert('Loan interest added successfully.'); window.location.href='loanInterest_Page.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to add loan interest.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Loan Interest</title>
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

        input, select, textarea {
            padding: 14px;
            font-size: 18px;
            border: none;
            border-radius: 6px;
            background-color: #ffffff;
            font-weight: 600;
            color: #000;
            transition: all 0.2s ease;
        }

        input:focus, select:focus, textarea:focus {
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
        <h2>Add Loan Interest</h2>
        <form method="POST">
            <div class="form-group">
                <label for="loan_id">Loan ID:</label>
                <select name="loan_id" id="loan_id" required>
                    <option value="1">Salary Loan</option>
                    <option value="2">Business Loan</option>
                    <option value="3">Emergency Loan</option>
                    <option value="4">Educational Loan</option>
                    <option value="5">Calamity Loan</option>
                </select>
            </div>

            <div class="form-group">
                <label for="interest_type">Interest Type:</label>
                <select name="interest_type" id="interest_type" required>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>

            <div class="form-group">
                <label for="rate">Interest Rate (%):</label>
                <input type="number" name="rate" id="rate" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="calculated_on">Calculated On (Date):</label>
                <input type="date" name="calculated_on" id="calculated_on" required>
            </div>

            <div class="form-group">
                <label for="interest_amount">Interest Amount:</label>
                <input type="number" name="interest_amount" id="interest_amount" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="notes">Notes:</label>
                <textarea name="notes" id="notes" rows="4" required></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="add-btn">Add Interest</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='loanInterest_Page.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>