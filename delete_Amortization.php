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

if (isset($_GET['id'])) {
    $amortization_id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM MONTHLY_AMORTIZATION WHERE amortization_id = ?");
    $stmt->bind_param("i", $amortization_id);

    if ($stmt->execute()) {
        echo "<script>alert('Amortization record deleted successfully.'); window.location.href='amort_Page.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to delete amortization record.'); window.location.href='amort_Page.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='amort_Page.php';</script>";
    exit();
}
?>