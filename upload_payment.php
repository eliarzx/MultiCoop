<?php
include 'db_connect.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first.'); window.location.href='homepage.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['payment_file']) && $_FILES['payment_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['payment_file']['tmp_name'];
        $fileName = $_FILES['payment_file']['name'];
        $fileSize = $_FILES['payment_file']['size'];
        $fileType = $_FILES['payment_file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'pdf'];

        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = './uploads/payment_proofs/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $newFileName = $user_id . '_' . time() . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $sql = "INSERT INTO PAYMENT_PROOFS (user_id, file_path, uploaded_at) VALUES (?, ?, NOW())";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("is", $user_id, $dest_path);
                    $stmt->execute();
                    $stmt->close();

                    echo "<script>alert('File is successfully uploaded.'); window.location.href='homepage.php';</script>";
                    exit();
                } else {
                    echo "<script>alert('Database error: " . addslashes($conn->error) . "'); window.location.href='homepage.php';</script>";
                }
            } else {
                echo "<script>alert('Error moving the uploaded file.'); window.location.href='homepage.php';</script>";
            }
        } else {
            echo "<script>alert('Upload failed. Allowed file types: " . implode(", ", $allowedfileExtensions) . "'); window.location.href='homepage.php';</script>";
        }
    } else {
        echo "<script>alert('Error uploading file: " . $_FILES['payment_file']['error'] . "'); window.location.href='homepage.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='homepage.php';</script>";
}
?>