<?php
    include '13-Login.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="icon" href="../Images\General\favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../5-UserSignInandRegistration/5-CSS/6-SignInPage.css">
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="wallpaper">
               <img src="../Images/General/rhodes image.jpeg" alt="wallpaper">
            </div>
        </div>
        <div class="right-panel">
            <h1><strong>Welcome back to RUMaintained!</strong></h1><br><br><br>


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