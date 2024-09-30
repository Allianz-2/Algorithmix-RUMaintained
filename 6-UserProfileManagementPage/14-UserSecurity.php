<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); 
    include '17-DeleteUser.php';

    if (isset($_SESSION['alert'])) {
        echo "<script>alert('" . $_SESSION['alert'] . "');</script>";
        unset($_SESSION['alert']); // Clear the alert message from the session
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Settings</title>
    <link rel="stylesheet" href="../6-UserProfileManagementPage\6-CSS\17-UserSecurity.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h1>Account</h1>
            <p>Manage your account info.</p>
            <div class="menu-item">
            <a href="../1-GeneralPages\1-Home.php">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 13v10h-6v-6h-6v6h-6v-10h-3l12-12 12 12h-3zm-1-5.907v-5.093h-3v2.093l3 3z" fill="currentColor"/>
                    <path d="M9 22V12H15V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Home
            </a>
            </div>
            <div class="menu-item">
                <a href="../1-GeneralPages/7-RedirectProfile.php">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Profile
            </a>
            </div>
            <div class="menu-item">
                <a href="18-UserResidenceInformationpage.php">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 22V12H15V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Residence
            </a>
            </div>
            <div class="menu-item active">
                <a href="security page Sys Dev.html">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 16V12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 8H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Security
            </a>
            </div>
            <div class="menu-item">
            <p><a href="../5-UserSignInandRegistration/15-Logout.php" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i>Log Out</a></p>
        </div>
        </div>
        <div class="main-content">
            <h2>Security Settings</h2>
            <div class="profile-details">
                <h3>Password Reset/Recovery</h3>
                <p>If you've forgotten your password, you can reset it here. For security reasons, we'll send a password reset link to your registered email address.</p>
                <form>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <button type="submit" class="save-button">Send Reset Link</button>
                </form>
                <!-- <div class="info-section">
                    <h4>Two-Factor Authentication</h4>
                    <p>Enhance your account security by enabling two-factor authentication.</p>
                    <button class="edit-button">Enable 2FA</button>
                </div> -->
            </div>
            <div class="profile-details">
                <h3>Delete Account</h3>
                <p>If you wish to delete your account, please enter your email address and confirm your decision. This action is irreversible.</p>
                <form action="14-UserSecurity.php" method="POST">
                    <div class="form-group">
                        <label for="delete-email">Email Address</label>
                        <input type="email" id="delete-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm-delete">Type "DELETE" to confirm</label>
                        <input type="text" id="confirm-delete" name="confirm-delete" required>
                    </div>
                    <button type="submit" name="delete-account" class="save-button">Delete Account</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>