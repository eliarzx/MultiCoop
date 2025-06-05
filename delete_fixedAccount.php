<?php
include 'db_connect.php';
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

if (isset($_GET['account_id'])) {
    $account_id = intval($_GET['account_id']);

    // Check if account exists
    $check = $conn->prepare("SELECT * FROM FIXED_ACCOUNTS WHERE account_id = ?");
    $check->bind_param("i", $account_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Account not found.'); window.location.href='fixedInterest_Page.php';</script>";
        exit();
    }

    // Optional: Also delete related interest entries
    $conn->prepare("DELETE FROM FIXED_ACCOUNT_INTERESTS WHERE account_id = ?")->execute([$account_id]);

    // Delete the account itself
    $stmt = $conn->prepare("DELETE FROM FIXED_ACCOUNTS WHERE account_id = ?");
    $stmt->bind_param("i", $account_id);

    if ($stmt->execute()) {
        echo "<script>alert('Fixed account deleted successfully.'); window.location.href='fixedInterest_Page.php';</script>";
    } else {
        echo "<script>alert('Failed to delete the fixed account.'); window.location.href='fixedInterest_Page.php';</script>";
    }
} else {
    echo "<script>alert('No account specified.'); window.location.href='fixedInterest_Page.php';</script>";
}
?>