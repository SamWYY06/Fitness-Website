<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize user ID (for now, hardcoded)
$uid = 12345;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user input
    $date = $_POST['date'];
    $weight = $_POST['weight'];

    // Validate that numbers are not negative and do not exceed three digits
    if ($weight < 0 || $weight > 999.99) {
        echo "<script>alert('Please enter valid weight');</script>";
    } else {
        // Check if data already exists for this date and user
        $checkSql = "SELECT * FROM weight WHERE date = '$date' AND uid = '$uid'";
        $result = $conn->query($checkSql);

        if ($result->num_rows > 0) {
            // Data already exists for this date
            echo "<script>alert('You already entered for this date');</script>";
        } else {
            // Insert data into the exro table
            $sql = "INSERT INTO weight (date, uid, weight) VALUES 
                    ('$date', '$uid', '$weight')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Weight added');</script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Weight </title>
</head>

<body> 
<style>
body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #f5f5f5;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .logo {
            font-size: 1.2em;
            font-weight: bold;
        }
        nav {
            display: flex;
            gap: 20px;
        }
        nav div {
            position: relative;
        }
        header a { 
            color: black;
            padding: 12px 14px;
            display: block;
            text-align: center;
            text-decoration: none;
        }
        header a:hover {
            transform: scale(1.1);
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .dropbtn {
            background-color: inherit;
            color: black;
            padding: 12px 14px;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }
        .dropbtn:hover {
            transform: scale(1.1);
        }
        .user-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .profile-pic {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
        }
        .profile-pic:hover {
            transform: scale(1.1);
        }
        .settings-icon {
            width: 20px;
            height: 20px;
        }
        .settings-icon:hover {
            transform: scale(1.1);
        }
        main {
            flex-grow: 1;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo"><a href="#">HuanFitnessPal</a></div>
        <nav>
            <a href="#">Home</a>
            <div class="dropdown">
                <button class="dropbtn">Health</button>
                <div class="dropdown-content">
                    <a href="#">Exercise</a>
                    <a href="#">Weight</a>
                    <a href="hydration.php">Hydration</a>
                </div>
            </div>
            <a href="consultation.php">Book a consultation</a>
        </nav>
        <div class="user-actions">
            <a href="#settings">
                <img src="https://cdn-icons-png.flaticon.com/512/563/563541.png" alt="Settings" class="settings-icon">
            </a>
            <a href="#profile">
                <img src="https://static.vecteezy.com/system/resources/thumbnails/020/911/737/small_2x/user-profile-icon-profile-avatar-user-icon-male-icon-face-icon-profile-icon-free-png.png" 
                 alt="Profile Picture" class="profile-pic">
            </a>
        </div>
    </header>
    
    
    <form method="POST" action="" onsubmit="return validateForm()">
        <!-- Date Input -->
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required><br><br>

        <label for="weight">Weight:</label>
        <input type="number" id="weight" name="weight" step="0.01" placeholder="Enter your weight" required><br><br>

        <!-- Submit Button -->
        <button type="submit">Add</button>
    </form>

    <script>
        // Client-side validation to ensure no negative or four-digit numbers
        function validateForm() {
            var weight = document.getElementById("weight").value;

            // Check if any value is negative or more than three digits
            if (weight < 0 || weight > 999.99)  {
                alert("Please enter valid numbers");
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>
</body>
</html>