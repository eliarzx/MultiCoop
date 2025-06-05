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

if (isset($_GET['loan_interest_id'])) {
    $loan_interest_id = $_GET['loan_interest_id'];

    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM LOAN_INTEREST WHERE loan_interest_id = ?");
    $stmt->bind_param("i", $loan_interest_id);

    if ($stmt->execute()) {
        echo "<script>alert('Loan interest deleted successfully.'); window.location.href='loanInterest_Page.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to delete loan interest.'); window.location.href='loanInterest_Page.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('No loan interest selected for deletion.'); window.location.href='loanInterest_Page.php';</script>";
    exit();
}
?>