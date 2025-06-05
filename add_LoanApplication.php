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

$user_id = $_SESSION['user_id'];


$loanTypes = $conn->query("SELECT loan_type_id, name FROM LOAN_TYPE");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loan_type = $_POST['loan_type'];
    $amount = $_POST['amount'];
    $term = $_POST['term'];
    $purpose = $_POST['purpose'];
    $date_applied = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO LOAN_APPLICATION (user_id, loan_type_id, amount, term_months, purpose, date_applied) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiddss", $user_id, $loan_type, $amount, $term, $purpose, $date_applied);

    if ($stmt->execute()) {
        echo "<script>alert('Loan application submitted successfully.'); window.location.href='loan_Page.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to submit application.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Loan Application</title>
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
        <h2>Loan Application</h2>
        <form method="POST">
            <div class="form-group">
                <label for="loan_type">Loan Type:</label>
                <select name="loan_type" id="loan_type" required>
                    <option value="" disabled selected>Select loan type</option>
                    <?php while ($row = $loanTypes->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="amount">Loan Amount:</label>
                <input type="number" name="amount" id="amount" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="term">Loan Term (in months):</label>
                <input type="number" name="term" id="term" min="1" required>
            </div>

            <div class="form-group">
                <label for="purpose">Purpose of Loan:</label>
                <textarea name="purpose" id="purpose" rows="4" required></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="add-btn">Apply for Loan</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='loan_Application.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>