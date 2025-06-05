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

if (isset($_GET['loan_interest_id'])) {
    $loan_interest_id = $_GET['loan_interest_id'];

    // Fetch current loan interest details
    $stmt = $conn->prepare("SELECT * FROM LOAN_INTEREST WHERE loan_interest_id = ?");
    $stmt->bind_param("i", $loan_interest_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<script>alert('Loan interest not found.'); window.location.href='loanInterest_Page.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('No loan interest selected for editing.'); window.location.href='loanInterest_Page.php';</script>";
    exit();
}

// Process form submission to update the loan interest
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $interest_type = $_POST['interest_type'];
    $interest_rate = $_POST['interest_rate'];
    $interest_description = $_POST['interest_description'];

    // Prepare and execute the update statement
    $stmt = $conn->prepare("UPDATE LOAN_INTEREST SET interest_type = ?, interest_rate = ?, interest_description = ? WHERE loan_interest_id = ?");
    $stmt->bind_param("sdsi", $interest_type, $interest_rate, $interest_description, $loan_interest_id);

    if ($stmt->execute()) {
        echo "<script>alert('Loan interest updated successfully.'); window.location.href='loanInterest_Page.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to update loan interest.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Loan Interest</title>
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
        <h2>Edit Loan Interest</h2>
        <form method="POST">
            <div class="form-group">
                <label for="interest_type">Interest Type:</label>
                <select name="interest_type" id="interest_type" required>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>

            <div class="form-group">
                <label for="interest_rate">Interest Rate (%):</label>
<input type="number" name="interest_rate" id="interest_rate" value="<?= htmlspecialchars($row['interest_rate'] ?? '') ?>" step="0.01" required>            </div>

            <div class="form-group">
                <label for="interest_description">Description:</label>
<textarea name="interest_description" id="interest_description" rows="4" required><?= htmlspecialchars($row['interest_description'] ?? '') ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="update-btn">Update Interest</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='loanInterest_Page.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>