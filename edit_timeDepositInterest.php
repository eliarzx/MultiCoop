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

if (!isset($_GET['interest_id'])) {
    echo "<script>alert('Invalid request.'); window.location.href='timeDepositInterest_Page.php';</script>";
    exit();
}

$interest_id = $_GET['interest_id'];

// Fetch existing record
$stmt = $conn->prepare("SELECT * FROM TIME_DEPOSIT_INTEREST WHERE interest_id = ?");
$stmt->bind_param("i", $interest_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "<script>alert('Record not found.'); window.location.href='timeDepositInterest_Page.php';</script>";
    exit();
}

$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $term_months = $_POST['term_months'];
    $interest_rate = $_POST['interest_rate'];

    $updateStmt = $conn->prepare("UPDATE TIME_DEPOSIT_INTEREST SET term_months = ?, interest_rate = ? WHERE interest_id = ?");
    $updateStmt->bind_param("idi", $term_months, $interest_rate, $interest_id);

    if ($updateStmt->execute()) {
        echo "<script>alert('Time deposit interest updated successfully.'); window.location.href='timeDepositInterest_Page.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to update record.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Time Deposit Interest</title>
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

        .update-btn {
            background-color: #F4F1E1;
            color: black;
        }

        .update-btn:hover {
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
        <h2>Edit Time Deposit Interest</h2>
        <form method="POST">
            <div class="form-group">
                <label for="term_months">Term (in Months):</label>
                <input type="number" name="term_months" id="term_months" value="<?= htmlspecialchars($row['term_months']) ?>" required>
            </div>

            <div class="form-group">
                <label for="interest_rate">Interest Rate (%):</label>
                <input type="number" step="0.01" name="interest_rate" id="interest_rate" value="<?= htmlspecialchars($row['interest_rate']) ?>" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="update-btn">Update Interest</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='timeDepositInterest_Page.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>