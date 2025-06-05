<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

if (!isset($_GET['id'])) {
    echo "<script>alert('No account ID provided.'); window.location.href='account_Page.php';</script>";
    exit();
}

$account_id = intval($_GET['id']); // Ensure the ID is an integer

$stmt = $conn->prepare("DELETE FROM USER_ACCOUNT WHERE account_id = ?");
$stmt->bind_param("i", $account_id);

if ($stmt->execute()) {
    echo "<script>alert('Account deleted successfully.'); window.location.href='account_Page.php';</script>";
} else {
    echo "<script>alert('Error deleting account.'); window.location.href='account_Page.php';</script>";
}
?>