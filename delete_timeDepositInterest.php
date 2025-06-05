<?php
include 'db_connect.php';
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

// Check if interest_id is provided
if (isset($_GET['interest_id'])) {
    $interest_id = intval($_GET['interest_id']);

    // Check if the interest entry exists
    $check = $conn->prepare("SELECT * FROM TIME_DEPOSIT_INTEREST WHERE interest_id = ?");
    $check->bind_param("i", $interest_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Interest record not found.'); window.location.href='timeDepositInterest_Page.php';</script>";
        exit();
    }

    // Delete the interest record
    $stmt = $conn->prepare("DELETE FROM TIME_DEPOSIT_INTEREST WHERE interest_id = ?");
    $stmt->bind_param("i", $interest_id);

    if ($stmt->execute()) {
        echo "<script>alert('Interest record deleted successfully.'); window.location.href='timeDepositInterest_Page.php';</script>";
    } else {
        echo "<script>alert('Failed to delete the interest record.'); window.location.href='timeDepositInterest_Page.php';</script>";
    }
} else {
    echo "<script>alert('No interest record specified.'); window.location.href='timeDepositInterest_Page.php';</script>";
}
?>