<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

if (!isset($_GET['id'])) {
    echo "<script>alert('No account ID provided.'); window.location.href='account_Page.php';</script>";
    exit();
}

$account_id = $_GET['id'];

// Fetch account data
$stmt = $conn->prepare("SELECT * FROM USER_ACCOUNT WHERE account_id = ?");
$stmt->bind_param("i", $account_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Account not found.'); window.location.href='account_Page.php';</script>";
    exit();
}

// Fetch all user IDs for dropdown
$user_result = $conn->query("SELECT user_id FROM USERS ORDER BY user_id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Securely hash the new password

    $update_stmt = $conn->prepare("UPDATE USER_ACCOUNT SET user_id = ?, email = ?, password = ? WHERE account_id = ?");
    $update_stmt->bind_param("issi", $user_id, $email, $password, $account_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Account updated successfully.'); window.location.href='account_Page.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating account.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Account</title>
  <style>
    /* Include your CSS here */
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
  <h2>Edit Account</h2>
  <form method="POST">
<div class="form-group">
  <label for="user_id">User ID</label>
  <select name="user_id" required>
      <option value="">Select User ID</option>
      <?php while ($row = $user_result->fetch_assoc()): ?>
          <option value="<?= $row['user_id'] ?>" <?= $row['user_id'] == $account['user_id'] ? 'selected' : '' ?>>
              <?= $row['user_id'] ?>
          </option>
      <?php endwhile; ?>
  </select>
</div>

    <div class="form-group">
      <label for="email">Email Address</label>
      <input type="email" id="email" name="email" value="<?= htmlspecialchars($account['email']) ?>" required>
    </div>

    <div class="form-group">
      <label for="password">New Password</label>
      <input type="password" id="password" name="password" placeholder="Enter new password" required>
    </div>

    <div class="form-actions">
      <button type="submit" class="update-btn">Update Account</button>
      <a href="account_Page.php"><button type="button" class="cancel-btn">Cancel</button></a>
    </div>
  </form>
</div>

</body>
</html>