<?php
    include '10-RegistrationPHP.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Staff Registration</title>
    <link rel="icon" href="../Images\General\favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../5-UserSignInandRegistration\5-CSS\11-Registration.css">
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
        <a href="..\5-UserSignInandRegistration\5-RegistrationStep1.html" style="color: #81598a; text-decoration: none;"><i class="fas fa-chevron-left"></i> Go Back</a>
        <h1 style="margin-top: 5px; padding-top: 5px;"><strong>Maintenance Staff Registration</strong></h1>
            <form action="4-MaintenanceStaffRegistration.php" method="POST" id="registration-form">
                <input type="hidden" id="role" name="role" value="MS">

                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" pattern="[A-Za-z]{1,25}" title="First name should only contain letters and be up to 25 characters long" autofocus required>
                </div>
                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname" pattern="[A-Za-z]{1,25}" title="Last name should only contain letters and be up to 25 characters long" required>
                </div>
                <div class="form-group">
                    <label for="staffNumber">Staff Number</label>
                    <input type="text" id="userID" name="userID" pattern="^SMS[A-Z]{1}[0-9]{4}$" placeholder="SMSX1234" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email_address" name="email_address" placeholder="Please use your university email" maxlength="50" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" pattern=".{8,20}" title="Password must be at least 8 characters long" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>

                <div class="form-group">
                    <label for="specialisation">Select Department or Divisions</label>
                    <select name="specialisation" id="specialisation" required>
                        <option value="Select Department or Division"></option>
                        <option value="Electrical Maintenance">Electrical Maintenance</option>
                        <option value="Plumbing">Plumbing</option>
                        <option value="Carpentry">Carpentry</option>
                        <option value="HVAC">HVAC</option>
                        <option value="General Maintenance">General Maintenance</option>
                        <option value="Painting and Decorating">Painting and Decorating</option>
                        <option value="Groundskeeping and Landscaping">Groundskeeping and Landscaping</option>
                        <option value="Pest Control">Pest Control</option>
                        <option value="Security Systems Maintenance">Security Systems Maintenance</option>
                        <option value="Fire Safety Maintenance">Fire Safety Maintenance</option>
                        <option value="Waste Management">Waste Management</option>
                        <option value="Lift/Elevator Maintenance">Lift/Elevator Maintenance</option>
                        <option value="Roofing and Waterproofing">Roofing and Waterproofing</option>
                        <option value="Masonry">Masonry</option>
                        <option value="Appliance Repair">Appliance Repair</option>
                    </select>
                </div>

                <div class="form-group">
                    <input type="checkbox" id="termsAndConditions" name="termsAndConditions" required>
                    <label for="termsAndConditions">
                        I accept the <a href="../5-UserSignInandRegistration\8-TermsAndPolicy.html" id="termsLink">Terms and Conditions</a>
                    </label>
                </div>

                <button type="submit" name="submit" class="btn">Register</button>
            </form>
            <div id="error-message" class="error-message"></div>
        </div>
    </div>
</body>
</html>
