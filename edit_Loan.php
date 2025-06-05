<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

if (!isset($_GET['loan_type_id'])) {
    echo "<script>alert('Loan Type ID missing.'); window.location.href='loan_Page.php';</script>";
    exit();
}

$loan_type_id = $_GET['loan_type_id'];

// Fetch existing loan data
$stmt = $conn->prepare("SELECT * FROM LOAN_TYPE WHERE loan_type_id = ?");
$stmt->bind_param("i", $loan_type_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Loan type not found.'); window.location.href='loan_Page.php';</script>";
    exit();
}

$loan = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loan_name = $_POST['loan_name'];
    $interest_rate = $_POST['interest_rate'];
    $interest_type = $_POST['interest_type'];

    $updateStmt = $conn->prepare("UPDATE LOAN_TYPE SET name = ?, interest_rate = ?, interest_type = ? WHERE loan_type_id = ?");
    $updateStmt->bind_param("sdsi", $loan_name, $interest_rate, $interest_type, $loan_type_id);

    if ($updateStmt->execute()) {
        echo "<script>alert('Loan type updated successfully.'); window.location.href='loan_Page.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to update loan type.');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Loan Type</title>
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
        <h2>Edit Loan Type</h2>
        <form method="POST">
            <div class="form-group">
                <label for="loan_name">Loan Name:</label>
                <input type="text" name="loan_name" id="loan_name" value="<?php echo htmlspecialchars($loan['loan_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="interest_rate">Interest Rate (%):</label>
                <input type="number" name="interest_rate" id="interest_rate" step="0.01" value="<?php echo htmlspecialchars($loan['interest_rate']); ?>" required>
            </div>

            <div class="form-group">
                <label for="interest_type">Interest Type:</label>
                <select name="interest_type" id="interest_type" required>
                    <option value="monthly" <?php if ($loan['interest_type'] === 'monthly') echo 'selected'; ?>>Monthly</option>
                    <option value="yearly" <?php if ($loan['interest_type'] === 'yearly') echo 'selected'; ?>>Yearly</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="update-btn">Update Loan Type</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='loan_Page.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>