<?php
session_start();
include 'db_connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

// Check if account_id is provided in the URL
if (!isset($_GET['account_id']) || empty($_GET['account_id'])) {
    echo "<script>alert('Invalid request.'); window.location.href='timeDepositAccounts_Page.php';</script>";
    exit();
}

$account_id = intval($_GET['account_id']);

// Prepare and execute delete statement
$stmt = $conn->prepare("DELETE FROM TIME_DEPOSIT_ACCOUNTS WHERE account_id = ?");
$stmt->bind_param("i", $account_id);

if ($stmt->execute()) {
    echo "<script>alert('Time deposit account deleted successfully.'); window.location.href='timeDepositAccounts_Page.php';</script>";
} else {
    echo "<script>alert('Failed to delete the account.'); window.location.href='timeDepositAccounts_Page.php';</script>";
}

$stmt->close();
$conn->close();
?>