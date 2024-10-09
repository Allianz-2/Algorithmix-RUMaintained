<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); 
    include '11-CheckPermission.php';
    include '7-ChangePassword.php';
    include '8-ChangeEmail.php';
    include '16-ChangeHall.php';
    include '20-ChangePhoto.php';

    include '5-ProfileManagement.php';

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
    <title>User Profile</title>
    <link rel="icon" href="../Images\General\favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="13-ProfileManage.css">
    <script src="12-EditSave.js" defer></script>
    <style>
.logout {
    position: absolute; /* Position the logout button absolutely */
    bottom: 20px; /* Distance from the bottom */
    left: 20px; /* Align with left padding */
    
}

    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h1>Account</h1>
            <p>Manage your account info.</p>
            <div class="menu-item">
                <a href="../1-GeneralPages\1-Home.php">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 13v10h-6v-6h-6v6h-6v-10h-3l12-12 12 12h-3zm-1-5.907v-5.093h-3v2.093l3 3z" fill="currentColor"/>
                    <path d="M9 22V12H15V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Home
            </a>
            </div>
            <div class="menu-item active">
                <a href="UserProfilePage.html">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Profile
            </a>
            </div>

            <div class="menu-item">
                <a href="..\6-UserProfileManagementPage\14-UserSecurity.php">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 16V12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 8H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Security
            </a>
            </div>

            <div class="menu-item">
                <a href="../1-GeneralPages\6-RedirectDashboard.php">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 3H10V10H3V3Z" fill="currentColor"/>
                    <path d="M14 3H21V10H14V3Z" fill="currentColor"/>
                    <path d="M14 14H21V21H14V14Z" fill="currentColor"/>
                    <path d="M3 14H10V21H3V14Z" fill="currentColor"/>
                </svg>
                Dashboard
                </a>
            </div>

            <div class="menu-item logout">
            <p><a href="../5-UserSignInandRegistration/15-Logout.php" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</a></p>
        </div>
        </div>
        <div class="main-content">
            <h2><strong>Profile Details</strong></h2>
                <div class="profile-header">
                <div class="profile-header">
                <div class="profile-picture-container">
                    <!-- Default user icon, hidden when a profile picture is available -->
                    <i class="fas fa-user default-icon" id="default-icon" style="display: <?php echo isset($photoPath) && !empty($photoPath) ? 'none' : 'block'; ?>;"></i>
                    
                    <!-- Profile picture display -->
                    <img src="<?php echo isset($photoPath) ? htmlspecialchars($photoPath) : ''; ?>" 
                        alt="Profile picture" 
                        class="profile-picture" 
                        id="profile-image" 
                        style="display: <?php echo isset($photoPath) && !empty($photoPath) ? 'block' : 'none'; ?>; width: 100%; height: 100%; object-fit: cover;">
                    
                    <!-- Edit option that opens the file input dialog -->
                    <div class="edit-picture" onclick="document.getElementById('profilePhoto').click()" style="display: <?php echo isset($photoPath) && !empty($photoPath) ? 'none' : 'block'; ?>;">
                        Edit 
                    </div>
                    
                    <!-- Form with file input for profile photo upload -->
                    <form id="upload-form" action="3-ProfileHS.php" method="post" enctype="multipart/form-data" style="display: none;">
                        <input type="file" name="profilePhoto" id="profilePhoto" accept="image/*" onchange="document.getElementById('upload-form').submit()">
                    </form>
                </div>
                
                <!-- Display user profile information -->
                <div class="profile-info">
                    <h1><?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></h1>
                </div>
            </div>

                    <!-- <button class="edit-button">Edit profile</button> -->
                </div>

            <div class="info-section">
                <h2><strong>User Information</strong></h2>
                <div class="user-info">
                    <div class="info-item">
                        <strong>Role:</strong> <?php echo $roleFull ?>
                    </div>

                    <div class="info-item">
                        <strong>Hall:</strong> <?php echo $hallName ?>
                    </div>
                    <div class="info-item">
                        <strong>Email:</strong> <?php echo $email ?>
                    </div>
                    <!-- <div class="info-item">
                        <strong>Contact Number:</strong> 123903922
                    </div> -->
                </div>
        
            <div class="info-section">
                <h2><strong>Personal Information</strong></h2>
                <form action="3-ProfileHS.php" method="post" id="ChangeEmail">
                    <div class="form-group">
                        <label for="fullName">Full Name</label>
                        <input type="text" id="fullName" name="fullName" value="<?php echo $firstname . ' ' . $lastname; ?>" readonly>
                    </div>
                    <!-- <div class="form-group">
                        <label for="birthDate">Date of Birth</label>
                        <input type="date" id="birthDate" name="birthDate">
                    </div> -->
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?php echo $email; ?>" readonly>
                    </div>
                    <button type="button" id="cancel-button" class="save-button" name="cancel-button" hidden onclick="toggleCancelEdit()">Cancel</button>
                    <button type="button" id="edit-button" class="save-button" name="edit-button" onclick="toggleEmailEdit()">Edit</button>
                    <button type="submit" id="save-button" class="save-button" name="save-button" hidden>Save</button>


                    </form>
            </div>
            <div class="info-section">
                <h2><strong>Hall Details</strong></h2>
                <form action="3-ProfileHS.php" method="post" id="ChangeHall">
                    <div class="form-group">
                        <label for="hall">Select Hall:</label>
                        <select name="hallChange" id="hallChange" disabled required>
                            <option value=""><?php echo $hallName?></option>
                            <option value="AW">Allen Webb Hall</option>
                            <option value="CH">Courtenay-Latimer Hall</option>
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
                
                    <button type="button" id="hall-cancel-button" class="save-button" name="hall-cancel-button" hidden onclick="toggleCancelHallEdit()">Cancel</button>
                    <button type="button" id="hall-edit-button" class="save-button" name="hall-edit-button" onclick="toggleHallEdit()">Edit</button>
                    <button type="submit" id="hall-save-button" class="save-button" name="hall-save-button" hidden>Save</button>
                </form>
            </div>
                

            <div class="info-section">
                <h2><strong>Change Password</strong></h2>
                <form action="3-ProfileHS.php" method="POST">
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <input type="password" id="currentPassword" name="currentPassword" required>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" id="newPassword" name="newPassword" pattern=".{8,}" title="Password must be at least 8 characters long" required>                </div>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm New Password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" pattern=".{8,}" title="Password must be at least 8 characters long" required>                </div>
                    </div>
                    <button type="submit" name="submit-password" class="save-button">Change Password</button>
                    </form>
            </div>
        </div>
    </div>
    <script>
function confirmLogout() {
    // Prompt the user for confirmation
    var confirmation = confirm("Are you sure you want to log out?");
    // If the user confirms, allow the logout action
    return confirmation; // true if confirmed, false if canceled
}
</script>
</body>
</html>