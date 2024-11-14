<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
            width: 800px;
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
        }

        h1 {
            margin: 0;
            font-size: 30px;
        }

        label {
            display: block;
            margin: 20px 0 10px;
        }

        input[type="text"],
        input[type="password"] {
            width: 90%;
            padding: 15px;
            margin-bottom: 25px;
            border: 1px solid #ccc;
            border-radius: 4px;
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

        button:disabled{
            background-color: #ccc;
            cursor: not-allowed;
        }

        .forgot-password{
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #0072B1;
        }

        .forgot-password:hover{
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="text-section">
            <h1>Hand Tools Inventory Tracking System</h1>
        </div>
        <div class="form-section">
            <form action="forgotPass.php" method="post">
                <h2>Reset Password</h2>
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>


                <button type="submit" id="login-button" >Send Link</button>
            </form>
        </div>
    </div>

</body>
</html>