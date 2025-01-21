<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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

        .user-actions {
            display: flex;
            align-items: center;
            gap: 15px;
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

        main {
            flex-grow: 1;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        /* Original admin.php styles */
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

        p {
            color: #666;
            margin-bottom: 1.5rem;
        }

        .search-container {
            margin-bottom: 1.5rem;
            position: relative;
        }

        input[type="text"] {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.95rem;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
            background-color: white;
        }

        th, td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #555;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .action-links a {
            text-decoration: none;
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-right: 0.5rem;
            display: inline-block;
        }

        a[href*="edit"] {
            color: #4a90e2;
            border: 1px solid #4a90e2;
        }

        a[href*="edit"]:hover {
            background-color: #4a90e2;
            color: white;
        }

        a[href*="delete"] {
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        a[href*="delete"]:hover {
            background-color: #dc3545;
            color: white;
        }

        .add-button {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 4px;
            font-weight: 500;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        .add-button:hover {
            background-color: #218838;
        }

        @media (max-width: 768px) {
            main {
                padding: 1rem;
            }

            .container {
                padding: 1rem;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            th, td {
                padding: 0.5rem;
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
                <img src="https://static.thenounproject.com/png/5015027-200.png" alt="Sign Out" class="signout-pic">
            </a>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Users</h2>
            <p>Manage user data, view, search, add, edit, delete user data</p>
            
            <div class="search-container">
                <input type="text" name="search" placeholder="Search users by email, name or id">
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Weight</th>
                        <th>Gender</th>
                        <th>Membership</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "huanFitnessPal";

                    // Create connection
                    $conn = mysqli_connect($servername, $username, $password, $dbname);
                    // Check connection
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    $sql = "SELECT u.*,
                            w.weight as latest_weight,
                            m.membership_type
                            FROM users u
                            LEFT JOIN (
                                SELECT user_id, weight
                                FROM weight_log wl1
                                WHERE entry_date = (
                                    SELECT MAX(entry_date)
                                    FROM weight_log wl2
                                    WHERE wl1.user_id = wl2.user_id
                                )
                            ) w ON u.id = w.user_id
                            LEFT JOIN (
                                SELECT user_id, membership_type
                                FROM memberships
                                WHERE status = 'active'
                                AND CURRENT_DATE BETWEEN start_date AND end_date
                            ) m ON u.id = m.user_id
                            ORDER BY u.id";
                            
                    $result = mysqli_query($conn, $sql);

                    if ($result) {
                        if (mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['username'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . ($row['latest_weight'] ? $row['latest_weight'] . " kg" : "-") . "</td>";
                                echo "<td>" . $row['gender'] . "</td>";
                                echo "<td>" . ($row['membership_type'] ? $row['membership_type'] : "-") . "</td>";
                                echo "<td class='action-links'>";
                                echo "<a href='aedit.php?id=" . $row['id'] . "'>Edit</a>";
                                echo "<a href='adelete.php?id=" . $row['id'] . "'>Delete</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align: center;'>0 results</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align: center;'>Error: " . mysqli_error($conn) . "</td></tr>";
                    }
                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>

            <a href="signup.php" class="add-button">ADD</a>
        </div>
    </main>
</body>
</html>