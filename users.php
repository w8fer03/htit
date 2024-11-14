<?php
session_start();
include("dbconn.php");

// Check if the user is not logged in
if (!isset($_SESSION['userID'])) {
// Redirect to login page if not logged in
header("Location: index.html");
exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Users</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap"rel="stylesheet"/>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"rel="stylesheet"/>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the CSS file -->
    <script>
    function confirmLogout(event) {
        event.preventDefault(); // Prevent default anchor click behavior
        const confirmation = confirm("Are you sure you want to log out?");
        if (confirmation) {
            window.location.href = 'logout.php'; // Redirect to logout.php
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
        return false; // Prevents the delete action
    }
    return true; // Allows the delete action
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
                <i class='bx bx-log-out icon'></i>
                <span class="link" onclick="confirmLogout(event)">Log Out</span>
              </a>
            </li>
          </div>
        </div>
      </div>
    </nav>

    <div class="content">
            <h2>List of Users</h2>
            <div style="text-align: left; margin-bottom: 10px; margin-left: 70px;">
                <a href="add_users.php" class="btn-add-tool"><i class='bx bx-plus-circle'></i> Add User</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Action</th>       
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows go here -->
                    <?php
                    
                    $query = "SELECT * FROM user";
                    $result = mysqli_query($connect, $query);

                    if (!$result) {
                      die("Query Failed.".mysqli_error($connect));
                    }
                    else {

                      while($row = mysqli_fetch_assoc($result)){
                        ?>

                      <tr>
                        <td><?php echo $row['userID']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['userPassword']; ?></td>
                        <td><?php echo $row['userRole']; ?></td>
                        <td class="action-buttons">
                          <a href="edit_user.php?id=<?php echo $row['userID']; ?>" class="btn-edit" onclick="confirmEdit(event)"><i class='bx bx-edit-alt'></i></a>
                          <a href="delete_user.php?userID=<?php echo $row['userID']; ?>" class="btn-delete" onclick="confirmDelete(event)"><i class='bx bxs-message-square-minus'></i></a>
                        </td>
                      </tr>

                          <?php
                      }
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
