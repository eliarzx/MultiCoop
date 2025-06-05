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
                $studentID = $row['StudentID'];
                $firstName = $row['FirstName'];
                $lastName = $row['LastName'];
                $middleName = $row['MiddleName'];
                $gender = $row['Gender'];
                $bday = $row['BDay'];
            } else {
                echo "<script>alert('Profile not found.'); window.location.href='list_Profile.php';</script>";
                exit();
            }
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "<script>alert('Invalid request.'); window.location.href='list_Profile.php';</script>";
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
        $deleteSql = "DELETE FROM PROFILE WHERE ID = ?";
        if ($stmt = $conn->prepare($deleteSql)) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "<script>alert('Profile deleted successfully.'); window.location.href='list_Profile.php';</script>";
                exit();
            } else {
                echo "<script>alert('Error deleting profile.');</script>";
            }
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    }
?>
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> 
    <title>Delete Profile</title>
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

        .delete-btn {
            background-color: rgba(198, 180, 216, 0.8);
            color: white;
        }

        .delete-btn:hover {
            background-color: rgba(162, 143, 192, 0.9);
        }

        .cancel-btn {
            background-color: rgba(234, 212, 212, 0.8);
            color: white;
        }

        .cancel-btn:hover {
            background-color: rgba(214, 190, 190, 0.9);
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
        <h2 class="title">Delete Profile</h2>
    </div>

    <div class="container">
<form method="POST" onsubmit="return confirmDelete();">
    <div class="form-group">
        <label for="id">ID:</label>
        <input type="text" id="id" value="<?php echo htmlspecialchars($id); ?>" disabled>
    </div> 

    <div class="form-group">
        <label for="student-id">Student ID:</label>
        <input type="text" id="student-id" value="<?php echo htmlspecialchars($studentID); ?>" disabled>
    </div>

    <div class="form-group">
        <label for="last-name">Last Name:</label>
        <input type="text" id="last-name" value="<?php echo htmlspecialchars($lastName); ?>" disabled>
    </div>

    <div class="form-group">
        <label for="first-name">First Name:</label>
        <input type="text" id="first-name" value="<?php echo htmlspecialchars($firstName); ?>" disabled>
    </div>

    <div class="form-group">
        <label for="mname">Middle Name:</label>
        <input type="text" id="mname" value="<?php echo htmlspecialchars($middleName); ?>" disabled>
    </div>

    <div class="form-group">
        <label for="gender">Gender:</label>
        <input type="text" id="gender" value="<?php echo htmlspecialchars($gender); ?>" disabled>
    </div>

    <div class="form-group">
        <label for="birthdate">Birthdate:</label>
        <input type="text" id="birthdate" value="<?php echo htmlspecialchars($bday); ?>" disabled>
    </div>

    <div class="btn-group">
        <button type="submit" name="delete" class="btn delete-btn">Delete</button>
        <button type="button" class="btn cancel-btn" onclick="window.location.href='list_Profile.php'">Cancel</button>
    </div>
</form>
    </div>

    <script>
        function confirmDelete() {
            return confirm("This action is irreversible. Are you sure to delete this record?");
        }
    </script>

</body>
</html>