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
    $account_id = $_POST['account_id'];
    $interest_amount = $_POST['interest_amount'];
    $interest_date = $_POST['interest_date'];

    $checkQuery = "
        SELECT 1 FROM FIXED_ACCOUNTS fa 
        JOIN MEMBER_PROFILE mp ON fa.member_id = mp.member_id 
        WHERE fa.account_id = ? AND mp.account_type = 'fixed_account'
    ";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("i", $account_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows === 0) {
        echo "<script>alert('Invalid account selected.'); window.location.href='fixedInterest_Page.php';</script>";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO FIXED_ACCOUNT_INTERESTS (account_id, interest_amount, interest_date) 
                            VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $account_id, $interest_amount, $interest_date);

    if ($stmt->execute()) {
        echo "<script>alert('Fixed account interest added successfully.'); window.location.href='fixedInterest_Page.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to add fixed account interest.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Fixed Interest</title>
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
        <h2>Add Fixed Account Interest</h2>
        <form method="POST">
            <div class="form-group">
                <label for="account_id">Account ID:</label>
                <select name="account_id" id="account_id" required>
                    <?php
                    $query = "
                        SELECT fa.account_id
                        FROM FIXED_ACCOUNTS fa 
                        JOIN MEMBER_PROFILE mp ON fa.member_id = mp.member_id 
                        WHERE mp.account_type = 'fixed_account'
                    ";
                    $result = $conn->query($query);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $accountId = (int) $row['account_id'];
                            echo "<option value=\"$accountId\">$accountId</option>";
                        }
                    } else {
                        echo '<option disabled>No fixed accounts available</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="interest_amount">Interest Amount:</label>
                <input type="number" name="interest_amount" id="interest_amount" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="interest_date">Interest Date:</label>
                <input type="date" name="interest_date" id="interest_date" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="add-btn">Add Interest</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='fixedInterest_Page.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>