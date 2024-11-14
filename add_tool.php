<?php
session_start();

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
    <title>Add Tools</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap"
      rel="stylesheet"
    />
    <!-- Boxicons CSS -->
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />

    <style>
      * {
        font-family: "Poppins", sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }
      body {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        background: #ececec;
        font-family: "Poppins", sans-serif;
        margin: 0;
      }
      nav {
        position: fixed;
        top: 0;
        left: 0;
        height: 70px;
        width: 100%;
        display: flex;
        align-items: center;
        background: #fff;
        box-shadow: 0 0 1px rgba(0, 0, 0, 0.1);
      }
      nav .logo {
        display: flex;
        align-items: center;
        margin: 0 24px;
      }
      .logo .menu-icon {
        color: #333;
        font-size: 40px;
        margin-right: 14px;
        cursor: pointer;
      }
      .logo .logo-name {
        color: #333;
        font-size: 22px;
        font-weight: 500;
      }
      nav .sidebar {
        position: fixed;
        top: 0;
        left: -100%;
        height: 100%;
        width: 260px;
        padding: 20px 0;
        background-color: #fff;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.5s ease;
      }
      nav.open .sidebar {
        left: 0;
      }
      .sidebar .sidebar-content {
        display: flex;
        height: 95%;
        flex-direction: column;
        justify-content: space-between;
        padding: 30px 16px;
      }
      .sidebar-content .list {
        list-style: none;
      }
      .list .nav-link {
        display: flex;
        align-items: center;
        margin: 8px 0;
        padding: 14px 12px;
        border-radius: 8px;
        text-decoration: none;
      }
      .lists .nav-link:hover {
        background-color: #0072b1;
      }
      .nav-link .icon {
        margin-right: 14px;
        font-size: 20px;
        color: #707070;
      }
      .nav-link .link {
        font-size: 16px;
        color: #707070;
        font-weight: 400;
      }
      .lists .nav-link:hover .icon,
      .lists .nav-link:hover .link {
        color: #fff;
      }
      .overlay {
        position: fixed;
        top: 0;
        left: -100%;
        height: 1000vh;
        width: 200%;
        opacity: 0;
        pointer-events: none;
        transition: all 0.4s ease;
        background: rgba(0, 0, 0, 0.3);
      }
      nav.open ~ .overlay {
        opacity: 1;
        left: 260px;
        pointer-events: auto;
      }
      .content {
        margin-top: 80px;
        display: block;
        justify-content: center;
        align-items: center;
        width: 100%;
        max-width: 70%;
      }
      h2 {
        text-align: center;
      }
      form {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        width: 100%;
        margin: auto;
          }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input[type="submit"] {
            background-color: #0072b1;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            opacity: 0.9;
        }
    </style>
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

    <section class="overlay"></section>

    <div class="content">
        <h2>Add New Tool</h2>
        <form action="add_tool.php" method="post">
            <div class="form-group">
                <label for="tool_ID">Tool ID:</label>
                <input type="text" id="tool_ID" name="tool_ID" required>
            </div>
        
            <div class="form-group">
                <label for="tool_Name">Tool Name:</label>
                <input type="text" id="tool_Name" name="tool_Name" required>
            </div>
            <div class="form-group">
                <label for="tool_Description">Tool Description:</label>
                <textarea id="tool_Description" name="tool_Description"></textarea>
            </div>
            <div class="form-group">
                <label for="tool_Location">Tool Location:</label>
                <select id="tool_Location" name="tool_Location" required>
                    <option value="">Select Location</option>
                    <option value="Afnan">Afnan</option>
                    <option value="Irfan">Irfan</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tool_Quantity">Tool Quantity:</label>
                <input type="number" id="tool_Quantity" name="tool_Quantity" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Add Tool">
            </div>
        </form>
    </div>
    
    <?php
    include("dbconn.php");


  // Check if the form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $tool_ID = $connect->real_escape_string($_POST['tool_ID']);
    $tool_Name = $connect->real_escape_string($_POST['tool_Name']);
    $tool_Description = $connect->real_escape_string($_POST['tool_Description']);
    $tool_Location = $connect->real_escape_string($_POST['tool_Location']);
    $tool_Quantity = (int)$_POST['tool_Quantity']; // Cast to integer

    // Prepare the SQL insert statement
    $sql = "INSERT INTO tool (tool_ID,tool_Name, tool_Description, tool_Location, tool_Quantity, tool_Condition) 
            VALUES ('$tool_ID','$tool_Name', '$tool_Description', '$tool_Location', $tool_Quantity, 'Good')";


    // Execute the query
    if ($connect->query($sql) === TRUE) {
      echo "<script type='text/javascript'>
      alert('Tool added successfully!');
      window.location.href = 'tools.php';
    </script>";
exit(); // Stop further execution
    } else {
        echo "Error: " . $sql . "<br>" . $connect->error;
    }
  }

    // Close the connection
    $connect->close();
    ?>

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
  </body>
</html>
