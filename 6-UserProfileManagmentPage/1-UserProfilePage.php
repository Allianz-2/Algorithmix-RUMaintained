<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body, html {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #f8f8f8;
            color: #333;
        }
        .container {
            display: flex;
            height: 100%;
        }
        .sidebar {
            width: 200px;
            background-color: #333;
            color: white;
            padding: 30px 20px;
            border-right: 1px solid #e0e0e0;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
        }
        .main-content {
            flex-grow: 1;
            padding: 30px;
            overflow-y: auto;
        
        }
        h1 {
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 8px 0;
        }
        h2 {
            font-size: 20px;
            font-weight: 500;
            margin: 0 0 24px 0;
            padding-bottom: 16px;
            border-bottom: 1px solid #e0e0e0;
        }
        h3 {
            font-size: 16px;
            font-weight: 500;
            margin: 0 0 16px 0;
        }
        p {
            color: #666;
            font-size: 14px;
            margin: 0 0 24px 0;
        }
        .menu-item {
            padding: 8px 12px;
            margin-bottom: 4px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            border-radius: 4px;
        }
        .menu-item.active {
            background-color: #444;
            font-weight: 500;
        }
        .menu-item svg {
            margin-right: 10px;
        }
        .menu-item a {
            color: white;
            text-decoration: none;
        }
        .profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
        }
        .profile-info {
            display: flex;
            align-items: center;
        }
        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .profile-picture-container {
            position: relative;
            width: 150px;
            height: 150px;
            overflow: hidden;
            border-radius: 50%;
            background-color: #f0f0f0; /* Light gray background */
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .profile-picture {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .default-icon {
            font-size: 80px;
            color: #cccccc;
        }
        .edit-picture {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            text-align: center;
            padding: 5px;
            cursor: pointer;
        }
        #file-input {
            display: none;
        }
        
        .edit-button {
            padding: 6px 12px;
            background-color: white;
            border: 1px solid #d0d0d0;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            color: #333;
        }
        .info-section {
            margin-bottom: 32px;
        }
        .info-item {
            margin-bottom: 8px;
            font-size: 14px;
        }
        .add-button {
            color: #81589a;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            margin-top: 12px;
        }
        .add-button::before {
            content: '+';
            margin-right: 4px;
            font-size: 18px;
        }
        .tag {
            background-color: #f1f3f4;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: normal;
            color: #5f6368;
            margin-left: 8px;
        }
        .connected-account {
            display: flex;
            align-items: center;
        }
        .connected-account img {
            margin-right: 8px;
        }
        .connected-account .email {
            color: #5f6368;
            margin-left: 4px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 200;
            font-size: 14px;
        }
       
        .save-button {
            background-color: #81589a;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .form-group input,
        .form-group select { /* Added select here */
            width: 90%;
            padding: 8px;
            border: 1px solid #d0d0d0;
            border-radius: 4px;
            font-size: 14px;
            background-color: white; /* Ensure background is white */
            color: #333; /* Ensure text color is consistent */
            appearance: auto; /* Remove default appearance */
            -webkit-appearance: auto; /* For older versions of Safari */
            -moz-appearance: auto; /* For Firefox */
            }
            .user-info {
            background-color: white;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .info-item {
            margin-bottom: 8px;
            font-size: 14px;
            line-height: 1.5;
        }

        .info-item strong {
            display: inline-block;
            width: 140px;
            font-weight: 600;
        }
        
    </style>
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
                <a href="residence page in user managment .html">
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
                        <h1>User Name</h1>
                    </div>
                </div>
                <button class="edit-button">Edit profile</button>
            </div>

            <div class="info-section">
                <h2><strong>Current User Information</strong></h2>
                <div class="user-info">
                    <div class="info-item">
                        <strong>Role:</strong> Student
                    </div>
                    <div class="info-item">
                        <strong>Residence:</strong> Chris Hani
                    </div>
                    <div class="info-item">
                        <strong>Email:</strong> 123@gmail.com
                    </div>
                    <div class="info-item">
                        <strong>Contact Number:</strong> 123903922
                    </div>
                </div>
        
            <div class="info-section">
                <h2><strong>Personal Information</strong></h2>
                <form>
                    <div class="form-group">
                        <label for="fullName">Full Name</label>
                        <input type="text" id="fullName" name="fullName">
                    </div>
                    <div class="form-group">
                        <label for="birthDate">Date of Birth</label>
                        <input type="date" id="birthDate" name="birthDate">
                    </div>
                    <div class="form-group">
                        <label for="Email address">Email <address></address></label>
                        <input type="email" id="email" name="email">
                    </div>
                    <button type="submit" class="save-button">Save Changes</button>
                </form>
            </div>
            <div class="info-section">
                <h2><strong>Residence Details</strong></h2>
                <form>
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
                </form>
            </div>
            <div class="info-section">
                <h2><strong>Change Password</strong></h2>
                <form>
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <input type="password" id="currentPassword" name="currentPassword">
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" id="newPassword" name="newPassword">
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm New Password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword">
                    </div>
                    <button type="submit" class="save-button">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>