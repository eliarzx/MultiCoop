<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in.'); window.location.href='loginPage.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Get and sanitize input
$first_name = trim($_POST['first_name'] ?? '');
$middle_name = trim($_POST['middle_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$birthdate = trim($_POST['birthdate'] ?? '');
$sex = $_POST['sex'] ?? '';
$email = trim($_POST['email'] ?? '');
$barangay = trim($_POST['barangay'] ?? '');
$municipality_code = trim($_POST['municipality_code'] ?? '');
$province = trim($_POST['province'] ?? '');
$account_type = $_POST['account_type'] ?? '';
$role = 'member';

// Check for required fields
if (
    $first_name === '' || $last_name === '' || $birthdate === '' ||
    $sex === '' || $email === '' || $account_type === ''
) {
    echo "<script>alert('Please fill out all required fields.'); history.back();</script>";
    exit();
}

// USERS table
$update_user = $conn->prepare("
    UPDATE USERS SET 
        first_name = ?, middle_name = ?, last_name = ?, birthdate = ?, sex = ?, 
        barangay = ?, municipality_code = ?, province = ?, role = ?
    WHERE user_id = ?
");

if (!$update_user) {
    die("Prepare failed (USERS): " . $conn->error);
}

$update_user->bind_param("sssssssssi", 
    $first_name, $middle_name, $last_name, $birthdate, $sex, 
    $barangay, $municipality_code, $province, $role, $user_id
);

if (!$update_user->execute()) {
    die("Execute failed (USERS): " . $update_user->error);
}

// MEMBER_PROFILE table
$update_profile = $conn->prepare("UPDATE MEMBER_PROFILE SET account_type = ? WHERE user_id = ?");
if (!$update_profile) {
    die("Prepare failed (MEMBER_PROFILE): " . $conn->error);
}
$update_profile->bind_param("si", $account_type, $user_id);
if (!$update_profile->execute()) {
    die("Execute failed (MEMBER_PROFILE): " . $update_profile->error);
}

// USER_ACCOUNT table
$update_email = $conn->prepare("UPDATE USER_ACCOUNT SET email = ? WHERE user_id = ?");
if (!$update_email) {
    die("Prepare failed (USER_ACCOUNT): " . $conn->error);
}
$update_email->bind_param("si", $email, $user_id);
if (!$update_email->execute()) {
    die("Execute failed (USER_ACCOUNT): " . $update_email->error);
}

echo "<script>alert('Profile updated successfully!');</script>";
header("Location: profile_Template.php");
exit();
?>