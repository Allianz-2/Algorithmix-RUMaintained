<?php
    include '10-RegistrationPHP.php';
?>
<!DOCTYPE html><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintainace Staff Registration - RUMaintained</title>
    <script src="12-RegistrationJavacript.js" defer></script>
    <link rel="stylesheet" href="11-Registration.css">

</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="wallpaper">
               <img src="../Images/General/rhodes image.jpeg" alt="wallpaper">
            </div>
        </div>
        <div class="right-panel">
            <h1>Maintenance Staff Registration</h1>
            <form action="4-MaintenanceStaffRegistration.php" method="POST" id="registration-form">
                <input type="hidden" id="role" name="role" value="MS">

                <div class="form-group">
                    <label for="firstname">Firstname</label>
                    <input type="text" id="firstname" name="firstname" required>
                </div>
                <div class="form-group">
                    <label for="lastname">Lastname</label>
                    <input type="text" id="lastname" name="lastname" required>
                </div>
                <div class="form-group">
                    <label for="staffNumber">Staff Number</label>
                    <input type="text" id="userID" name="userID" pattern="^SMS[A-Z]{1}[0-9]{4}$" placeholder="SMSX1234"required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email_address" name="email_address" title="Please use your university email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
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
                        I accept the <a href="../3-UserRegistration/1-TermsPolicy.html" id="termsLink">Terms and Conditions</a>
                    </label>
                </div>

                <button type="submit" name="submit" class="btn">Register</button>
            </form>
            <div id="error-message" class="error-message"></div>
        </div>
    </div>
</body>
</html>