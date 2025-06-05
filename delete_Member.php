<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

if (isset($_GET['member_id']) && is_numeric($_GET['member_id'])) {
    $member_id = intval($_GET['member_id']);

    $stmt = $conn->prepare("DELETE FROM MEMBER_PROFILE WHERE member_id = ?");
    $stmt->bind_param("i", $member_id);

    if ($stmt->execute()) {
        echo "<script>alert('Member deleted successfully.'); window.location.href='member_Page.php';</script>";
    } else {
        echo "<script>alert('Error deleting member.'); window.location.href='member_Page.php';</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('No member ID provided.'); window.location.href='member_Page.php';</script>";
}
?><?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

if (isset($_GET['member_id']) && is_numeric($_GET['member_id'])) {
    $member_id = intval($_GET['member_id']);

    $stmt = $conn->prepare("DELETE FROM MEMBER_PROFILE WHERE member_id = ?");
    $stmt->bind_param("i", $member_id);

    if ($stmt->execute()) {
        echo "<script>alert('Member deleted successfully.'); window.location.href='member_Page.php';</script>";
    } else {
        echo "<script>alert('Error deleting member.'); window.location.href='member_Page.php';</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('No member ID provided.'); window.location.href='member_Page.php';</script>";
}
?>