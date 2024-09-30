<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); 
    // include '3-FloatingMenu.html';
    include '8-TicketStatusInformation.php';
    include '9-UpdateTicket.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Ticket</title>
    <link rel="icon" href="../Images\General\favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../7-TicketCreation/7-CSS/1-TicketCreation.css">
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
                <h2>Maintenance Ticket</h2>

                <div class="progress-container">
                    <div class="progress-bar">
                        <div class="progress" id="progress"></div>
                        <div class="step-wrapper">
                            <div class="step ">1</div>
                            <div class="step-label">Create Ticket</div>
                        </div>
                        <div class="step-wrapper">
                            <div class="step <?php echo ($Status == 'Open') ? 'active' : ' '; ?>">2</div>
                            <div class="step-label">House Warden Approval</div>
                        </div>
                        <div class="step-wrapper">
                            <div class="step <?php echo ($Status == 'Confirmed') ? 'active' : ' '; ?>">3</div>
                            <div class="step-label">Hall Secretary Approval</div>
                        </div>
                        <div class="step-wrapper">
                            <div class="step <?php echo ($Status == 'Requisitioned') ? 'active' : ' '; ?>">4</div>
                            <div class="step-label">Maintenance Staff</div>
                        </div>
                    </div>
                </div>
                    
                <form action="7-TicketStatus.php" method="post" enctype="multipart/form-data">
                    <!-- <input type="hidden" id="status" name="status" value="Open"> -->
                    <?php
                        if (isset($_GET['ticketID'])) {
                            $ticketID = $_GET['ticketID'];
                            echo '<input type="hidden" name="ticketID" value="' . htmlspecialchars($ticketID) . '">';
                        } else {
                            echo 'No ticket ID provided.';
                        }
                    ?>
                    <div class="section-title">Ticket Details</div>
                    <div class="form-group">
                        <label for="description">Description of Fault:</label>
                        <div class="input-container">
                            <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($Description); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">

                        <label for="category" class="">Category of Fault:</label>
                        <div class="input-container">
                            <select id="category" name="category" disabled>
                                <option value=""><?php echo $categoryName ?></option>
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
                        <label for="severity">Severity of Fault:</label>
                        <div class="input-container">
                            <select id="severity" name="severity" disabled>
                                <option value=""><?php echo $Severity ?></option>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                    </div>
                    <div class = "form-group">
                        <label for="residence">Residence:</label>
                        <div class="input-container">
                            <input type="text" id="residence" name="residence" value=<?php echo $ResidenceName?> readonly>
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
                    
                    <?php if (!empty($comments)): ?>
                        <div class="form-group">
                            <label for="existing-comments">Existing Comments:</label>
                            <div class="input-container">
                                <textarea id="existing-comments" name="existing-comments" readonly><?php
                                    foreach ($comments as $comment) {
                                        echo htmlspecialchars($comment) . "\n";
                                    }
                                ?></textarea>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="new-comment">Add New Comment:</label>
                        <div class="input-container">
                            <textarea id="new-comment" name="new-comment" placeholder="Please enter additional comments on issue"></textarea>
                        </div>
                    </div>
                    
                    <!-- <div class="form-group">
                        <label for="photo">Upload Photo:</label>
                    
                        <div class="input-container">
                            Custom upload area with the file input hidden
                            <div class="upload-area" id="upload-area">
                                <i class="fas fa-image"></i>
                                <p>Click or drag Photo to this area to upload</p>
                    
                                <input type="file" name="photo" id="photo" style="display: none;">
                            </div>
                        </div>
                    </div> -->
                    <div class="form-group">
                    <?php
                        $showButtons = !($_SESSION['role'] === 'S');                    
                        if ($showButtons): ?>
                            <?php if (!($_SESSION['role'] === 'MS' || $Status === 'Resolved')): ?>
                                <?php if (($Status === 'Open' && $_SESSION['role'] === 'HW') || ($Status === 'Confirmed' && $_SESSION['role'] === 'HS')): ?>
                                    <button type="submit" name="submit-reject" class="btn-primary" onclick="return confirm('Are you sure you want to reject this ticket?');">Reject</button>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if (($Status === 'Open' && $_SESSION['role'] === 'HW') || 
                                    ($Status === 'Confirmed' && $_SESSION['role'] === 'HS') || 
                                    ($Status === 'Requisitioned' && $_SESSION['role'] === 'MS') || 
                                    ($Status === 'Resolved' && $_SESSION['role'] === 'HS')): ?>
                                <button type="submit" name = "submit-approve" class="btn-primary">Approve</button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </main>


   
</body>
</html>