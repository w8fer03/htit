<?php
session_start();
include("dbconn.php");

// Check if the user is not logged in
if (!isset($_SESSION['userID'])) {
    // Redirect to login page if not logged in
    header("Location: index.html");
    exit();
}

$successMessage = "";

if(isset($_POST['add_users'])) {

  $user_name = $_POST['username'];
  $user_Password = $_POST['userPassword'];

  $query = "INSERT INTO user (username, userPassword) VALUES ('$user_name', '$user_Password')";

  $result = mysqli_query($connect, $query);

      if (!$result) {
        die("Query Failed.".mysqli_error($connect));
      }
      else {
        echo "<script type='text/javascript'>
              alert('User added successfully!');
              window.location.href = 'users.php';
              </script>";

        
      }
    }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add User</title>
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
        min-height: 100%;
        background: #ececec;
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
        margin-top: 60px;
        padding: 20px;
        width: 100%;
      }
      h2 {
        text-align: center;
      }
      form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

    function confirmEdit(event) {
            if (!confirm('Edit this record?')) {
                event.preventDefault();
            }
        }

    function confirmDelete(event) {
           if (!confirm('Are you sure you want to delete this record?')) {
                  event.preventDefault();
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
        <h2>Add New User</h2>
        <form action="add_users.php" method="post">
            <div class="form-group">
                <label for="username">User Name:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="userPassword">Password:</label>
                <input id="userPassword" name="userPassword" required></input>
            </div>
            <div class="form-group">
                <input type="submit" name = "add_users" value="Add User">
            </div>
        </form>
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
  </body>
</html>
