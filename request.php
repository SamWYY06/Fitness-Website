<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "huanFitnessPal";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle approve/decline actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['consultation_id'])) {
    $consultation_id = intval($_POST['consultation_id']);
    $status = $_POST['action'] === 'approve' ? 'approved' : 'declined';
    
    $update_sql = "UPDATE consultations SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($stmt, "si", $status, $consultation_id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: request.php");
        exit();
    }
}

// Get consultation requests
$query = "SELECT c.*, u.username 
          FROM consultations c 
          JOIN users u ON c.user_id = u.id 
          WHERE c.status = 'pending' 
          ORDER BY c.preferredDate ASC, c.preferredTime ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Consultation Requests - HuanFitnessPal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

        .signout-pic {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            object-fit: cover;
        }

        .signout-pic:hover {
            transform: scale(1.1);
        }

        .settings-icon {
            width: 20px;
            height: 20px;
        }

        .settings-icon:hover {
            transform: scale(1.1);
        }

        .user-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        main {
            flex-grow: 1;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        h2 {
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }

        .subtitle {
            color: #666;
            margin-bottom: 1.5rem;
        }

        .request-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .request-item {
            display: flex;
            align-items: center;
            padding: 16px;
            border-radius: 8px;
            background-color: #f8f8f8;
            transition: background-color 0.2s;
            border: 1px solid #eee;
        }

        .request-item:hover {
            background-color: #f0f0f0;
        }

        .request-info {
            flex-grow: 1;
            margin-right: 20px;
        }

        .request-name {
            font-weight: 500;
            margin-bottom: 4px;
            color: #333;
        }

        .request-details {
            color: #666;
            font-size: 14px;
            line-height: 1.4;
        }

        .request-actions {
            display: flex;
            gap: 8px;
        }

        .action-button {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: transform 0.1s;
        }

        .action-button:hover {
            transform: scale(1.05);
        }

        .approve-button {
            background-color: #28a745;
            color: white;
        }

        .reject-button {
            background-color: #dc3545;
            color: white;
        }

        .no-requests {
            text-align: center;
            padding: 40px;
            color: #666;
            background-color: #f8f8f8;
            border-radius: 8px;
            border: 1px solid #eee;
        }

        @media (max-width: 768px) {
            main {
                padding: 1rem;
            }

            .container {
                padding: 1rem;
            }

            .request-item {
                flex-direction: column;
                align-items: stretch;
            }

            .request-info {
                margin-right: 0;
                margin-bottom: 1rem;
            }

            .request-actions {
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">HuanFitnessPal</div>
        <nav>
            <a href="admin.php">Manage Users</a>
            <a href="request.php">Manage Requests</a>
        </nav>
        <div class="user-actions">
            <a href="setting.php">
                <img src="https://cdn-icons-png.flaticon.com/512/563/563541.png" alt="Settings" class="settings-icon">
            </a>
            <a href="login.php">
                <img src="https://static.thenounproject.com/png/5015027-200.png" 
                     alt="Sign Out" class="signout-pic">
            </a>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Consultation Requests</h2>
            <div class="subtitle">Handle incoming consultations</div>

            <div class="request-list">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="request-item">
                            <div class="request-info">
                                <div class="request-name"><?php echo htmlspecialchars($row['username']); ?></div>
                                <div class="request-details">
                                    Date: <?php echo date('d M Y', strtotime($row['preferredDate'])); ?><br>
                                    Time: <?php echo date('H:i', strtotime($row['preferredTime'])); ?><br>
                                    Consultant: <?php echo htmlspecialchars($row['consultantName']); ?><br>
                                    Notes: <?php echo htmlspecialchars($row['notes']); ?>
                                </div>
                            </div>
                            <div class="request-actions">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="consultation_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="action-button approve-button">✓</button>
                                </form>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="consultation_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="action" value="decline">
                                    <button type="submit" class="action-button reject-button">✕</button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-requests">
                        <p>No pending consultation requests</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
<?php mysqli_close($conn); ?>