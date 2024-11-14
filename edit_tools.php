<?php
include("dbconn.php");

$tool_Name = '';
$tool_Description = '';
$tool_Location = '';
$tool_Quantity = '';
$tool_Condition = '';

if (isset($_GET['id'])) {
    $recordID = $_GET['id'];

    $query = "SELECT * FROM tool WHERE record_ID = '$recordID'";
    $result = mysqli_query($connect, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($connect));
    } else {
        $row = mysqli_fetch_assoc($result);
        
        if ($row) {
            $tool_Name = $row['tool_Name'];
            $tool_Description = $row['tool_Description'];
            $tool_Location = $row['tool_Location'];
            $tool_Quantity = $row['tool_Quantity'];
            $tool_Condition = $row['tool_Condition']; // Assuming tool_Condition exists
        } else {
            echo "No tool found with ID: $recordID";
            exit; // Exit if no tool is found
        }
    }

    if (isset($_POST['edit_tools'])) {
        // Capture input values
        $tool_Name = $_POST['tool_Name'];
        $tool_Description = $_POST['tool_Description'];
        $tool_Location = $_POST['tool_Location'];
        $tool_Quantity = $_POST['tool_Quantity'];
        $tool_Condition = $_POST['tool_Condition']; // Assuming you have this field

        // Prepare statement for security
        $stmt = $connect->prepare("UPDATE tool SET tool_Name = ?, tool_Description = ?, tool_Location = ?, tool_Quantity = ?, tool_Condition = ? WHERE record_ID = ?");
        $stmt->bind_param("sssssi", $tool_Name, $tool_Description, $tool_Location, $tool_Quantity, $tool_Condition, $recordID);

        if ($stmt->execute()) {
            echo "<script type='text/javascript'>
                    alert('Tool updated successfully!');
                    window.location.href = 'tools.php';
                  </script>";
        } else {
            die("Query Failed: " . $stmt->error);
        }
        $stmt->close();
    }
}
?>

    <!DOCTYPE html>
    <html lang="en">
      <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Edit User</title>
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
            <h2>Edit Tool</h2>
            <form action="edit_tools.php?id=<?php echo $recordID; ?>" method="post">

            <div class="form-group">
                <label for="tool_Name">Tool Name:</label>
                <input type="text" id="tool_Name" name="tool_Name" value="<?php echo htmlspecialchars($tool_Name); ?>" required>
            </div>
            <div class="form-group">
                <label for="tool_Description">Tool Description:</label>
                <textarea id="tool_Description" name="tool_Description"><?php echo htmlspecialchars($tool_Description); ?></textarea>
            </div>
            <div class="form-group">
                <label for="tool_Location">Tool Location:</label>
                <select id="tool_Location" name="tool_Location" required>
                    <option value="">Select Location</option>
                    <option value="Afnan" <?php echo ($tool_Location === 'Afnan') ? 'selected' : ''; ?>>Afnan</option>
                    <option value="Irfan" <?php echo ($tool_Location === 'Irfan') ? 'selected' : ''; ?>>Irfan</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tool_Quantity">Tool Quantity:</label>
                <input type="number" id="tool_Quantity" name="tool_Quantity" value="<?php echo htmlspecialchars($tool_Quantity); ?>" required>
            </div>
            <div class="form-group">
                <label for="tool_Condition">Tool Status:</label>
                <select id="tool_Condition" name="tool_Condition" required>
                    <option value="">Select Status</option>
                    <option value="Good" <?php echo ($tool_Condition === 'Good') ? 'selected' : ''; ?>>Good</option>
                    <option value="Bad" <?php echo ($tool_Condition === 'Bad') ? 'selected' : ''; ?>>Bad</option>
                </select>
            </div>
            <div class="form-group">
                  <input type="submit" name="edit_tools" value="Update Tools">
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
    