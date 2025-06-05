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

if (!isset($_GET['amortization_id'])) {
    echo "<script>alert('No amortization ID provided.'); window.location.href='amort_Page.php';</script>";
    exit();
}

$amortization_id = intval($_GET['amortization_id']);

$stmt = $conn->prepare("SELECT * FROM MONTHLY_AMORTIZATION WHERE amortization_id = ?");
$stmt->bind_param("i", $amortization_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "<script>alert('Amortization record not found.'); window.location.href='amort_Page.php';</script>";
    exit();
}

$loan_types_stmt = $conn->prepare("SELECT loan_type_id, name FROM LOAN_TYPE");
$loan_types_stmt->execute();
$loan_types_result = $loan_types_stmt->get_result();
$loan_types = [];
while ($row = $loan_types_result->fetch_assoc()) {
    $loan_types[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loan_type_id = $_POST['loan_type_id'];
    $amount = $_POST['amount'];
    $duration_months = $_POST['duration_months'];

    $update = $conn->prepare("UPDATE MONTHLY_AMORTIZATION SET loan_type_id = ?, amount = ?, duration_months = ? WHERE amortization_id = ?");
    $update->bind_param("idii", $loan_type_id, $amount, $duration_months, $amortization_id);

    if ($update->execute()) {
        echo "<script>alert('Amortization updated successfully.'); window.location.href='amort_Page.php';</script>";
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
    <title>Edit Amortization</title>
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

       

Here is the updated code that uses a dropdown for the `loan_type_id` field, populated with existing loan type names from the `LOAN_TYPE` table:

```php
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

if (!isset($_GET['amortization_id'])) {
    echo "<script>alert('No amortization ID provided.'); window.location.href='amort_Page.php';</script>";
    exit();
}

$amortization_id = intval($_GET['amortization_id']);

$stmt = $conn->prepare("SELECT * FROM MONTHLY_AMORTIZATION WHERE amortization_id = ?");
$stmt->bind_param("i", $amortization_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "<script>alert('Amortization record not found.'); window.location.href='amort_Page.php';</script>";
    exit();
}

$loan_types_stmt = $conn->prepare("SELECT loan_type_id, name FROM LOAN_TYPE");
$loan_types_stmt->execute();
$loan_types_result = $loan_types_stmt->get_result();
$loan_types = [];
while ($row = $loan_types_result->fetch_assoc()) {
    $loan_types[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loan_type_id = $_POST['loan_type_id'];
    $amount = $_POST['amount'];
    $duration_months = $_POST['duration_months'];

    $update = $conn->prepare("UPDATE MONTHLY_AMORTIZATION SET loan_type_id = ?, amount = ?, duration_months = ? WHERE amortization_id = ?");
    $update->bind_param("idii", $loan_type_id, $amount, $duration_months, $amortization_id);

    if ($update->execute()) {
        echo "<script>alert('Amortization updated successfully.'); window.location.href='amort_Page.php';</script>";
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
    <title>Edit Amortization</title>
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
        <h2>Edit Amortization</h2>
        <form method="POST">
            <div class="form-group">
                <label for="loan_type_id">Loan Type:</label>
                <select name="loan_type_id" id="loan_type_id" required>
                    <?php foreach ($loan_types as $loan_type): ?>
                        <option value="<?= $loan_type['loan_type_id'] ?>" <?= $loan_type['loan_type_id'] == $data['loan_type_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($loan_type['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="number" step="0.01" name="amount" id="amount" value="<?= htmlspecialchars($data['amount']) ?>" required>
            </div>

            <div class="form-group">
                <label for="duration_months">Duration (Months):</label>
                <input type="number" name="duration_months" id="duration_months" value="<?= htmlspecialchars($data['duration_months']) ?>" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="add-btn">Update</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='amort_Page.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>