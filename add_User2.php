<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

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

    $stmt = $conn->prepare("INSERT INTO USERS (first_name, middle_name, last_name, birthdate, sex, barangay, municipality_code, province, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $first, $middle, $last, $birthdate, $sex, $barangay, $municipality, $province, $role);

    if ($stmt->execute()) {
        echo "<script>alert('User added successfully.'); window.location.href='user_Page.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to add user.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add User</title>
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
            -webkit-appearance: none;
            -moz-appearance: none;
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
        <h2>Add New User</h2>
        <form method="POST">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name" required>
            </div>

            <div class="form-group">
                <label for="middle_name">Middle Name:</label>
                <input type="text" name="middle_name" id="middle_name">
            </div>

            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" required>
            </div>

            <div class="form-group">
                <label for="birthdate">Birthdate:</label>
                <input type="date" name="birthdate" id="birthdate" required>
            </div>

            <div class="form-group">
                <label for="sex">Sex:</label>
                <select name="sex" id="sex" required>
                    <option value="" disabled selected>Select sex</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            <div class="form-group">
                <label for="barangay">Barangay:</label>
                <input type="text" name="barangay" id="barangay">
            </div>

            <div class="form-group">
                <label for="municipality_code">Municipality:</label>
                <input type="text" name="municipality_code" id="municipality_code">
            </div>

            <div class="form-group">
                <label for="province">Province:</label>
                <input type="text" name="province" id="province">
            </div>

            <div class="form-group">
                <label for="role">Role:</label>
                <select name="role" id="role" required>
                    <option value="" disabled selected>Select role</option>
                    <option value="admin">Admin</option>
                    <option value="member">Member</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="update-btn">Add User</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='user_Page.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>