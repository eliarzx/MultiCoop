<?php
session_start();
include 'db_connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

if (!isset($_GET['account_id']) || !is_numeric($_GET['account_id'])) {
    echo "<script>alert('Invalid request.'); window.location.href='savingsAccounts_Page.php';</script>";
    exit();
}

$account_id = intval($_GET['account_id']);

// Check that the account exists
$check = $conn->prepare("SELECT 1 FROM SAVINGS_ACCOUNTS WHERE account_id = ?");
$check->bind_param("i", $account_id);
$check->execute();
$res = $check->get_result();

if ($res->num_rows === 0) {
    echo "<script>alert('Account not found.'); window.location.href='savingsAccounts_Page.php';</script>";
    exit();
}

// Delete the savings account
$stmt = $conn->prepare("DELETE FROM SAVINGS_ACCOUNTS WHERE account_id = ?");
$stmt->bind_param("i", $account_id);

if ($stmt->execute()) {
    echo "<script>alert('Savings account deleted successfully.'); window.location.href='savingsAccounts_Page.php';</script>";
} else {
    echo "<script>alert('Failed to delete savings account.'); window.location.href='savingsAccounts_Page.php';</script>";
}

$stmt->close();
$conn->close();
?>