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

if (!isset($_GET['account_id'])) {
    echo "<script>alert('No account ID provided.'); window.location.href='fixedAccounts_Page.php';</script>";
    exit();
}

$account_id = intval($_GET['account_id']);

$stmt = $conn->prepare("SELECT * FROM FIXED_ACCOUNTS WHERE account_id = ?");
$stmt->bind_param("i", $account_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "<script>alert('Fixed account record not found.'); window.location.href='fixedAccounts_Page.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account_balance = $_POST['account_balance'];
    $deposit_date = $_POST['deposit_date'];
    $maturity_date = $_POST['maturity_date'];
    $interest_rate = $_POST['interest_rate'];
    $status = $_POST['status'];

    $update = $conn->prepare("UPDATE FIXED_ACCOUNTS SET account_balance = ?, deposit_date = ?, maturity_date = ?, interest_rate = ?, status = ? WHERE account_id = ?");
    $update->bind_param("dsssii", $account_balance, $deposit_date, $maturity_date, $interest_rate, $status, $account_id);

    if ($update->execute()) {
        echo "<script>alert('Fixed account updated successfully.'); window.location.href='fixedAccounts_Page.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to update.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Fixed Account</title>
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

        input, select {
            padding: 14px;
            font-size: 18px;
            border: none;
            border-radius: 6px;
            background-color: #ffffff;
            font-weight: 600;
            color: #000;
            transition: all 0.2s ease;
        }

        input:focus, select:focus {
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
        <h2>Edit Fixed Account</h2>
        <form method="POST">
            <div class="form-group">
                <label for="account_balance">Account Balance:</label>
                <input type="number" step="0.01" name="account_balance" id="account_balance" value="<?= htmlspecialchars($data['account_balance']) ?>" required>
            </div>

            <div class="form-group">
                <label for="deposit_date">Deposit Date:</label>
                <input type="date" name="deposit_date" id="deposit_date" value="<?= htmlspecialchars($data['deposit_date']) ?>" required>
            </div>

            <div class="form-group">
                <label for="maturity_date">Maturity Date:</label>
                <input type="date" name="maturity_date" id="maturity_date" value="<?= htmlspecialchars($data['maturity_date']) ?>" required>
            </div>

            <div class="form-group">
                <label for="interest_rate">Interest Rate (%):</label>
                <input type="number" step="0.01" name="interest_rate" id="interest_rate" value="<?= htmlspecialchars($data['interest_rate']) ?>" required>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" id="status" required>
                    <option value="1" <?= $data['status'] == 1 ? 'selected' : '' ?>>Active</option>
                    <option value="0" <?= $data['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="add-btn">Update</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='fixedAccounts_Page.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>