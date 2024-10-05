<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); 
    include '2-TicketCreation.php';
    include '3-FloatingMenu.html';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Ticket</title>
    <link rel="icon" href="../Images\General\favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../7-TicketCreation\7-CSS\1-TicketCreation.css">
    <script src="4-TicketCreation.js"></script>
</head>
<body>
    <header>
    <div class="logo">
            <a href="../1-GeneralPages/1-Home.php">
                <img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="RU Maintained Logo">
            </a>
            </div>       
        <nav>
            <a href="../1-GeneralPages\1-Home.php">Home</a>
            <a href="../1-GeneralPages\3-AboutUs.php">About Us</a>
            <?php
                    if (isset($_SESSION['userID'])) {
                        echo '<div class="dropdown">
                                <a href=""><strong>Services ▾</strong></a>
                                <div class="dropdown-content">
                                    <a href="../7-TicketCreation/1-TicketCreation.php">Create Ticket</a>
                                    <a href="../1-GeneralPages/7-RedirectProfile.php">Profile</a>
                                    <a href="../1-GeneralPages/6-RedirectDashboard.php">Dashboard</a></div></div>';

                    }
                ?>
            <a href="../1-GeneralPages\2-ContactUs.php">Contact Us</a>
        </nav>
        <?php 
            if (isset($_SESSION['userID'])) {
                echo '<div class="profile-info">
                        <a href= "../1-GeneralPages/7-RedirectProfile.php">
                            <i class="fas fa-user default-icon" id="default-icon"></i></a>
                        <span class="profile-name">
                            ' . htmlspecialchars('Welcome '. $_SESSION['Firstname'] . '!') . '
                        </span>
                    </div>';
            } else {
                echo '<div class="auth-buttons">
                        <a href="../5-UserSignInandRegistration/6-SignInPage.php" class="cta-button">Sign in</a>
                        <a href="../5-UserSignInandRegistration/5-RegistrationStep1.html" class="cta-button login-button">Register</a>
                    </div>';
            }
            ?>
    </header>

    <main>
        <div class="container">
            <div class="ticket-form-container">
                <h2>Create Your Maintenance Ticket</h2>
                <div class="progress-container">
                    <div class="progress-bar">
                        <div class="progress" id="progress"></div>
                        <div class="step-wrapper">
                            <div class="step active">1</div>
                            <div class="step-label">Create Ticket</div>
                        </div>
                        <div class="step-wrapper">
                            <div class="step ">2</div>
                            <div class="step-label">House Warden Approval</div>
                        </div>
                        <div class="step-wrapper">
                            <div class="step ">3</div>
                            <div class="step-label">Hall Secretary Approval</div>
                        </div>
                        <div class="step-wrapper">
                            <div class="step ">4</div>
                            <div class="step-label">Maintenance Staff</div>
                        </div>
                    </div>
                </div>
                    
                <form action="1-TicketCreation.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="status" name="status" value="Open">

                    <div class="section-title">Ticket Details</div>
                    <div class="form-group">
                        <label for="description" class="required">Description of Fault:</label>
                        <div class="input-container">
                            <input type="text" id="description" name="description" placeholder="Leaking tap" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="category" class="required">Category of Fault:</label>
                        <div class="input-container">
                            <select id="category" name="category" required>
                                <option value="">Select category</option>
                                <option value="CAPL">Appliance Repair</option> <!-- Faults related to home or office appliance repair -->
                                <option value="CCRP">Carpentry Faults</option> <!-- Faults related to carpentry work, including repair and installation -->
                                <option value="CELE">Electrical Maintenance Faults</option> <!-- Issues related to electrical systems and maintenance -->
                                <option value="CFRS">Fire Safety Maintenance</option> <!-- Fire safety system maintenance including alarms and sprinklers -->
                                <option value="CGNM">General Maintenance</option> <!-- General upkeep and maintenance issues -->
                                <option value="CGRL">Groundskeeping and Landscaping</option> <!-- Issues with outdoor areas, landscaping, and grounds maintenance -->
                                <option value="CHVC">HVAC Faults</option> <!-- Heating, ventilation, and air conditioning maintenance issues -->
                                <option value="CLFT">Lift/Elevator Maintenance</option> <!-- Faults with lift or elevator systems -->
                                <option value="CMSN">Masonry Faults</option> <!-- Masonry related issues such as structural damage and repairs -->
                                <option value="CPLM">Plumbing Faults</option> <!-- Plumbing related faults such as leaks, blockages, and other issues -->
                                <option value="CRFW">Roofing and Waterproofing</option> <!-- Issues with roofing, water leakage, and waterproofing -->
                                <option value="CSEC">Security Systems Maintenance</option> <!-- Maintenance of security systems such as alarms and cameras -->
                                <option value="CWST">Waste Management</option> <!-- Issues related to waste disposal and management -->
                            </select>
                        </div>
                    </div>

                    
                    <div class="form-group">
                        <label for="severity" class="required">Severity of Fault:</label>
                        <div class="input-container">
                            <select id="severity" name="severity" required>
                                <option value="">Select severity</option>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="separator"></div>
                    
                    <div class="section-title">Additional Information</div>
                    
                    <!-- <div class="form-group">
                        <label for="affected-items">Affected Items:</label>
                        <div class="input-container">
                            <input type="text" id="affected-items" name="affected-items" placeholder="e.g. damaged floor and sink">
                        </div>
                    </div> -->
                    
                    <div class="form-group">
                        <label for="comments">Comments:</label>
                        <div class="input-container">
                            <textarea id="comments" name="comments" placeholder="Please enter additional comments on issue"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="photo">Upload Photo:</label>
                    
                        <!-- Container for the custom upload area -->
                        <div class="input-container">
                            <!-- Custom upload area with the file input hidden -->
                            <div class="upload-area" id="upload-area" onclick="document.getElementById('photo').click();">
                                <i class="fas fa-image"></i>
                                <p>Click or drag Photo to this area to upload</p>

                                <!-- Actual file input, hidden from view -->
                                <input type="file" name="photo" id="photo" style="display: none;" accept="image/*" onchange="handleFileSelect(event)">                            
                                <div id="preview-area"></div>

                            </div>

                        </div>
                        <script>
                            function handleFileSelect(event) {
                                var fileInput = event.target;
                                var previewArea = document.getElementById('preview-area');
                                var uploadArea = document.getElementById('upload-area');

                                uploadArea.querySelector('i').style.display = 'none';
                                uploadArea.querySelector('p').style.display = 'none';

                                if (fileInput.files && fileInput.files[0]) {
                                    var reader = new FileReader();
                                    reader.onload = function(e) {
                                        previewArea.innerHTML = '<img src="' + e.target.result + '" alt="Uploaded Photo" style="max-width: 100%;">';
                                    };
                                    reader.readAsDataURL(fileInput.files[0]);
                                }
                            }
                        </script>
                    </div>
                    <div class="button-group">
                        <input type="submit" name="submit" value="Create Ticket">
                        <!-- <input type="submit" name="SAVE" value="Save Ticket"> -->
                     
                    </div>
                </form>
            </div>
        </div>
    </main>


   
</body>
</html>