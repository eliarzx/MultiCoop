<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

if (!isset($_GET['loan_type_id'])) {
    echo "<script>alert('Loan Type ID missing.'); window.location.href='loan_Page.php';</script>";
    exit();
}

$loan_type_id = $_GET['loan_type_id'];

$stmt = $conn->prepare("DELETE FROM LOAN_TYPE WHERE loan_type_id = ?");
$stmt->bind_param("i", $loan_type_id);

if ($stmt->execute()) {
    echo "<script>alert('Loan type deleted successfully.'); window.location.href='loan_Page.php';</script>";
} else {
    echo "<script>alert('Failed to delete loan type.'); window.location.href='loan_Page.php';</script>";
}
?>