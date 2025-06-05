<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Securely hash the password

    $stmt = $conn->prepare("INSERT INTO USER_ACCOUNT (user_id, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $email, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Account created successfully.'); window.location.href='account_Page.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error creating account.');</script>";
    }
}

// Fetch available user IDs for dropdown
$result = $conn->query("SELECT user_id FROM USERS ORDER BY user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Account</title>
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
  <h2>Add New Account</h2>
  <form method="POST">
<div class="form-group">
  <label for="user_id">User ID</label>
  <select id="user_id" name="user_id" required>
    <option value="">Select User ID</option>
    <?php while ($row = $result->fetch_assoc()): ?>
      <option value="<?= $row['user_id'] ?>"><?= $row['user_id'] ?></option>
    <?php endwhile; ?>
  </select>
</div>

    <div class="form-group">
      <label for="email">Email Address</label>
      <input type="email" id="email" name="email" required>
    </div>

    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>
    </div>

    <div class="form-actions">
      <button type="submit" class="update-btn">Create Account</button>
      <a href="account_Page.php"><button type="button" class="cancel-btn">Cancel</button></a>
    </div>
  </form>
</div>

</body>
</html>