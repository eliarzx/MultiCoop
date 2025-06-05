<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $newPassword = $_POST['passnew'];

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Check if email exists
    $checkQuery = "SELECT * FROM USER_ACCOUNT WHERE email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email found, update password
        $updateQuery = "UPDATE USER_ACCOUNT SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ss", $hashedPassword, $email);

        if ($stmt->execute()) {
            echo "<script>alert('Password successfully reset. Please login.'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error updating password.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Email not found.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="nav.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }

        .forgot-password {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h3 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .submit-btn {
            background-color: #003366;
            color: white;
        }

        .cancel-btn {
            background-color: #D6C4A1;
            color: white;
        }

        .links {
            text-align: center;
            margin-top: 15px;
        }

        .links a {
            color: #800020;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>
    <ul class="navbar">
        <li><a href="index.php">UNIHUB</a></li>
        <li><a href="loginPage.php">LOGIN</a></li>
        <li><a href="about_Us2.php">ABOUT US</a></li>
        <li><a href="contact_Us2.php">CONTACT US</a></li>
    </ul>

    <div class="forgot-password">
        <h3>Forgot Password</h3>
        <form method="post" action="">
            <label>ENTER YOUR EMAIL</label>
            <input type="email" id="email" name="email" required>

            <label>NEW PASSWORD</label>
            <input type="password" id="passnew" name="passnew" required>

            <button type="submit" class="submit-btn">SUBMIT</button>
            <button type="button" class="cancel-btn" onclick="window.location.href='index.php';">CANCEL</button>
        </form>

        <div class="links">
            <a href="index.php"><u>Back to Login</u></a>
        </div>
    </div>
</body>
</html>