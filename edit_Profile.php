<!DOCTYPE html>
<html lang="en">

<?php 
include 'db_connect.php'; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM PROFILE WHERE ID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $studentID = htmlspecialchars($row['StudentID']);
            $firstName = htmlspecialchars($row['FirstName']);
            $lastName = htmlspecialchars($row['LastName']);
            $middleName = htmlspecialchars($row['MiddleName']);
            $gender = htmlspecialchars($row['Gender']);
            $bday = htmlspecialchars($row['BDay']);
        } else {
            echo "<script>alert('User not found.'); window.location.href='list_Profile.php';</script>";
            exit();
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
        exit();
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='list_Profile.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['student_number'], $_POST['first_name'], $_POST['last_name'], $_POST['middle_name'], $_POST['gender'], $_POST['bday'])) {
        $newStudentID = trim($_POST['student_number']);
        $newFirstName = trim($_POST['first_name']);
        $newLastName = trim($_POST['last_name']);
        $newMiddleName = trim($_POST['middle_name']);
        $newGender = trim($_POST['gender']);
        $newBDay = trim($_POST['bday']);

        $updateSql = "UPDATE PROFILE SET StudentID=?, FirstName=?, LastName=?, MiddleName=?, Gender=?, BDay=? WHERE ID=?";

        if ($updateStmt = $conn->prepare($updateSql)) {
            $updateStmt->bind_param("ssssssi", $newStudentID, $newFirstName, $newLastName, $newMiddleName, $newGender, $newBDay, $id);

            if ($updateStmt->execute()) {
                echo "<script>alert('User updated successfully.'); window.location.href='profile_Template.php';</script>";
            } else {
                echo "<script>alert('Error updating user: " . $updateStmt->error . "');</script>";
            }

            $updateStmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "<script>alert('Missing form data. Please fill all fields.');</script>";
    }
}
?>
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Noto Looped Thai UI", Helvetica, sans-serif;
        }

        body {
            background-color: whitesmoke;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            padding-top: 40px; 
        }

        .title-container {
            background: #CEE0E6;
            backdrop-filter: blur(10px);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            width: 50%;
            max-width: 600px;
            margin-bottom: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .title {
            font-size: 32px;
            font-weight: bold;
            color: white;
        }

        .container {
            width: 50%;
            max-width: 600px;
            background: rgba(206, 224, 230, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            font-size: 16px;
            font-weight: bold;
            color: black; 
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid rgba(0, 0, 0, 0.0);
            border-radius: 5px;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.9);
            color: black;
            outline: none;
        }

        .form-group input::placeholder {
            color: rgba(0, 0, 0, 0.6); 
        }

        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            text-transform: uppercase;
        }

        .update-btn {
            background-color: rgba(198, 180, 216, 0.8);
            color: white;
        }

        .update-btn:hover {
            background-color: rgba(162, 143, 192, 0.9);
        }

        .cancel-btn {
            background-color: rgba(234, 212, 212, 0.8);
            color: white;
        }

        .cancel-btn:hover {
            background-color: rgba(214, 190, 190, 0.9);
        }
        
        .form-group select {
            width: 100%; 
            padding: 12px; 
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 5px; 
            font-size: 15px; 
            background: rgba(255, 255, 255, 0.95); 
            color: black; 
            outline: none; 
            cursor: pointer; 
            appearance: none; 
        }

        .form-group select::-ms-expand {
            display: none;
        }

        .form-group select:focus {
            border: 1px solid #A28FC0; 
            background: white; 
        }
        
    </style>
</head>
<body>
    
    <ul class="navbar">
        <li><a href="homepage.php">HOME</a></li>
        <li><a href="#news">NEWS</a></li>
        <li><a href="#announcement">ANNOUNCEMENTS</a></li>
        <li><a href="about_Us.php">ABOUT US</a></li>
        <li><a href="contact_Us.php">CONTACT US</a></li>

        <li class="dropdown">
            <a href="javascript:void(0)">SETTINGS</a>
            <ul class="dropdown-content">
                <li class="user-dropdown">
                    <a href="javascript:void(0)">USER</a>
                    <ul class="user-dropdown-content">
                        <li><a href="list_Profile.php">Profile</a></li>
                        <li><a href="list_User.php">Account</a></li>
                        <li><a href="list_Schedule.php">Schedule</a></li>
                        <li><a href="index.php">Log-out</a></li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
    
    <div class="title-container">
        <h2 class="title">Update Profile</h2>
    </div>

    <div class="container">
          <form method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

            <div class="form-group">
                <label for="student-number">Student Number:</label>
                <input type="text" name="student_number" id="student-number" value="<?php echo htmlspecialchars($studentID); ?>" required>
            </div>

            <div class="form-group">
                <label for="last-name">Last Name:</label>
                <input type="text" name="last_name" id="last-name" value="<?php echo htmlspecialchars($lastName); ?>" required>
            </div>

            <div class="form-group">
                <label for="first-name">First Name:</label>
                <input type="text" name="first_name" id="first-name" value="<?php echo htmlspecialchars($firstName); ?>" required>
            </div>

            <div class="form-group">
                <label for="middle-name">Middle Name:</label>
                <input type="text" name="middle_name" id="middle-name" value="<?php echo htmlspecialchars($middleName); ?>">
            </div>

            <div class="form-group">
                <label for="gender">Gender:</label>
                <select name="gender" id="gender" required>
                    <option value="Male" <?php if ($gender == "Male") echo "selected"; ?>>Male</option>
                    <option value="Female" <?php if ($gender == "Female") echo "selected"; ?>>Female</option>
                </select>
            </div>

            <div class="form-group">
                <label for="bday">Birthdate:</label>
                <input type="date" name="bday" id="bday" value="<?php echo htmlspecialchars($bday); ?>" required>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn update-btn">Update</button>
                <button type="button" class="btn cancel-btn" onclick="window.location.href='profile_Template.php'">Cancel</button>
            </div>
        </form>
    </div>

</body>
</html>