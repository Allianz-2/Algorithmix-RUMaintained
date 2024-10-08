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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<style>
    .password-container {
    position: relative;
}

.password-toggle {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    cursor: pointer;
}

.password-toggle i {
    font-size: 1.2em;
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
            <h1><strong>Welcome back to RUMaintained!</strong></h1><br><br><br>


            <form action="6-SignInPage.php" method="POST" id="signin-form">
                <div class="form-group">
                    <label for="userID">Username</label>
                    <input type="text" id="userID" name="userID" autofocus required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" required>
                        <span class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <br>
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

    <script>
        // Get the password input and the show button
        //const passwordInput = document.getElementById('password');
        //const showPasswordBtn = document.getElementById('showPassword');

        // Get the password input and the eye icon
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        // Toggle the password visibility when the icon is clicked
            togglePassword.addEventListener('click', function() {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            
            // Toggle icon between 'eye' and 'eye-slash'
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });


        // When the button is pressed, show the password
        showPasswordBtn.addEventListener('mousedown', function() {
            passwordInput.type = 'text';
        });

        // When the button is released, hide the password
        showPasswordBtn.addEventListener('mouseup', function() {
            passwordInput.type = 'password';
        });

        // Ensure the password remains hidden when the mouse leaves the button area
        //showPasswordBtn.addEventListener('mouseleave', function() {
          //  passwordInput.type = 'password';
       // });
    </script>






</body>
</html>