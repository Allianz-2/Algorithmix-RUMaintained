<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUMaintained</title>
    <link rel="icon" href="../Images\General\favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../1-GeneralPages/CSS/1-Home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .home-section .profile-info {
        margin-right: 20px;
        font-size: 1.1rem
        }
    </style>
</head>
<body>
    <!-- Home Section -->
    <div class="home-section">
        <header>
            <div class="logo">
                <a href="/1-GeneralPages/1-Home.php">
                    <img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="RUMaintained Logo">
                </a>
            </div>
            <nav>
                <a href="1-Home.php"><strong>Home</strong></a>
                <a href="3-AboutUs.php">About Us</a>
                <!-- <div class="dropdown">
                    <a href="">Services ▾</a>
                    <div class="dropdown-content">
                        <a href="../7-TicketCreation/1-TicketCreationPage.html">Create Ticket</a>
                        <a href="#">Student Dashboard</a>
                        <a href="#">House Warden Dashboard</a>
                        <a href="#">Hall Secretary Dashboard</a>
                        <a href="#">Maintenance Dashboard</a>
                    </div>
                </div> -->
                <?php
                    if (isset($_SESSION['userID'])) {
                        echo '<div class="dropdown">
                                <a href="">Services ▾</a>
                                <div class="dropdown-content">
                                    <a href="../7-TicketCreation/1-TicketCreation.php">Create Ticket</a>
                                    <a href="../1-GeneralPages/7-RedirectProfile.php">Profile</a>
                                    <a href="../1-GeneralPages/6-RedirectDashboard.php">Dashboard</a></div></div>';
;

                    }
                ?>
                
                <a href="2-ContactUs.php">Contact Us</a>
            </nav>
            <?php if (isset($_SESSION['userID'])) {
                echo '<div class="profile-info">
                        <a href= "7-RedirectProfile.php">
                            <i class="fas fa-user default-icon" id="default-icon"></i></a>
                        <span class="profile-name">
                            ' . htmlspecialchars('Welcome '. $_SESSION['Firstname'] . '!') . '
                        </span>
                    </div>';
            } else {
                echo '<div class="auth-buttons">
                        <a href="../5-UserSignInandRegistration\6-SignInPage.php" class="cta-button">Sign in</a>
                        <a href="../5-UserSignInandRegistration\5-RegistrationStep1.html" class="cta-button login-button">Register</a>
                    </div>';
            }
            ?>


        </header>

        <main>
            <section class="hero">
                <img src="../Images/General/campus .jpg" alt="Campus Image">
                <div class="hero-content">
                    <h1>Residence Maintenance System</h1>
                    <p><i>Your Maintenance Made Easy</i></p>
                    <!-- <a href="/5-UserSignInandRegistration/6-SignInPage.php" class="cta-button">Sign In</a> -->
                    <?php 
                        if (!isset($_SESSION['userID'])) {
                        echo '<a href="../5-UserSignInandRegistration/6-SignInPage.php" class="cta-button">Sign In</a>';
                        }
                    ?>
                </div>
            </section>

        </main>
    </div>

    <!-- About Us Section -->
    <div class="about-section">
        <header class="about-header">
            <h1>About RUMaintained</h1>
        </header>
     
        <div class="container">
            <section class="section">
            
                <p>Welcome to RUMaintained (a Residence Maintenance System) at Rhodes University, your trusted platform for maintaining the comfort and safety of your "home away from home." Our system is designed to streamline the reporting and tracking of maintenance issues within university residences, ensuring that every student enjoys a well-maintained living environment.</p>
            </section>

            <section class="section">
                <h2>Our Mission</h2>
                <div class="section-content">
                    <div class="section-text">
                        <p>Our mission is to provide a seamless and efficient solution for managing maintenance requests within university residences. We believe that a well-maintained living space contributes significantly to the academic and personal success of students. By enabling easy communication between residents and maintenance teams, we help ensure that issues are addressed promptly and effectively.</p>
                    </div>
                    <div class="section-image">
                        <img src="../Images/General/Picture 1.png" alt="Our Mission">
                    </div>
                </div>
            </section>

            <section class="section">
                <h2>What is RUMaintained used for?</h2>
                <div class="section-content right">
                    <div class="section-text">
                        <p>Our system allows students to report maintenance faults directly from their residence. Once a fault is reported, it is visible to house wardens and hall secretaries, who can confirm the issue and request the necessary repairs. Maintenance staff are then notified to resolve the issue, ensuring that the entire process is transparent and traceable.</p>
                    </div>
                    <div class="section-image">
                        <img src="../Images/General/112.png" alt="What We Do">
                    </div>
                </div>
            </section>

            <section class="section">
                <h2>Why Choose Us?</h2>
                <div class="section-content">
                    <div class="section-text">
                        <ul>
                            <li><strong>User-Friendly:</strong> Our platform is designed with simplicity in mind, making it easy for students to report issues and track their status.</li>
                            <li><strong>Transparency:</strong> Every step of the maintenance process is documented and visible to all relevant parties, ensuring accountability.</li>
                            <li><strong>Efficiency:</strong> We bridge the gap between students, house wardens, hall secretaries, and maintenance staff, facilitating quick and effective repairs.</li>
                        </ul>
                    </div>
                    <div class="section-image">
                        <img src="../Images/General/how we do.png" alt="Why Choose Us">
                    </div>
                </div>
            </section>

            <section class="section faq-section">
                <h2>Frequently Asked Questions</h2>
                <div class="faq-item">
                    <div class="faq-question">What is this system used for?</div>
                    <div class="faq-answer">This system is designed to help students and staff report and manage maintenance issues within university residences efficiently.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">How do I report a maintenance issue?</div>
                    <div class="faq-answer">You can report an issue by logging into your account, navigating to the "Report Issue" section, and filling out the necessary details about the problem.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Can I track the status of my maintenance request?</div>
                    <div class="faq-answer">Yes, once you report an issue, you can track its status from your dashboard to see if it's been acknowledged, scheduled, or resolved.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">What type of maintenance issue can I report?</div>
                    <div class="faq-answer">You can report any issue related to the upkeep and maintenance of your residence, including plumbing, electrical, or general repairs.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">What should I do if I forget my password?</div>
                    <div class="faq-answer">Click on the "Forgot Password" link on the login page and follow the instructions to reset your password.</div>
                </div>
            </section>
        </section>
        <?php
            if (!isset($_SESSION['userID'])) {
                echo '<section class="section">
                        <h2>Get Started</h2>
                        <a href="../5-UserSignInandRegistration/5-RegistrationStep1.html" class="cta-button">Register Now</a>
                    </section>';
            }
        ?>
        <!-- <section class="section">
            <h2>Get Started</h2>
            <a href="../5-UserSignInandRegistration/5-RegistrationStep1.html" class="cta-button">Register Now</a>
        </section> -->
    </div>

    <script>
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
                question.classList.toggle('active');
            });
        });
    </script>
     <script>
       document.addEventListener('DOMContentLoaded', function() {
    var scrollToTopBtn = document.getElementById("scrollToTopBtn");

    window.onscroll = function() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            scrollToTopBtn.style.display = "flex";
            console.log("Button should be visible");
        } else {
            scrollToTopBtn.style.display = "none";
            console.log("Button should be hidden");
        }
    };

    scrollToTopBtn.onclick = function() {
        console.log("Button clicked");
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    };
});
    </script>
     <button id="scrollToTopBtn" title="Go to top">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 19V5M12 5L5 12M12 5L19 12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>
<footer>
    <p>RUMaintained - A Residence Maintenance System</p>
    <p>Developed by Algorithmix</p>
    <p>&copy; 2024 Algorithmix. All rights reserved.</p>
</footer>


</body>
</html>