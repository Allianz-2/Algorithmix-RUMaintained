<?php
    include '13-Login.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In </title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f6f8fa;
            background-image: url("../Images/General/purple\ gradient\ background.jpeg");
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            display: flex;
            width: 1000px;
            height: 650px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .left-panel {
            flex: 1;
            background-color: #0d1117;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
            overflow: hidden;
        }
        .wallpaper {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        .wallpaper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        .right-panel {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
        }
        h1 {
            font-size: 24px;
            font-weight: 100;
            margin-bottom: 10px;
        }
        h2 {
            font-size: 20px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }

        .form-group-inline {
            margin-bottom: 15px;
            display: flex;
            align-items: center; /* Ensure vertical alignment for all elements */
        }
        .form-group-inline input[type="checkbox"] {
            margin-right: 5px; /* Space between checkbox and label */
            margin-bottom: 10px;
            
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #d0d7de;
            border-radius: 4px;
        }
        .btn_signin {
            display: inline-block;
            padding: 10px 20px;
            background-color: #81589a;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #aaa;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #81589a;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            text-align: center;
            margin-bottom: 10px;
            width: 100%;
        }
        .forgot-password {
            display: inline-block;
            margin-top: 5px;
            color: #0969da;
            text-decoration: none;
            font-size: 14px;
        }
        .register-container {
            margin-top: 20px;
            font-size: 14px; 
        }
        .register-link {
            color: #0969da;
            text-decoration: none;
        }
        .error-message {
            color: #d73a49;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="wallpaper">
               <img src="../Images/General/rhodes image.jpeg" alt="wallpaper">
            </div>
        </div>
        <div class="right-panel">
            <h1>Welcome back to RUMaintained</h1>
            <h2>Sign In</h2>

            <form action="6-SignInPage.php" method="POST" id="signin-form">
                <div class="form-group">
                    <label for="userID">Username</label>
                    <input type="text" id="userID" name="userID" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <a href="1-ForgotPaswordPage.html" class="forgot-password">Forgot password?</a>
                </div>
                <div class="form-group-inline">
                    <input type="checkbox" id="remember-me" name="remember-me">
                    <label for="remember-me">Remember me</label>
                </div>
                <?php 
                    if (isset($_GET['redirect'])) {
                        // Output a hidden input field with the value of the 'redirect' parameter
                        echo '<input type="hidden" id="current_url" name="current_url" value="' . htmlspecialchars($_GET['redirect']) . '">';   
                    }
                ?>
                <button type="submit" name="submit" class="btn_signin">Sign In</button>
            </form>
            <div class="register-container">
                New to RUMaintained? <a href="../5-UserSignInandRegistration/5-RegistrationStep1.html" class="register-link">Register an account</a>
            </div>
            <div id="error-message" class="error-message"></div>
        </div>
    </div>
</body>
</html>