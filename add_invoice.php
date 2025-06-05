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

$users = [];
$user_query = $conn->query("SELECT user_id, first_name, last_name FROM USERS WHERE role = 'member'");  
while ($row = $user_query->fetch_assoc()) {
    $users[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $due_date = $_POST['due_date'];

    if (!is_numeric($amount)) {
        echo "<script>alert('Amount must be numeric.');</script>";
    } else {
        // Determine status automatically based on due_date vs today
        $today = date('Y-m-d');
        $status = ($due_date < $today) ? 'overdue' : 'unpaid';

        $stmt = $conn->prepare("INSERT INTO INVOICES (user_id, description, amount, due_date, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("isdss", $user_id, $description, $amount, $due_date, $status);

        if ($stmt->execute()) {
            echo "<script>alert('Invoice added successfully.'); window.location.href='accountant.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error adding invoice: " . addslashes($stmt->error) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add Invoice</title>
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <style>
        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #00796b;
            display: flex;
            justify-content: center;
            gap: 40px;
            padding: 14px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            z-index: 1000;
        }
        nav a {
            color: #e0f2f1;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }
        nav a:hover {
            background-color: #004d40;
            color: #a7ffeb;
        }

        /* Main body - shifted down to prevent overlap with navbar */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
            color: #333;
            margin: 0;
            padding: 80px 20px 20px; /* padding-top for fixed navbar */
        }

        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            padding: 40px 35px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.07);
            color: #333;
        }

        h2 {
            font-weight: 600;
            color: #005f73;
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 18px;
            font-weight: 600;
            color: #0a9396;
            margin-bottom: 8px;
        }

        input, select, textarea {
            padding: 14px;
            font-size: 18px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #e0f7fa;
            color: #004d40;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #00796b;
            box-shadow: 0 0 6px rgba(0, 121, 107, 0.4);
            background-color: #ffffff;
            color: #004d40;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        button {
            font-size: 18px;
            font-weight: 600;
            width: 220px;
            height: 56px;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .add-btn {
            background-color: #00796b;
            color: #e0f2f1;
        }

        .add-btn:hover {
            background-color: #004d40;
            color: #a7ffeb;
            transform: scale(1.05);
        }

        .cancel-btn {
            background-color: #94d2bd;
            color: #004d40;
        }

        .cancel-btn:hover {
            background-color: #74b49b;
            color: #e0f2f1;
            transform: scale(1.05);
        }

    </style>
</head>
<body>

    <nav>
    <a href="accountant.php"><i class="fas fa-home"></i> Home</a>
    <a href="add_invoice.php"><i class="fas fa-file-invoice"></i> Manage</a>
    </nav>
        
    <div class="form-container">
        <h2>Add Invoice</h2>
        <form method="POST">
            <div class="form-group">
                <label for="user_id">User:</label>
                <select name="user_id" id="user_id" required>
                    <option value="" disabled selected>Select User</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['user_id'] ?>">
                            <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="amount">Amount (₱):</label>
                <input type="number" step="0.01" name="amount" id="amount" required>
            </div>

            <div class="form-group">
                <label for="due_date">Due Date:</label>
                <input type="date" name="due_date" id="due_date" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="add-btn">Add Invoice</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='accountant.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>