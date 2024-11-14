<?php
// Get the username (email) from the query string
$username = isset($_GET['username']) ? $_GET['username'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
            background-image: url('image/bg.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
        }

        .container {
            display: flex;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 90%; /* Adjust width for smaller screens */
            max-width: 800px; /* Set a max-width for larger screens */
            height: 500px;
        }

        .text-section {
            background-color: #0072B1; /* TNB Blue */
            color: white;
            padding: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            width: 50%;
        }

        .form-section {
            padding: 60px;
            width: 50%;
            display: flex;
            align-items: center;
            flex-direction: column; /* Align items vertically */
        }

        h1 {
            margin: 0;
            font-size: 30px;
        }

        label {
            display: block;
            margin: 20 px 0 10px;
        }

        .password-container {
            position: relative;
            width: 100%;
            margin-bottom: 25px; /* Space between inputs */
        }

        input[type="password"],
        input[type="text"] {
            width: 90%;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            
        }

        .eye-icon {
            position: absolute;
            right: 0px;
            top: 50%;
            transform: translateY(-50%); /* Center vertically */
            cursor: pointer;
            width: 20px; /* Adjust width */
            height: 20px; /* Adjust height */
        }

        button {
            background-color: #0072B1; /* TNB Blue */
            color: white;
            border: none;
            padding: 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #005f8d; /* Darker shade for hover */
        }

       /* Media Queries for Smaller Screens */
      @media (max-width: 768px) {
        body {
          height: 100%;
          margin-top: 15vh;
        }
        .container {
          flex-direction: column;
          align-items: center;
          justify-content: space-around;
          padding: 8px; /* Reduce padding on smaller screens */
        }

        .text-section,
        .form-section {
          padding: 10px; /* Reduce padding on smaller screens */
          width: 100%;
        }

        h1 {
          font-size: 24px; /* Adjust heading size for smaller screens */
        }

        .form-section {
          display: flex; /* Allow for vertical alignment within form */
          flex-direction: column; /* Stack form elements vertically */
          align-items: center; /* Center form elements horizontally */
        }
      }
    </style>
</head>
<body>

    <div class="container">
        <div class="text-section">
            <h1>Hand Tools Inventory Tracking System</h1>
        </div>
        <div class="form-section">
            <form id="reset-password-form" method="POST">
                <h2>Forgot Password</h2>
                <label for="username">Username</label>
                <div class="password-container">
                <input type="text" id="username" name="username" required>
                </div>
                <label for="newPassword">New Password</label>
                <div class="password-container">
                    <input type="password" id="newPassword" name="new-password" required>
                    <img src="image/eye-close.png" class="eye-icon" id="eyeicon">
                </div>

                <label for="confirmPassword">Confirm Password</label>
                <div class="password-container">
                    <input type="password" id="confirmPassword" name="confirm-password" required>
                    <img src="image/eye-close.png" class="eye-icon" id="confirm-eyeicon">
                </div>
               
                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>
    
    <script>
       const resetPasswordForm = document.getElementById('reset-password-form');

resetPasswordForm.addEventListener('submit', (e) => {
    e.preventDefault();
    console.log("Reset Password form submitted"); // Check if this logs in the console
    const username = document.getElementById('username').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    // Validate if passwords match
    if (newPassword !== confirmPassword) {
        alert('Passwords do not match');
        return;
    }

    // Create an object to send with the request
    const data = {
        username: username,
        new_password: newPassword,
    };

    // Send data to backend using AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'forgot-password.php', true); // Use your PHP script URL here
    xhr.setRequestHeader('Content-Type', 'application/json');

    // Handle the response
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert('Password reset successfully!');
                // Optionally redirect to another page
                window.location.href = 'index.html';
            } else {
                alert('Error resetting password: ' + response.message);
            }
        } else {
            alert('Request failed. Please try again later.');
        }
    };

    // Send the data as a JSON object
    xhr.send(JSON.stringify(data));
});

let eyeicon = document.getElementById("eyeicon");
let newpassword = document.getElementById("newPassword");
let confirmPassword = document.getElementById("confirmPassword");

eyeicon.onclick = function() {
    if (newpassword.type === "password") {
        newpassword.type = "text";
        eyeicon.src = "image/eye-open.png";
    } else {
        newpassword.type = "password";
        eyeicon.src = "image/eye-close.png";
    }
}

let confirmEyeIcon = document.getElementById("confirm-eyeicon");
confirmEyeIcon.onclick = function() {
    if (confirmPassword.type === "password") {
        confirmPassword.type = "text";
        confirmEyeIcon.src = "image/eye-open.png";
    } else {
        confirmPassword.type = "password";
        confirmEyeIcon.src = "image/eye-close.png";
    }
}

    </script>
</body>
</html>