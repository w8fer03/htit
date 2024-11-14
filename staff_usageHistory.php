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

// Modify the SQL query to filter by user
$sql = "
        SELECT h.tool_ID, h.old_condition, h.new_condition, h.update_date
        FROM tool_condition_history h
        JOIN tool t ON h.record_ID = t.record_ID
        WHERE t.tool_Location = ?
        ORDER BY h.update_date DESC
    ";
$stmt = $connect->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tool Condition History</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="styles.css">
    <script>
        function confirmLogout(event) {
            event.preventDefault();
            const confirmation = confirm("Are you sure you want to log out?");
            if (confirmation) {
                window.location.href = 'logout.php';
            }
        }

        function searchTable() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const table = document.getElementById('toolTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let match = false;

                for (let j = 0; j < cells.length; j++) {
                    if (cells[j] && cells[j].textContent.toLowerCase().includes(input)) {
                        match = true;
                        break;
                    }
                }

                rows[i].style.display = match ? '' : 'none';
            }
        }

        // Attach an event listener to the search input for real-time filtering
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('searchInput').addEventListener('input', searchTable);
        });
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
              <a href="dashboard.php" class="nav-link">
                <i class="bx bxs-home icon"></i>
                <span class="link">Dashboard</span>
              </a>
            </li>
            <li class="list">
              <a href="tool.php" class="nav-link">
                <i class="bx bxs-wrench icon"></i>
                <span class="link">Tools</span>
              </a>
            </li>
            <li class="list">
              <a href="staff_usageHistory.php" class="nav-link">
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
              <a href="logout.php" class="nav-link" onclick="confirmLogout(event)">
                <i class='bx bx-log-out icon'></i>
                <span class="link" >Log Out</span>
              </a>
            </li>
          </div>
        </div>
      </div>
       <!-- New section for displaying the username -->
       <div class="profile">
            <i class='bx bx-user icon'></i> <!-- Profile icon -->
            <span class="username"><?php echo htmlspecialchars($username); ?></span>
        </div>
    </nav>
    <section class="overlay"></section>  

    <div class="content">

    <h2>Tool Condition History</h2>
    <table>
        <thead>
            <tr>
                <th>Tool ID</th>
                <th>Old Condition</th>
                <th>New Condition</th>
                <th>Update Date</th>
            </tr>
        </thead>
        <tbody>
        <?php
            // Display the data if any records are available
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['tool_ID'] . "</td>";
                    echo "<td>" . $row['old_condition'] . "</td>";
                    echo "<td>" . $row['new_condition'] . "</td>";
                    echo "<td>" . $row['update_date'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No history available.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php $connect->close(); ?>
    </div>

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
