<?php
    include '10-RegistrationPHP.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall Secretary Registration - RUMaintained</title>
    <link rel="stylesheet" href="11-Registration.css">
    <script src="12-RegistrationJavacript.js" defer></script>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="wallpaper">
                <img src="../Images/General/rhodes image.jpeg" alt="wallpaper">
            </div>
        </div>
        <div class="right-panel">
            <h1>Hall Secretary Registration</h1>
            <form action="2-HallSecretaryRegistration.php" method="post" id="registration-form">
            <input type="hidden" id="role" name="role" value="HS">

            <div class="form-group">
                    <label for="firstname">Firstname</label>
                    <input type="text" id="firstname" name="firstname" required>
                </div>
                <div class="form-group">
                    <label for="lastname">Lastname</label>
                    <input type="text" id="lastname" name="lastname" required>
                </div>
                <div class="form-group">
                    <label for="userID">Staff Number</label>
                    <input type="text" id="userID" name="userID" pattern="^SHS[A-Z]{1}[0-9]{4}$" placeholder="SHSX0000"required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email_address" name="email_address" placeholder="Please use your university email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" pattern=".{8,}" title="Password must be at least 8 characters long" required>                
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>

                <div class="form-group">
                    <label for="hall">Select Hall:</label>
                    <select name="hall" id="hall">
                        <option value="Select Hall"></option>
                        <option value="AW">Allen Webb Hall</option>
                        <option value="CL">Courtenay-Latimer Hall</option>
                        <option value="DT">Desmond Tutu Hall</option>
                        <option value="DH">Drostdy Hall</option>
                        <option value="FH">Founders Hall</option>
                        <option value="HH">Hobson Hall</option>
                        <option value="JS">Jan Smuts Hall</option>
                        <option value="MM">Miriam Makeba Hall</option>
                        <option value="KH">Kimberley Hall</option>
                        <option value="LN">Lilian Ngoyi Hall</option>
                        <option value="NM">Nelson Mandela Hall</option>
                        <option value="SM">St Mary Hall</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <input type="checkbox" id="termsAndConditions" name="termsAndConditions" required>
                    <label for="termsAndConditions">
                        I accept the <a href="8-TermsAndPolicy.html" id="termsLink">Terms and Conditions</a>
                    </label>
                </div>

                <button type="submit" name="submit" class="btn">Register</button>
            </form>
            <div id="error-message" class="error-message"></div>
        </div>
    </div>

   

</body>
</html>