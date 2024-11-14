<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['userID'])) {
    header("Location: index.html");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tools</title>
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

        function confirmEdit(event) {
            if (!confirm('Edit this record?')) {
                event.preventDefault();
            }
        }

        function confirmDelete(event) {
            if (!confirm('Are you sure you want to delete this record?')) {
                event.preventDefault();
                return false;
            }
            return true;
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
        <h2>Tools</h2>
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
                    <th>Update Condition</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include("dbconn.php");

                $sql = "SELECT * FROM tool WHERE tool_Location = ?";
                $stmt = $connect->prepare($sql);
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                  $lastUpdated = new DateTime($row['last_condition_update']);
                  $currentDate = new DateTime();
                  $interval = $currentDate->diff($lastUpdated)->m;

                  // If more than a month has passed, prompt for update
                  $needsUpdate = $interval >= 1;
                  ?>
                  <tr>
                      <td><?php echo $row['tool_ID']; ?></td>
                      <td><?php echo $row['tool_Name']; ?></td>
                      <td><?php echo $row['tool_Description']; ?></td>
                      <td><?php echo $row['tool_Location']; ?></td>
                      <td><?php echo $row['tool_Quantity']; ?></td>
                      <td><?php echo $row['tool_Condition']; ?></td>
                      <td>
                      <?php if ($needsUpdate): ?>
                          <form method="POST" action="update_condition.php">
                              <input type="hidden" name="record_ID" value="<?php echo $row['record_ID']; ?>">
                              <select name="new_condition" required>
                                  <option value="">Select</option>
                                  <option value="Good">Good</option>
                                  <option value="Bad">Bad</option>
                              </select>
                              <button type="submit">Update</button>
                          </form>
                      <?php else: ?>
                          Updated Recently
                      <?php endif; ?>
                  </td>
              </tr>
              <?php
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