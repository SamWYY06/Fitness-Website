<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Set back link based on role, exactly matching login.php's approach
if ($_SESSION['role'] == 'admin') {
    $backLink = 'admin.php';
} else {
    $backLink = 'home.php';
}

// Function to check if user is admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                        url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=1470&auto=format&fit=crop') no-repeat center center fixed;
            background-size: cover;
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

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 2rem;
            color: #111;
        }

        .settings-section {
            margin-bottom: 2rem;
        }

        .settings-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }

        .language-select {
            width: 100%;
            max-width: 400px;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: white;
            margin-bottom: 2rem;
        }

        .theme-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }

        .theme-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .theme-title {
            font-weight: 500;
        }

        .theme-description {
            color: #666;
            font-size: 0.875rem;
        }

        .toggle {
            position: relative;
            width: 44px;
            height: 24px;
            background-color: #e4e4e4;
            border-radius: 12px;
            cursor: pointer;
        }

        .toggle::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background-color: white;
            border-radius: 50%;
            top: 2px;
            left: 2px;
            transition: transform 0.2s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .toggle.active {
            background-color: #007bff;
        }

        .toggle.active::after {
            transform: translateX(20px);
        }

        .container {
            background-color: rgba(255, 255, 255, 0.85);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .button-save {
            background-color: #4CAF50;
            color: white;
        }

        .button-save:hover {
            background-color: #45a049;
        }

        .button-back {
            background-color: #999999;
            color: #333;
        }

        .button-back:hover {
            background-color: #757575;
        }

        .button-back a {
            color: white;
            text-decoration: none;
        }
        
        .disabled-link {
            pointer-events: none;
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo"><a href="<?php echo isAdmin() ? 'admin.php' : 'home.php'; ?>" 
               class="<?php echo isAdmin() ? 'disabled-link' : ''; ?>">
                HuanFitnessPal
            </a>
        </div>
        <nav>
            <?php if (!isAdmin()): ?>
                <a href="home.php">Home</a>
                <div class="dropdown">
                    <button class="dropbtn">Health</button>
                    <div class="dropdown-content">
                        <a href="exercise.php">Exercise</a>
                        <a href="weight.php">Weight</a>
                        <a href="hydration.php">Hydration</a>
                    </div>
                </div>
                <a href="consultation.php">Book a consultation</a>
            <?php endif; ?>
        </nav>
        <div class="user-actions">
            <a href="setting.php">
                <img src="https://cdn-icons-png.flaticon.com/512/563/563541.png" alt="Settings" class="settings-icon">
            </a>
            <a href="profile.php">
                <img src="https://static.vecteezy.com/system/resources/thumbnails/020/911/737/small_2x/user-profile-icon-profile-avatar-user-icon-male-icon-face-icon-profile-icon-free-png.png" 
                 alt="Profile Picture" class="profile-pic">
            </a>
        </div>
    </header>

    <main>
        <div class="container">
            <br>
            <h1>Settings</h1>
            
            <div class="settings-section">
                <h3><label class="settings-label">Language</label></h3>
                <select class="language-select">
                    <option value="en">English</option>
                    <option value="es">Chinese</option>
                    <option value="fr">Malay</option>
                </select>
            </div>

            <div class="settings-section">
                <h3><label class="settings-label">Theme</label></h3>
                
                <div class="theme-option">
                    <div class="theme-info">
                        <div class="theme-title">Dark mode</div>
                        <div class="theme-description">Use dark mode for a more comfortable night time experience</div>
                    </div>
                    <div class="toggle"></div>
                </div>

                <div class="theme-option">
                    <div class="theme-info">
                        <div class="theme-title">Auto</div>
                        <div class="theme-description">Automatically switch between dark and light mode based on your device settings</div>
                    </div>
                    <div class="toggle"></div>
                </div>
            </div>
            <button type="button" class="button button-back"><a href="<?php echo $backLink; ?>" class="back">< Back</a></button>
                <button type="submit" class="button button-save">Save</button>
        </div>
    </main>

    <script>
        // Add toggle functionality
        document.querySelectorAll('.toggle').forEach(toggle => {
            toggle.addEventListener('click', () => {
                toggle.classList.toggle('active');
            });
        });
    </script>
</body>
</html>