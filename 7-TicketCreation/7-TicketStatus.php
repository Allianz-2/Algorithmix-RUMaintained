<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); 
    // include '3-FloatingMenu.html';
    include '8-TicketStatusInformation.php';
    include '9-UpdateTicket.php';

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
    <title>Create Ticket</title>
    <link rel="icon" href="../Images\General\favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../7-TicketCreation/7-CSS/1-TicketCreation.css">
    <script src="4-TicketCreation.js"></script>
    <style>
                .home-section .profile-info {
            margin-right: 20px;
            font-size: 1.1rem
        }

        .profile-info {
            display: flex;
            align-items: center;
        }

        .profile-photo {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 10px;
            margin-top: 5px;
        }

        .default-icon {
            font-size: 20px;
            margin-right: 10px;
        }

        .profile-name {
            font-size: 16px;
        }
    </style>    
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
                                <a href="#">Services ▾</a>
                                <div class="dropdown-content">';
                        if (isset($_SESSION['role']) && ($_SESSION['role'] == 'S' || $_SESSION['role'] == 'HW')) {
                            echo '<a href="../7-TicketCreation/1-TicketCreation.php">Create Ticket</a>';
                        }
                        echo '<a href="../1-GeneralPages/7-RedirectProfile.php">Profile</a>
                            <a href="../1-GeneralPages/6-RedirectDashboard.php">Dashboard</a>
                            </div>
                            </div>';
                    }
                ?>
            <a href="../1-GeneralPages\2-ContactUs.php">Contact Us</a>
        </nav>
        <?php if (isset($_SESSION['userID'])): ?>
                <div class="profile-info">
                    <a href="../1-GeneralPages/7-RedirectProfile.php">
                        <?php if (isset($_SESSION['ProfilePath']) && !empty($_SESSION['ProfilePath'])): ?>
                            <img src="<?php echo htmlspecialchars($_SESSION['ProfilePath']); ?>" alt="Profile Photo" class="profile-photo" style="width: 20px; height: 20px; border-radius: 50%;">
                        <?php else: ?>
                            <i class="fas fa-user default-icon" id="default-icon"></i>
                        <?php endif; ?>
                    </a>
                    <span class="profile-name">
                        <a style="text-decoration:none; color:black;" href="../1-Generalpages/7-RedirectProfile.php">
                            
                        <?php echo 'Welcome ' . htmlspecialchars($_SESSION['Firstname']) . '!'; ?>
                        </a>
                    </span>
                </div>
            <?php else: ?>
                <div class="auth-buttons">
                    <a href="../5-UserSignInandRegistration/6-SignInPage.php" class="cta-button">Sign in</a>
                    <a href="../5-UserSignInandRegistration/5-RegistrationStep1.html" class="cta-button login-button">Register</a>
                </div>
            <?php endif; ?>
    </header>

    <main>
        <div class="container">
            <div class="ticket-form-container">
                <h2>Maintenance Ticket</h2>

                <?php if ($Status !== 'Closed'): ?>
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress" id="progress"></div>
                            <div class="step-wrapper">
                                <div class="step">1</div>
                                <div class="step-label">Create Ticket</div>
                            </div>
                            <div class="step-wrapper">
                                <div class="step <?php echo ($Status == 'Open') ? 'active' : ''; ?>">2</div>
                                <div class="step-label">House Warden Approval</div>
                            </div>
                            <div class="step-wrapper">
                                <div class="step <?php echo ($Status == 'Confirmed') ? 'active' : ''; ?>">3</div>
                                <div class="step-label">Hall Secretary Approval</div>
                            </div>
                            <div class="step-wrapper">
                                <div class="step <?php echo ($Status == 'Requisitioned') ? 'active' : ''; ?>">4</div>
                                <div class="step-label">Maintenance Staff</div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                    
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
                            <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($Description); ?>" readonly style="color: purple;">
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
                            <select id="severity" name="severity" <?php echo (($_SESSION['role'] === 'HW' && $Status === 'Open') || ($_SESSION['role'] === 'HS' && $Status === 'Confirmed')) ? '' : 'disabled'; ?>>
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
                            <input type="text" id="residence" name="residence" value="<?php echo htmlspecialchars($ResidenceName);?>" readonly style="color: grey;">
                        </div>
                    </div>
                    
                    
                    <div class="separator"></div>




                    <div class = "section-title">Ticket Created By</div>
                    <div class="form-group">
                        <label for="created-by">Created By:</label>
                        <div class="input-container">
                            <input type="text" id="created-by" name="created-by" value="<?php echo htmlspecialchars($firstName . ' ' . $lastName . " - " . $userID); ?>" readonly style="color: grey;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="created-date">Creation Date:</label>
                        <div class="input-container">
                            <input type="text" id="created-date" name="created-date" value="<?php echo htmlspecialchars($DateCreated); ?>" readonly style="color: grey;">
                        </div>
                    </div>
                    <?php if (!is_null($DateConfirmed)) : ?>
                        <div class="form-group">
                            <label for="created-date">Confirmed Date:</label>
                            <div class="input-container">
                                <input type="text" id="created-date" name="created-date" value="<?php echo htmlspecialchars($DateConfirmed); ?>" readonly style="color: grey;">
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!is_null($DateRequisitioned)) : ?>
                        <div class="form-group">
                            <label for="created-date">Requisitioned Date:</label>
                            <div class="input-container">
                                <input type="text" id="created-date" name="created-date" value="<?php echo htmlspecialchars($DateRequisitioned); ?>" readonly style="color: grey;">
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!is_null($DateResolved)) : ?>
                        <div class="form-group">
                            <label for="created-date">Resolved Date:</label>
                            <div class="input-container">
                                <input type="text" id="created-date" name="created-date" value="<?php echo htmlspecialchars($DateResolved); ?>" readonly style="color: grey;">
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!is_null($DateClosed)) : ?>
                        <div class="form-group">
                            <label for="created-date">Closed Date:</label>
                            <div class="input-container">
                                <input type="text" id="created-date" name="created-date" value="<?php echo htmlspecialchars($DateClosed); ?>" readonly style="color: grey;">
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="separator"></div>

                    <?php if (!empty($comments) || !empty($photoPath)): ?>
                        <div class="section-title">Additional Information</div>
                        
                        <?php if (!empty($comments)): ?>
                            <div class="form-group">
                                <label for="existing-comments">Existing Comments:</label>
                                <div class="input-container">
                                    <textarea id="existing-comments" name="existing-comments" readonly style="color: purple;" rows="5" cols="50"><?php
                                        foreach ($comments as $comment) {
                                            echo htmlspecialchars($comment) . "\n";
                                        }
                                    ?></textarea>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (  $_SESSION['role'] === 'HW' && $Status === "Open" || 
                                    $_SESSION['role'] === 'MS' && $Status === "Requisitioned" || 
                                    $_SESSION['role'] === 'HS' && $Status === "Confirmed"): ?>
                            <div class="form-group">
                                <label for="new-comment">Add New Comment:</label>
                                <div class="input-container">
                                    <textarea id="new-comment" name="new-comment" placeholder="Please enter additional comments on issue"></textarea>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($photoPath)): ?>
                            <div class="form-group">
                                <label for="photo">Uploaded Photo:</label>
                                <div class="upload-area">
                                    <div class="uploaded-photo">
                                        <img src="<?php echo htmlspecialchars($photoPath); ?>" alt="Uploaded Photo" style="max-width: 100%; height: auto;">
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>



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