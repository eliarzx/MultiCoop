<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

if (!isset($_GET['id'])) {
    echo "<script>alert('No user selected.'); window.location.href='user_Page.php';</script>";
    exit();
}

$id = intval($_GET['id']);
$query = $conn->prepare("SELECT * FROM USERS WHERE user_id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('User not found.'); window.location.href='user_Page.php';</script>";
    exit();
}

$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = $_POST['first_name'];
    $middle = $_POST['middle_name'];
    $last = $_POST['last_name'];
    $birthdate = $_POST['birthdate'];
    $sex = $_POST['sex'];
    $barangay = $_POST['barangay'];
    $municipality = $_POST['municipality_code'];
    $province = $_POST['province'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE USERS SET first_name=?, middle_name=?, last_name=?, birthdate=?, sex=?, barangay=?, municipality_code=?, province=?, role=? WHERE user_id=?");
    $stmt->bind_param("sssssssssi", $first, $middle, $last, $birthdate, $sex, $barangay, $municipality, $province, $role, $id);

    if ($stmt->execute()) {
        echo "<script>alert('User updated successfully.'); window.location.href='user_Page.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to update user.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #001f3f;
            padding: 20px;
        }

        .form-container {
            max-width: 1000px;
            margin-top: 20px;
            margin-bottom: auto;
            margin-left: auto;
            margin-right: auto;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 16px;
            padding-left: 50px;
            padding-top: 10px;
            padding-bottom: 50px;
            padding-right: 50px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            color: white;
        }

        h2 {
            color: #FFFFFF;
            text-align: center;
            margin-bottom: 20px;
            font-size: 32px;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .form-group {
            flex: 1 1 45%;
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
            height: 28px;
            border: none;
            border-radius: 6px;
            background-color: #ffffff;
            font-weight: 600;
            color: #000;
            transition: all 0.2s ease;
            appearance: none;
        }

        select {
            padding: 14px;
            font-size: 18px;
            height: 56px;
        }

        input:focus, select:focus {
            outline: none;
            box-shadow: 0 0 6px rgba(255, 255, 255, 0.8);
            background-color: #ffffff;
        }

        .form-actions {
            flex-basis: 100%;
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
        }

        button {
            font-size: 18px;
            font-weight: bold;
            width: 300px;
            height: 58px;
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
            transform: scale(1.03);
        }

        .cancel-btn {
            background-color: #D6C4A1;
            color: black;
        }

        .cancel-btn:hover {
            background-color: #5e0000;
            transform: scale(1.03);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit User</h2>

    <div class="container">
        <form method="POST">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($row['first_name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="middle_name">Middle Name:</label>
                <input type="text" name="middle_name" id="middle_name" value="<?= htmlspecialchars($row['middle_name']) ?>">
            </div>

            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($row['last_name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="birthdate">Birthdate:</label>
                <input type="date" name="birthdate" id="birthdate" value="<?= htmlspecialchars($row['birthdate']) ?>" required>
            </div>

            <div class="form-group">
                <label for="sex">Sex:</label>
                <select name="sex" id="sex" required>
                    <option value="Male" <?= $row['sex'] == 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= $row['sex'] == 'Female' ? 'selected' : '' ?>>Female</option>
                    <option value="Other" <?= $row['sex'] == 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="barangay">Barangay:</label>
                <input type="text" name="barangay" id="barangay" value="<?= htmlspecialchars($row['barangay']) ?>">
            </div>

            <div class="form-group">
                <label for="municipality_code">Municipality:</label>
                <input type="text" name="municipality_code" id="municipality_code" value="<?= htmlspecialchars($row['municipality_code']) ?>">
            </div>

            <div class="form-group">
                <label for="province">Province:</label>
                <input type="text" name="province" id="province" value="<?= htmlspecialchars($row['province']) ?>">
            </div>

            <div class="form-group">
                <label for="role">Role:</label>
                <select name="role" id="role" required>
                    <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="member" <?= $row['role'] == 'member' ? 'selected' : '' ?>>Member</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="update-btn">Save Changes</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='user_Page.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>