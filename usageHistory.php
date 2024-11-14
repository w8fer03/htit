<?php
session_start();
include("dbconn.php");

// Check if the user is not logged in
if (!isset($_SESSION['userID'])) {
  header("Location: index.html");
  exit();
}

$username = $_SESSION['username'];
$userID = $_SESSION['userID'];


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Usage History</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="styles.css"> <!-- Link to the CSS file -->
    <!-- Boxicons CSS -->
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
    <script>
      function confirmLogout(event) {
        event.preventDefault(); // Prevent default anchor click behavior
        const confirmation = confirm("Are you sure you want to log out?");
        if (confirmation) {
          window.location.href = "logout.php"; // Redirect to logout.php
        }
      }
    </script>
  </head>
  <body>
    <nav>
      <div class="logo">
        <i class="bx bx-menu menu-icon"></i>
        <span class="logo-name">Hand Tools Inventory</span>
      </div>

      <div class="sidebar">
        <div class="logo">
          <i class="bx bx-menu menu-icon"></i>
          <span class="logo-name">Hand Tools Inventory</span>
        </div>

        <div class="sidebar-content">
          <ul class="lists">
            <li class="list">
              <a href="admin_dashboard.php" class="nav-link">
                <i class="bx bxs-home icon"></i>
                <span class="link">Dashboard</span>
              </a>
            </li>
            <li class="list">
              <a href="tools.php" class="nav-link">
                <i class="bx bxs-wrench icon"></i>
                <span class="link">Tools</span>
              </a>
            </li>
            <li class="list">
              <a href="users.php" class="nav-link">
                <i class="bx bxs-user-circle icon"></i>
                <span class="link">Users</span>
              </a>
            </li>
            <li class="list">
              <a href="usageHistory.php" class="nav-link">
                <i class="bx bx-history icon"></i>
                <span class="link">UsageHistory</span>
              </a>
            </li>
          </ul>

          <div class="bottom-content">
            <li class="list">
              <a href="#" class="nav-link">
                <i class="bx bx-cog icon"></i>
                <span class="link">Settings</span>
              </a>
            </li>
            <li class="list">
              <a href="logout.php" class="nav-link">
                <i class="bx bx-log-out icon"></i>
                <span class="link" onclick="confirmLogout(event)">Log Out</span>
              </a>
            </li>
          </div>
        </div>
      </div>
    </nav>

    <div class="content">
            <h2>Tool Condition History</h2>
            <table>
                <thead>
                    <tr>
                      <th>Tool ID</th>
                      <th>Old Condition</th>
                      <th>New Condition</th>
                      <th>Update Date</th> 
                      <th>User</th>     
                    </tr>
                </thead>
                <tbody>
                <?php
                    // PHP code to fetch and display usage history data
                    include("dbconn.php");

                    // Check if the user is logged in
                    if (!isset($_SESSION['userID'])) {
                      header("Location: index.html");
                      exit();
                    }

                    // Prepare the SQL query to fetch usage history based on tool location
                    
                    // Modify the SQL query to filter by user
                    $sql = "SELECT h.tool_ID,t.tool_Name, h.old_condition, h.new_condition, h.update_date,u.username
                            FROM tool_condition_history h
                            JOIN tool t ON h.record_ID = t.record_ID
                            JOIN user u ON t.tool_Location = u.username
                            ORDER BY h.update_date DESC";
                    $stmt = $connect->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?php echo $row['tool_Name']; ?></td>
                                <td><?php echo $row['old_condition']; ?></td>
                                <td><?php echo $row['new_condition']; ?></td>
                                <td><?php echo $row['update_date']; ?></td>
                                <td><?php echo $row['username']; ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="8">No usage history found.</td>
                        </tr>
                        <?php
                    }
              ?>
                </tbody>
            </table>
        </div>


    <section class="overlay"></section>
    <script>
      const navBar = document.querySelector("nav"),
        menuBtns = document.querySelectorAll(".menu-icon"),
        overlay = document.querySelector(".overlay");

      menuBtns.forEach((menuBtn) => {
        menuBtn.addEventListener("click", () => {
          navBar.classList.toggle("open");
        });
      });

      overlay.addEventListener("click", () => {
        navBar.classList.toggle("open");
      });
      console.log(navBar, menuBtns, overlay);
    </script>

<footer>
        <p>&copy; <?php echo date("Y"); ?> Hand Tools Inventory. All Rights Reserved.</p>
    </footer>
  </body>
</html>
