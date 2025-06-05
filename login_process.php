<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['uname']);
    $password = trim($_POST['pass']);

    if (empty($email) || empty($password)) {
        echo "<script>alert('Please enter both email and password.'); window.location.href='loginPage.php';</script>";
        exit();
    }

    $sql = "SELECT * FROM USER_ACCOUNT WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $userId = $row['user_id'];
                $_SESSION['user_id'] = $userId;
                $_SESSION['email'] = $row['email'];

                $roleQuery = "SELECT role FROM USERS WHERE user_id = ?";
                if ($roleStmt = $conn->prepare($roleQuery)) {
                    $roleStmt->bind_param("i", $userId);
                    $roleStmt->execute();
                    $roleResult = $roleStmt->get_result();

                    if ($roleRow = $roleResult->fetch_assoc()) {
                        $role = strtolower($roleRow['role']);

                        if ($role === 'admin') {
                            echo "<script>window.location.href='admin.php';</script>";
                            exit();
                        } else if ($role === 'accountant') {
                            echo "<script>window.location.href='accountant.php';</script>";
                            exit();
                        } else if ($role === 'member') {
                            $profileQuery = "SELECT account_type FROM MEMBER_PROFILE WHERE user_id = ?";
                            if ($profileStmt = $conn->prepare($profileQuery)) {
                                $profileStmt->bind_param("i", $userId);
                                $profileStmt->execute();
                                $profileResult = $profileStmt->get_result();

                                if ($profileRow = $profileResult->fetch_assoc()) {
                                    $accountType = strtolower($profileRow['account_type']);
                                    if ($accountType === 'loan') {
                                        echo "<script>window.location.href='loanAccount.php';</script>";
                                    } else {
                                        echo "<script>window.location.href='homepage.php';</script>";
                                    }
                                    exit();
                                } else {
                                    echo "<script>alert('Profile not found. You cannot log in without a profile.'); window.location.href='loginPage.php';</script>";
                                    exit();
                                }
                            } else {
                                echo "<script>alert('Error checking profile.'); window.location.href='loginPage.php';</script>";
                                exit();
                            }
                        } else {
                            echo "<script>alert('Unknown role!'); window.location.href='loginPage.php';</script>";
                            exit();
                        }
                    } else {
                        echo "<script>alert('User role not found.'); window.location.href='loginPage.php';</script>";
                        exit();
                    }
                } else {
                    echo "<script>alert('Error preparing role query!'); window.location.href='loginPage.php';</script>";
                    exit();
                }
            } else {
                echo "<script>alert('Incorrect password!'); window.location.href='loginPage.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Email not found.'); window.location.href='loginPage.php';</script>";
            exit();
        }

        $stmt->close();
    } else {
        echo "<script>alert('Database error: " . $conn->error . "'); window.location.href='loginPage.php';</script>";
        exit();
    }
}

$conn->close();
?>