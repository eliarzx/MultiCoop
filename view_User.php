<!DOCTYPE html>
    <?php 
        include 'db_connect.php'; 

        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $sql = "SELECT * FROM USER WHERE ID = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
                    $studentID = htmlspecialchars($row['StudentID']);
                    $firstName = htmlspecialchars($row['FirstName']);
                    $lastName = htmlspecialchars($row['LastName']);
                    $middleName = htmlspecialchars($row['MiddleName']);
                    $username = htmlspecialchars($row['Username']);
                    $password = htmlspecialchars($row['Password']); 
                } else {
                    echo "<script>alert('User not found.'); window.location.href='list_User.php';</script>";
                    exit();
                }
                $stmt->close();
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "<script>alert('Invalid request.'); window.location.href='list_User.php';</script>";
            exit();
        }
        ?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User</title>
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
            font-size: 36px; 
            font-weight: bold;
            color: white;
        }

        .container {
            width: 50%;
            max-width: 600px;
            background: rgba(206, 224, 230, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px; 
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .info-group {
            margin-bottom: 18px; 
            text-align: left;
        }

        .info-group label {
            font-size: 18px; 
            font-weight: bold;
            color: black;
            display: block;
            margin-bottom: 8px;
        }

        .info-group p {
            background: rgba(255, 255, 255, 0.9);
            padding: 12px; 
            border-radius: 5px;
            font-size: 16px; 
            color: black;
            text-align: left;
        }

        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        .back-btn {
            width: 100%;
            padding: 14px; 
            font-size: 18px; 
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            text-transform: uppercase;
            background-color: rgba(234, 212, 212, 0.8);
            color: white;
        }

        .back-btn:hover {
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
        <h2 class="title">View User</h2>
    </div>

     <div class="container">
        <div class="info-group">
            <label>Student Number:</label>
            <p><?= $studentID ?></p>
        </div>

        <div class="info-group">
            <label>Last Name:</label>
            <p><?= $lastName ?></p>
        </div>

        <div class="info-group">
            <label>First Name:</label>
            <p><?= $firstName ?></p>
        </div>
         
        <div class="info-group">
            <label>Middle Name:</label>
            <p><?= $middleName ?></p>
        </div>

        <div class="info-group">
            <label>Username:</label>
            <p><?= $username ?></p>
        </div>

        <div class="info-group">
            <label>Password:</label>
            <p><?= $password ?></p> 
        </div>

        <div class="btn-group">
            <button type="button" class="back-btn" onclick="window.location.href='list_User.php'">Back</button>
        </div>
    </div>

</body>
</html>