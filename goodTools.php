<?php
session_start();

if (isset($_SESSION['message'])) {
  echo "<p class='success-message'>" . $_SESSION['message'] . "</p>";
  unset($_SESSION['message']);
} elseif (isset($_SESSION['error'])) {
  echo "<p class='error-message'>" . $_SESSION['error'] . "</p>";
  unset($_SESSION['error']);
}

// Check if the user is not logged in
if (!isset($_SESSION['userID'])) {
    // Redirect to login page if not logged in
    header("Location: index.html");
    exit();
}

// Fetch the username from the session
$username = $_SESSION['username']; // Assuming the username is stored in session


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Good Condition Tools</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet"/>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"rel="stylesheet"/>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the CSS file -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      

    function confirmLogout(event) {
        event.preventDefault(); // Prevent default anchor click behavior
        const confirmation = confirm("Are you sure you want to log out?");
        if (confirmation) {
            window.location.href = 'logout.php'; // Redirect to logout.php
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
              <a href="logout.php" class="nav-link">
                            <i class='bx bx-log-out icon'></i>
                            <span class="link" onclick="confirmLogout(event)">Log Out</span>
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
            <h2>List of Tools in Good Condition</h2>
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
              <div>
                  <a href="tool.php" class="btn-total-tools"><i class='bx bx-home-alt-2'></i> Total Tools</a>
                  <a href="goodTools.php" class="btn-available"><i class='bx bx-check-circle'></i> Good</a>
                  <a href="badTools.php" class="btn-inuse"><i class='bx bx-time'></i> Bad</a>
              </div>
              <div>
                  <i class='bx bx-search'></i><input type="text" id="searchInput" placeholder="Search..." class="search-input">
              </div>  
            </div>
            <table id="toolTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Quantity</th>
                        <th>Condition</th>
                                      
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows go here -->
                    <?php
                    /*session_start();*/
                    include("dbconn.php");
                    
                    // Retrieve list of all tools
                    $sql = "SELECT * FROM tool WHERE tool_Condition = 'Good' AND tool_Location = ?";
                    $stmt = $connect->prepare($sql);
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                          ?>
                          <tr>
                              <td><?php echo $row['tool_ID']; ?></td>
                              <td><?php echo $row['tool_Name']; ?></td>
                              <td><?php echo $row['tool_Description']; ?></td>
                              <td><?php echo $row['tool_Location']; ?></td>
                              <td><?php echo $row['tool_Quantity']; ?></td>
                              <td><?php echo $row['tool_Condition']; ?></td>
                          </tr>
                          <?php
                      }
                    }
                  $stmt->close();
                  $connect->close();
                  ?>

                   
                </tbody>
            </table>
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
