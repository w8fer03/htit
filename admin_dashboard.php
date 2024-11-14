<?php
session_start();
include("dbconn.php");

// Check if the user is not logged in
if (!isset($_SESSION['userID'])) {
    // Redirect to login page if not logged in
    header("Location: index.html");
    exit();
    }



// Fetch data from the database
$totalToolsQuery = mysqli_query($connect, "SELECT COUNT(*) AS total FROM tool");
$totalTools = mysqli_fetch_assoc($totalToolsQuery)['total'];

$goodToolsQuery = mysqli_query($connect, "SELECT COUNT(*) AS good FROM tool WHERE tool_Condition = 'Good'");
$goodTools = mysqli_fetch_assoc($goodToolsQuery)['good'];

$badToolsQuery = mysqli_query($connect, "SELECT COUNT(*) AS bad FROM tool WHERE tool_Condition = 'Bad'");
$badTools = mysqli_fetch_assoc($badToolsQuery)['bad'];

// Fetch recent activities
$recentActivitiesQuery = mysqli_query($connect, "
    SELECT 
        h.tool_ID,t.tool_Name, h.old_condition, h.new_condition, h.update_date,u.username
        FROM tool_condition_history h
        JOIN tool t ON h.record_ID = t.record_ID
        JOIN user u ON t.tool_Location = u.username
        ORDER BY h.update_date DESC
    LIMIT 10
");

if (!$recentActivitiesQuery) {
    die("Query failed: " . mysqli_error($connect));
}

$recentActivities = mysqli_fetch_all($recentActivitiesQuery, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="adminstyles.css"> <!-- Link to the CSS file -->
    
    <script>
    function confirmLogout(event) {
        event.preventDefault(); // Prevent default anchor click behavior
        const confirmation = confirm("Are you sure you want to log out?");
        if (confirmation) {
            window.location.href = 'logout.php'; // Redirect to logout.php
        }
    }
    </script>
</head>
<body>
    <nav>
        <div class="logo">
            <i class='bx bx-menu menu-icon'></i>
            <span class="logo-name">Hand Tools Inventory</span>
        </div>

        <div class="sidebar">
            <div class="logo">
                <i class='bx bx-menu menu-icon'></i>
                <span class="logo-name">Hand Tools Inventory</span>
            </div>

            <div class="sidebar-content">
                <ul class="lists">
                    <li class="list">
                        <a href="admin_dashboard.php" class="nav-link">
                            <i class='bx bxs-home icon'></i>
                            <span class="link">Dashboard</span>
                        </a>
                    </li>
                    <li class="list">
                        <a href="tools.php" class="nav-link">
                            <i class='bx bxs-wrench icon' ></i>
                            <span class="link">Tools</span>
                        </a>
                    </li>
                    <li class="list">
                        <a href="users.php" class="nav-link">
                            <i class='bx bxs-user-circle icon' ></i>
                            <span class="link">User</span>
                        </a>
                    </li>
                    <li class="list">
                        <a href="usageHistory.php" class="nav-link">
                            <i class='bx bx-history icon' ></i>
                            <span class="link">UsageHistory</span>
                        </a>
                    </li>
                </ul>

                <div class="bottom-content">
                    <li class="list">
                        <a href="#" class="nav-link">
                            <i class='bx bx-cog icon'></i>
                            <span class="link">Settings</span>
                        </a>
                    </li>
                    <li class="list">
                        <a href="logout.php" class="nav-link">
                            <i class='bx bx-log-out icon'></i>
                            <span class="link" onclick="confirmLogout(event)">Log Out</span>
                        </a>
                    </li>
                </div>
            </div>
        </div>
    </nav>
<section class="dashboard">
    <div class="dash-content">
        <div class="overview">
            <div class="title">
                <i class='bx bxs-tachometer'></i>
                <span class="text">Dashboard</span>
            </div>

            <div class="boxes">
                <div class="box box1">
                    <i class='bx bx-wrench'></i>
                    <span class="text">Total Tools</span>
                    <p><?php echo $totalTools; ?></p>
                </div>

                <div class="box box2">
                    <i class='bx bx-check-square'></i>
                    <span class="text">Good</span>
                    <p><?php echo $goodTools; ?></p>
                </div>

                <div class="box box3">
                    <i class='bx bx-hard-hat' ></i>
                    <span class="text">Bad</span>
                    <p><?php echo $badTools; ?></p>
                </div>
            </div>
        </div>

        <div class="activity">
        <div class="title">
                <i class='bx bx-time-five'></i>
                <span class="text">Recent Activities</span>
            </div>

            <table>
        <thead>
            <tr>
                <th>Tool Name</th>
                <th>Old Condition</th>
                <th>New Condition</th>
                <th>Update Date</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($recentActivities) > 0): ?>
                <?php foreach ($recentActivities as $activity): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($activity['tool_Name']); ?></td>
                        <td><?php echo htmlspecialchars($activity['old_condition']); ?></td>
                        <td><?php echo htmlspecialchars($activity['new_condition']); ?></td>
                        <td><?php echo htmlspecialchars($activity['update_date']); ?></td>
                        <td><?php echo htmlspecialchars($activity['username']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No recent activities found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
        </div>
    </div>
</section>
    

    <section class="overlay"></section>
    <script>
        const navBar = document.querySelector("nav"),
              menuBtns = document.querySelectorAll(".menu-icon"),
              overlay = document.querySelector(".overlay");

              menuBtns.forEach(menuBtn => {
                menuBtn.addEventListener("click", () => {
                    navBar.classList.toggle("open");
                });
              });

              overlay.addEventListener("click", () => {
                navBar.classList.toggle("open");
              })
        console.log(navBar, menuBtns, overlay);

    </script>
    
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Hand Tools Inventory. All Rights Reserved.</p>
    </footer>

</body>
</html>
