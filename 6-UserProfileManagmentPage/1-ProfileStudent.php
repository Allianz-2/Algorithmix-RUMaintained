<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); 
    include '5-ProfileManagement.php';
    include '7-ChangePassword.php';
    include '8-ChangeEmail.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="4-ProfileManage.css">
    <script>
        function toggleEmailEdit() {
            var emailField = document.getElementById('email');
            var saveButton = document.getElementById('save-button');
            var cancelButton = document.getElementById('cancel-button');
            var editButton = document.getElementById('edit-button');
            
            if (emailField.readOnly) {
                emailField.readOnly = false;
                cancelButton.hidden = false;
                saveButton.hidden = false;
                editButton.hidden = true;
            }
        }

        function toggleCancelEdit() {
            var emailField = document.getElementById('email');
            var saveButton = document.getElementById('save-button');
            var cancelButton = document.getElementById('cancel-button');
            var editButton = document.getElementById('edit-button');

            emailField.readOnly = true;
            editButton.hidden = false;
            cancelButton.hidden = true;
            saveButton.hidden = true;
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h1>Account</h1>
            <p>Manage your account info.</p>
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
                <a href="2-UserResidenceInformationpage.php">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 22V12H15V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Residence
            </a>
            </div>
            <div class="menu-item">
                <a href="security page Sys Dev.html">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 16V12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 8H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Security
            </a>
            </div>
        </div>
        <div class="main-content">
            <h2><strong>Profile Details</strong></h2>
            <div class="profile-header">
                <div class="profile-header">
                    <div class="profile-picture-container">
                        <i class="fas fa-user default-icon" id="default-icon"></i>
            <img src="" alt="Profile picture" class="profile-picture" id="profile-image" style="display: none;">
                        <div class="edit-picture" onclick="document.getElementById('file-input').click()">
                            Edit 
                        </div>
                        <input type="file" id="file-input" accept="image/*" onchange="updateProfilePicture(event)">
                    </div>
                    <div class="profile-info">
                        <h1><?php echo $firstname . ' ' . $lastname ?></h1>
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
                        <strong>Residence:</strong> <?php echo $resName ?>
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
                <form action="1-ProfileStudent.php" method="post" id="ChangeEmail">
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
                <h2><strong>Residence Details</strong></h2>
                <div class="form-group">
                    <label for="Residence">Residence:</label>
                    <input type="text" id="residence" name="residence" value="<?php echo $resName ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="Hall">Hall:</label>
                    <input type="text" id="hall" name="hall" value="<?php echo $hallName ?>" readonly>
                </div>
                <!-- <form>
                    <div class="form-group">
                        <label for="address"></label>
                        <select name="Residence" id="Residence">
                            <option value="Select New Residence">Select New Residence: </option>
                           <optgroup label="Allen Webb Hall">
                           <option value="Canterbury House">Canterbury House</option>
                           <option value="Canterbury Annex">Canterbury Annex House</option>
                           <option value="Salisbury House">Salisbury House</option>
                           <option value="Truro House">Truro House</option>
                           <option value="Winchester House">Winchester House</option>
                           </optgroup>
                           <optgroup label="Courtenay-Latimer Hall">
                           <option value="Beit House">Beit House</option>
                           <option value="Jameson House">Jameson House</option>
                           <option value="Oriel House">Oriel House</option>
                           </optgroup>
                           <optgroup label="Desmond Tutu Hall">
                           <option value="Ellen Kuzwayo House">Ellen Kuzwayo House</option>
                           <option value="Amina Cachalia House">Amina Cachalia House</option>
                           <option value="Calata House">Calata House</option>
                           <option value="Margaret Smith House">Margaret Smith House</option>
                           <option value="Hilltop 3 House">Hilltop 3 House</option>
                           <option value="Oakdene House">Oakdene House</option>
                           </optgroup>
                           <optgroup label="Drostdy Hall">
                           <option value="Allan Gray House">Allan Gray House</option>
                           <option value="Celeste House">Celeste House</option>
                           <option value="Graham House">Graham House</option>
                           <option value="Prince Alfred House">Prince Alfred House</option>
                           </optgroup>
                           <optgroup label="Founders Hall">
                           <option value="Botha House">Botha House</option>
                           <option value="College House">College House</option>
                           <option value="Cory House">Cory House</option>
                           <option value="Matthews House">Matthews House</option>
                           </optgroup>
                           <optgroup label="Hobson Hall">
                           <option value="Dingemans House">Dingemans House</option>
                           <option value="Hobson House">Hobson House</option>
                           <option value="Livingstone House">Livingstone House</option>
                           <option value="Milner House">Milner House</option>
                           </optgroup>
                           <optgroup label="Jan Smuts Hall">
                           <option value="Adamson House">Adamson House</option>
                           <option value="Atherstone House">Atherstone House</option>
                           <option value="Jan Smuts House">Jan Smuts House</option>
                           <option value="New House">New House</option>
                           </optgroup>
                           <optgroup label="Miriam Makeba Hall">
                           <option value="Chris Hani House">Chris Hani House</option>
                           <option value="Piet Retief House">Piet Retief House</option>
                           <option value="Thomas Pringle House">Thomas Pringle House</option>
                           <option value="Walker House">Walker House</option>
                           </optgroup>
                           <optgroup label="Kimberley Hall">
                           <option value="Cullen Bowles House">Cullen Bowles House</option>
                           <option value="De Beers House">De Beers House</option>
                           <option value="Rosa Parks House">Rosa Parks House</option>
                           <option value="Goldfields House">Goldfields House</option>
                           </optgroup>
                           <optgroup label="Lilian Ngoyi Hall">
                           <option value="Centenary House">Centenary House</option>
                           <option value="Ruth First House">Ruth First House</option>
                           <option value="Joe Slovo House">Joe Slovo House</option>
                           <option value="Victoria Mxenge House">Victoria Mxenge House</option>
                           </optgroup>
                           <optgroup label="Nelson Mandela Hall">
                           <option value="Stanley Kidd House">Stanley Kidd House</option>
                           <option value="Adelaide Tambo House">Adelaide Tambo House</option>
                           <option value="Guy Butler House">Guy Butler House</option>
                           <option value="Helen Joseph House">Helen Joseph House</option>
                           </optgroup>
                           <optgroup label="St Mary Hall">
                           <option value="John Kotze House">John Kotze House</option>
                           <option value="Lilian Britten House">Lilian Britten House</option>
                           <option value="Olive Schreiner House">Olive Schreiner House</option>
                           <option value="Phelps House">Phelps House</option>
                           </optgroup>
                           </select>
                    </select>
                    </div>
                   
                    <button type="submit" class="save-button">Update Residence</button>
                </form> -->
            </div>
            <div class="info-section">
                <h2><strong>Change Password</strong></h2>
                <form action="1-ProfileStudent.php" method="POST">
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
</body>
</html>