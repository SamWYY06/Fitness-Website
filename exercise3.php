<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize user ID (for now, hardcoded)
$uid = 12345;

// If search is submitted
if (isset($_POST['search'])) {
    $date = $_POST['date'];

    $stmt = $conn->prepare("SELECT pushups, situps, squads FROM exro WHERE date = ? AND uid = ?");
    $stmt->bind_param("si", $date, $uid);
    $stmt->execute();
    $stmt->bind_result($pushups, $situps, $squads);

    if ($stmt->fetch()) {
        // Data was found and successfully fetched
    } else {
        echo "<script>alert('No data found for the selected date');</script>";
        // Reset the exercise variables
        $pushups = $situps = $squads = '';
    }

    // Close the statement
    $stmt->close();
}

// If edit is submitted
if (isset($_POST['edit'])) {
    $date = $_POST['date'];
    $pushups = $_POST['pushups'];
    $situps = $_POST['situps'];
    $squads = $_POST['squads'];


    if ($pushups < 0 || $situps < 0 || $squads < 0 || strlen($pushups) > 3 || strlen($situps) > 3 || strlen($squads) > 3) {
        echo "<script>alert('Please enter valid numbers');</script>";
    } else {
    $stmt = $conn->prepare("UPDATE exro SET pushups = ?, situps = ?, squads = ? WHERE date = ? AND uid = ?");
    $stmt->bind_param("iiisi", $pushups, $situps, $squads, $date, $uid);
    if ($stmt->execute()) {
        echo "<script>alert('Exercise routine updated');</script>";
    } else {
        echo "<script>alert('Error updating record" . $stmt->error . "');</script>";
    }
    $stmt->close();
    }
}

// If delete is submitted
if (isset($_POST['delete'])) {
    $date = $_POST['date'];

    // Proceed with deletion after user confirmation
    $stmt = $conn->prepare("DELETE FROM exro WHERE date = ? AND uid = ?");
    $stmt->bind_param("si", $date, $uid);

    if ($stmt->execute()) {
        echo "<script>alert('Exercise routine deleted');</script>";
        // Reset the exercise variables after deletion
        $pushups = $situps = $squads = '';
    } else {
        echo "<script>alert('Error deleting record" . $stmt->error . "');</script>";
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise Routine</title>
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
    <h2>Search Exercise Routine</h2>
    <form method="POST" action="">
        <label for="date">Date:</label>
        <input type="date" name="date" value="<?php echo isset($date) ? $date : ''; ?>" required>
        <button type="submit" name="search">Search</button>
    </form>

    <?php if (isset($date) && !empty($pushups)) { // Show inputs only if there are values ?>
        <form method="POST" action="" onsubmit="return validateForm()">
            <input type="hidden" name="date" value="<?php echo $date; ?>" readonly><br>

            <label for="pushups">Pushups:</label>
            <input type="number" name="pushups" value="<?php echo $pushups; ?>"><br>

            <label for="situps">Situps:</label>
            <input type="number" name="situps" value="<?php echo $situps; ?>"><br>

            <label for="squads">Squads:</label>
            <input type="number" name="squads" value="<?php echo $squads; ?>"><br>

            <button type="submit" name="edit">Edit</button>
            <button type="submit" name="delete" onclick="return confirm('Do you want to delete this record?');">Delete</button>
        </form>
    <?php } ?>
    <script>
        // Client-side validation to ensure no negative or four-digit numbers
        function validateForm() {
            var pushups = document.getElementById("pushups").value;
            var situps = document.getElementById("situps").value;
            var squads = document.getElementById("squads").value;

            // Check if any value is negative or more than three digits
            if (pushups < 0 || situps < 0 || squads < 0 || pushups.length > 3 || situps.length > 3 || squads.length > 3) {
                alert("Please enter valid numbers");
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>
</body>
</html>