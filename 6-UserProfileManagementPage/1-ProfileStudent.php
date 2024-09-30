<?php
    if (isset($_SESSION['alert'])) {
        echo "<script>alert('" . $_SESSION['alert'] . "');</script>";
        unset($_SESSION['alert']); // Clear the alert message from the session
    }

    require_once("../5-UserSignInandRegistration/14-secure.php"); 
    include '11-CheckPermission.php';
    include '7-ChangePassword.php';
    include '8-ChangeEmail.php';
    include '9-ChangeResidence.php';
    include '5-ProfileManagement.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="icon" href="../Images\General\icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="13-ProfileManage.css">
    <script src="12-EditSave.js" defer></script>
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
                <a href="18-UserResidenceInformationpage.php">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 22V12H15V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Residence
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
            <p><a href="../5-UserSignInandRegistration/15-Logout.php" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</a></p>
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
                <form action="1-ProfileStudent.php" method="post" id="ChangeResidence">
                    <!-- <div class="form-group">
                        <label for="Residence">Residence:</label>
                        <input type="text" id="residence" name="residence" value="<?php echo $resName ?>" readonly>
                    </div> -->

                    <div class="form-group">
                    <label for="address">Select Residence:</label>
                    <select name="residenceID" id="residence" disabled required>
                      <option value="Select Residence"><?php echo $resName ?></option>
                      <optgroup label="Allen Webb Hall">
                        <option value="AWCN">Canterbury House</option>
                        <option value="AWCA">Canterbury Annex House</option>
                        <option value="AWSL">Salisbury House</option>
                        <option value="AWTR">Truro House</option>
                        <option value="AWWC">Winchester House</option>
                      </optgroup>
                      <optgroup label="Courtenay-Latimer Hall">
                        <option value="CHBE">Beit House</option>
                        <option value="CHJM">Jameson House</option>
                        <option value="CHOR">Oriel House</option>
                      </optgroup>
                      <optgroup label="Desmond Tutu Hall">
                        <option value="DTEK">Ellen Kuzwayo House</option>
                        <option value="DTAC">Amina Cachalia House</option>
                        <option value="HMCT">Calata House</option>
                        <option value="DTMS">Margaret Smith House</option>
                        <option value="HMH7">Hilltop 7 House</option>
                        <option value="HMH8">Hilltop 8 House</option>
                      </optgroup>
                      <optgroup label="Drostdy Hall">
                        <option value="DHAG">Allan Gray House</option>
                        <option value="DHCE">Celeste House</option>
                        <option value="DHGH">Graham House</option>
                        <option value="DHPA">Prince Alfred House</option>
                      </optgroup>
                      <optgroup label="Founders Hall">
                        <option value="FHBT">Botha House</option>
                        <option value="FHCL">College House</option>
                        <option value="FHCR">Cory House</option>
                        <option value="FHM">Matthews House</option>
                      </optgroup>
                      <optgroup label="Hobson Hall">
                        <option value="HHDH">Dingemans House</option>
                        <option value="HHHH">Hobson House</option>
                        <option value="HHLH">Livingstone House</option>
                        <option value="HHMH">Milner House</option>
                      </optgroup>
                      <optgroup label="Jan Smuts Hall">
                        <option value="JSAD">Adamson House</option>
                        <option value="JSAS">Atherstone House</option>
                        <option value="JSRS">Jan Smuts House</option>
                        <option value="JSNW">New House</option>
                      </optgroup>
                      <optgroup label="Miriam Makeba Hall">
                        <option value="MMCH">Chris Hani House</option>
                        <option value="MMPR">Piet Retief House</option>
                        <option value="MMTP">Thomas Pringle House</option>
                        <option value="MMWH">Walker House</option>
                      </optgroup>
                      <optgroup label="Kimberley Hall">
                        <option value="KHCB">Cullen Bowles House</option>
                        <option value="KHDB">De Beers House</option>
                        <option value="KHRP">Rosa Parks House</option>
                        <option value="KHGF">Goldfields House</option>
                      </optgroup>
                      <optgroup label="Lilian Ngoyi Hall">
                        <option value="LNCY">Centenary House</option>
                        <option value="LNRF">Ruth First House</option>
                        <option value="LNJS">Joe Slovo House</option>
                        <option value="LNVM">Victoria Mxenge House</option>
                      </optgroup>
                      <optgroup label="Nelson Mandela Hall">
                        <option value="NMSK">Stanley Kidd House</option>
                        <option value="NMAT">Adelaide Tambo House</option>
                        <option value="NMGB">Guy Butler House</option>
                        <option value="NMHJ">Helen Joseph House</option>
                      </optgroup>
                      <optgroup label="St Mary Hall">
                        <option value="SMJK">John Kotze House</option>
                        <option value="SMLB">Lilian Britten House</option>
                        <option value="SMOS">Olive Schreiner House</option>
                        <option value="SMPH">Phelps House</option>
                      </optgroup>
                    </select>
                </div>
                
                <div class="form-group" id="hallGroup">
                        <label for="Hall">Hall:</label>
                        <input type="text" id="hall" name="hall" value="<?php echo $hallName ?>" readonly>
                    </div>
                    <button type="button" id="res-cancel-button" class="save-button" name="res-cancel-button" hidden onclick="toggleCancelResidenceEdit()">Cancel</button>
                    <button type="button" id="res-edit-button" class="save-button" name="res-edit-button" onclick="toggleResidenceEdit()">Edit</button>
                    <button type="submit" id="res-save-button" class="save-button" name="res-save-button" hidden>Save</button>
                </form>
                
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