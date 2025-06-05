<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Access denied.'); window.location.href='login.php';</script>";
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM USERS WHERE user_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('User deleted successfully.'); window.location.href='user_Page.php';</script>";
    } else {
        echo "<script>alert('Error deleting user.'); window.location.href='user_Page.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid user ID.'); window.location.href='user_Page.php';</script>";
}

$conn->close();
?><?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Access denied.'); window.location.href='login.php';</script>";
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM USERS WHERE user_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('User deleted successfully.'); window.location.href='user_Page.php';</script>";
    } else {
        echo "<script>alert('Error deleting user.'); window.location.href='user_Page.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid user ID.'); window.location.href='user_Page.php';</script>";
}

$conn->close();
?>