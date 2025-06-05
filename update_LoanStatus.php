<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Unauthorized access.'); window.location.href='login.php';</script>";
    exit();
}

if (isset($_GET['loan_application_id']) && isset($_GET['action'])) {
    $loan_id = $_GET['loan_application_id'];
    $action = $_GET['action'];

    if ($action === 'approve') {
        $status = 'approved';
    } elseif ($action === 'reject') {
        $status = 'rejected';
    } else {
        echo "<script>alert('Invalid action.'); window.location.href='loan_Application.php';</script>";
        exit();
    }

    $stmt = $conn->prepare("UPDATE LOAN_APPLICATION SET status = ? WHERE loan_application_id = ?");
    $stmt->bind_param("si", $status, $loan_id);

    if ($stmt->execute()) {
        echo "<script>alert('Application $status successfully.'); window.location.href='loan_Application.php';</script>";
    } else {
        echo "<script>alert('Error updating status.'); window.location.href='loan_Application.php';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Missing parameters.'); window.location.href='loan_Application.php';</script>";
}
?>