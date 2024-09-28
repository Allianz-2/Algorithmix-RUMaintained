<?php
    include '2-TicketCreationPagefinal.php';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RU Maintained - Create Ticket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="1-TicketCreation.css">
</head>
<body>
    <header>
        <div class="logo"><img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="RUMaintained Logo"></div>
        <nav>
            <a href="\\ict.ru.ac.za\DFS\UGHomes\g21s2894\My Documents\GitHub\Algorithmix-RUMaintained\1-GeneralPages\1-Home.html">Home</a>
            <a href="\\ict.ru.ac.za\DFS\UGHomes\g21s2894\My Documents\GitHub\Algorithmix-RUMaintained\1-GeneralPages\3-AboutUs.html">About Us</a>
            <div class="dropdown">
                <a href="#"><strong>Services ▾</strong></a>
                <div class="dropdown-content">
                    <a href="../7-TicketCreation/1-TicketCreationPage.html">Create Ticket</a>
                    <a href="#">Student Dashboard</a>
                    <a href="#">House Warden Dashboard</a>
                    <a href="#">Hall Secretary Dashboard</a>
                    <a href="#">Maintenance Staff Dashboard</a>
                </div>
            </div>
            <a href="\\ict.ru.ac.za\DFS\UGHomes\g21s2894\My Documents\GitHub\Algorithmix-RUMaintained\1-GeneralPages\2-ContactUs.html">Contact Us</a>
        </nav>
        <div class="auth-buttons">
            <a href="../5-UserSignInandRegistration/5-RegistrationStep1.html" class="cta-button login-button">Register</a>
            <a href="../5-UserSignInandRegistration/6-SignInPage.html" class="cta-button">Sign in</a>
        </div>
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
                            <div class="step">2</div>
                            <div class="step-label">House Warden Approval</div>
                        </div>
                        <div class="step-wrapper">
                            <div class="step">3</div>
                            <div class="step-label">Hall Secretary Approval</div>
                        </div>
                    </div>
                    
                <form action="1-TicketCreationPagefinal.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="status" name="status" value="Open">

                    <div class="section-title">Ticket Details</div>
                    <div class="form-group">
                        <label for="description" class="required">Description of Fault:</label>
                        <div class="input-container">
                            <input type="text" id="description" name="description" placeholder="e.g Broken tap faucet" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="category" class="required">Category of Fault:</label>
                        <div class="input-container">
                            <select id="category" name="category" required>
                                <option value="CE049">Select category</option>
                                <option value="CE049">Plumbing</option>
                                <option value="CE049">Electrical</option>
                                <option value="CE049">Exterior</option>
                                <option value="CE049">Bedroom</option>
                                <option value="CE049">Bathroom</option>
                                <option value="CE049">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="severity" class="required">Severity of Fault:</label>
                        <div class="input-container">
                            <select id="severity" name="severity" required>
                                <option value="">Select severity</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- <div class="form-group">
                        <label for="location" class="required">Specific Location of Fault:</label>
                        <div class="input-container">
                            <select id="location" name="location" required>
                                <option value="">Select location</option>
                                <option value="bathroom">Bathroom</option>
                                <option value="kitchen">Kitchen</option>
                                <option value="bedroom">Bedroom</option>
                                <option value="common-area">Common Area</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div> -->
                    
                    <!-- <div class="form-group">
                        <label for="nature" class="required">Nature of Fault:</label>
                        <div class="input-container radio-group">
                            <label><input type="radio" name="nature" value="new"> New Fault</label>
                            <label><input type="radio" name="nature" value="recurring"> Recurring Fault</label>
                        </div>
                    </div> -->
                    
                    <div class="separator"></div>
                    
                    <div class="section-title">Additional Information</div>
                    
                    <div class="form-group">
                        <label for="affected-items">Affected Items:</label>
                        <div class="input-container">
                            <input type="text" id="affected-items" name="affected-items" placeholder="e.g. damaged floor and sink">
                        </div>
                    </div>
                    
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
                            <div class="upload-area" id="upload-area">
                                <i class="fas fa-image"></i>
                                <p>Click or drag Photo to this area to upload</p>
                    
                                <!-- Actual file input, hidden from view -->
                                <input type="file" name="photo" id="photo" style="display: none;">
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        // JavaScript to trigger file input click when the custom upload area is clicked
                        const uploadArea = document.getElementById('upload-area');
                        const fileInput = document.getElementById('photo');
                    
                        uploadArea.addEventListener('click', function() {
                            fileInput.click();
                        });
                    
                        // Optional: Show the selected file name
                        fileInput.addEventListener('change', function() {
                            if (fileInput.files.length > 0) {
                                uploadArea.querySelector('p').textContent = fileInput.files[0].name;
                            }
                        });
                    </script>
                    
                    
                    <div class="button-group">
                        <input type="submit" name="submit" value="Create Ticket">
                        <!-- <input type="submit" name="SAVE" value="Save Ticket"> -->
                     
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Floating Menu -->
    <div class="floating-menu" id="menu-btn">
        <i class="fa fa-question-circle"></i>
    </div>

    <!-- Popup Menu -->
    <div class="popup-menu" id="popup-menu">
        <ul>
            <li><a href="#" onclick="showSection('faq')">Frequently Asked Questions</a></li>
            <li><a href="#" onclick="showSection('help')">Help</a></li>
            <li><a href="#" onclick="showSection('doc')">Documentation</a></li>
        </ul>
        <div id="faq" class="popup-menu-content">
            <h3>Frequently Asked Questions</h3>
            <p>Here you can find answers to the most commonly asked questions.</p>
            <ul>
                <li>What is this system?</li>
                <li>How do I create a ticket?</li>
                <li>How do I track my ticket?</li>
            </ul>
        </div>
        <div id="help" class="popup-menu-content">
            <h3>Help</h3>
            <p>Quick fixes to common issues:</p>
            <ul>
                <li>Unable to change your reservation: This is pre-selected from once you register.</li>
                <li>How to upload a photo: Click the upload area or drag the photo into the box.</li>
                <li>How to edit ticket details: Contact support if you need to update your ticket after submission.</li>
            </ul>
        </div>
        <div id="doc" class="popup-menu-content">
            <h3>Documentation</h3>
            <p>Here you can find detailed documentation on how to use the system.</p>
            <ul>
                <li><a href="#">User Guide</a></li>
                <li><a href="#">Admin Guide</a></li>
                <li><a href="#">System Overview</a></li>
            </ul>
        </div>
    </div>
    <script>
        const progress = document.getElementById('progress');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const steps = document.querySelectorAll('.step');

        let currentStep = 1;

        nextBtn.addEventListener('click', () => {
            if (currentStep < steps.length) {
                currentStep++;
                updateProgress();
            }
        });

        prevBtn.addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                updateProgress();
            }
        });

        function updateProgress() {
            steps.forEach((step, index) => {
                if (index < currentStep) {
                    step.classList.add('active');
                } else {
                    step.classList.remove('active');
                }
            });

            const progressWidth = ((currentStep - 1) / (steps.length - 1)) * 100 + '%';
            progress.style.width = progressWidth;

            prevBtn.disabled = currentStep === 1;
            nextBtn.disabled = currentStep === steps.length;
        }
    </script>

    <script>
        document.getElementById('menu-btn').addEventListener('click', function() {
            const popupMenu = document.getElementById('popup-menu');
            popupMenu.style.display = popupMenu.style.display === 'none' || popupMenu.style.display === '' ? 'block' : 'none';
        });

        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.popup-menu-content').forEach(el => el.classList.remove('active'));
            // Show the selected section
            document.getElementById(sectionId).classList.add('active');
        }
    </script>
</body>
</html>