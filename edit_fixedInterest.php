<?php
include 'db_connect.php';
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

if (isset($_GET['account_id'])) {
    $account_id = intval($_GET['account_id']);

    // Fetch the current account details
    $query = $conn->prepare("SELECT * FROM FIXED_ACCOUNTS WHERE account_id = ?");
    $query->bind_param("i", $account_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Account not found.'); window.location.href='fixedInterest_Page.php';</script>";
        exit();
    }

    $account = $result->fetch_assoc(); // Fetch the account details

    // Handle form submission for editing account details
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $account_id = $_POST['account_id'];
        $interest_amount = $_POST['interest_amount'];
        $interest_date = $_POST['interest_date'];

        // Update the account details in the database
        $stmt = $conn->prepare("UPDATE FIXED_ACCOUNT_INTERESTS 
                                SET interest_amount = ?, interest_date = ? 
                                WHERE account_id = ?");
        $stmt->bind_param("dsi", $interest_amount, $interest_date, $account_id);

        if ($stmt->execute()) {
            echo "<script>alert('Fixed account interest updated successfully.'); window.location.href='fixedInterest_Page.php';</script>";
            exit();
        } else {
            echo "<script>alert('Failed to update the fixed account interest.');</script>";
        }
    }
} else {
    echo "<script>alert('No account specified.'); window.location.href='fixedInterest_Page.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Fixed Account Interest</title>
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

        .save-btn {
            background-color: #F4F1E1;
            color: black;
        }

        .save-btn:hover {
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
        <h2>Edit Fixed Account Interest</h2>
        <form method="POST">
            <div class="form-group">
                <label for="account_id">Account ID:</label>
                <input type="text" name="account_id" id="account_id" value="<?php echo $account['account_id']; ?>" disabled>
            </div>

            <div class="form-group">
                <label for="interest_amount">Interest Amount:</label>
                <input type="number" name="interest_amount" id="interest_amount" value="<?php echo $account['interest_amount']; ?>" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="interest_date">Interest Date:</label>
                <input type="date" name="interest_date" id="interest_date" value="<?php echo $account['interest_date']; ?>" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="save-btn">Save Changes</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='fixedInterest_Page.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>