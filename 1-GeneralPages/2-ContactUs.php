<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - RUMaintained</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="/1-GeneralPages/CSS/2-ContactUs.css">
</head>
<body>
    <header>
        <div class="logo">            <a href="/1-GeneralPages/1-Home.php">
            <img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="RUMaintained Logo">
            </a>
        </div>
        <nav>
            <a href="1-Home.php">Home</a>
            <a href="3-AboutUs.php">About Us</a>
            <!-- <div class="dropdown">
                <a href="#">Services ▾</a>
                <div class="dropdown-content">
                    <a href="../7-TicketCreation/1-TicketCreationPage.html">Create Ticket</a>
                    <a href="#">Student Dashboard</a>
                    <a href="#">House Warden Dashboard</a>
                    <a href="#">Hall Secretary Dashboard</a>
                    <a href="#">Maintenance Staff Dashboard</a>
                </div>
            </div> -->
            <?php
                if (isset($_SESSION['userID'])) {
                    echo '<div class="dropdown">
                            <a href="">Services ▾</a>
                            <div class="dropdown-content">
                                <a href="../7-TicketCreation/1-TicketCreationPage.html">Create Ticket</a>
                                <a href="#">Student Dashboard</a>
                                <a href="#">House Warden Dashboard</a>
                                <a href="#">Hall Secretary Dashboard</a>
                                <a href="#">Maintenance Dashboard</a>
                            </div>
                        </div>';
                }
            ?>
            <a href="2-ContactUs.php"><strong>Contact Us</strong></a>
        </nav>
        <div class="auth-buttons">
            <a href="/5-UserSignInandRegistration\5-RegistrationStep1.html" class="cta-button login-button">Register</a>
            <a href="/5-UserSignInandRegistration/6-SignInPage.php" class="cta-button">Sign in</a>
        </div>
    </header>

    <main>
        <div class="top-image-container">
            <img src="../Images/General/contact-banner.jpg" alt="Contact-banner">
        </div> 
        <div class="container">
            <h1>Contact Us</h1>
            <p class="subtitle">Get in touch with the RUMaintained team for any queries, support, or feedback.</p>
        </div>
        <form class="contact-form" action="contact.php" method="POST">
            <div class="form-group">
                <input type="text" name="name" placeholder="Your name" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email address" required>
            </div>
            <div class="form-group">
                <input type="tel" name="phone" placeholder="Phone number">
            </div>
            <div class="form-group">
                <select name="role" required>
                    <option value="">Select Role</option>
                    <option value="student">Student</option>
                    <option value="staff">Staff</option>
                    <option value="warden">House Warden</option>
                    <option value="maintenance">Maintenance Staff</option>
                </select>
            </div>
            <div class="form-group full-width">
                <textarea name="message" placeholder="How can we help?" required></textarea>
            </div>
            <button type="submit" class="submit-button" name="submit">Send Message</button>
        </form>

        <div class="map-container">
            <h2>Visit Us</h2>
            <div class="map-and-info">
                <div class="map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3367.5494344988587!2d26.519699015220997!3d-33.31134498081086!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1e7ad0bcb47f0a6b%3A0x48e9bd7f230cf551!2sRhodes%20University!5e0!3m2!1sen!2sza!4v1631234567890!5m2!1sen!2sza"  loading="lazy"></iframe>
                </div>
                <div class="contact-info">
                    <h3>Address</h3>
                    <p>Rhodes University, Drosty Rd, Grahamstown, 6139</p>
                    <h3>Email</h3>
                    <p>support@rumaintained.co.za</p>
                    <h3>Phone</h3>
                    <p>+27 (0)46 603 8111</p>
                </div>
            </div>
        </div>
        
        <section class="social-media">
            <h2>Connect With Us</h2>
            <div class="social-icons">
                <a href="https://www.facebook.com/RhodesUniversity/" title="Facebook"><i class="fab fa-facebook"></i></a>
                <a href="https://twitter.com/Rhodes_Uni" title="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="https://www.instagram.com/rhodes-university/" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://www.linkedin.com/school/rhodes-university/" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                <a href="https://www.youtube.com" title="YouTube"><i class="fab fa-youtube"></i></a>
            </div>
        </section>
    </main>

    <footer>
        <p>RUMaintained - A Residence Maintenance System</p>
        <p>Developed by Algorithmix</p>
        <p>&copy; 2024 Algorithmix. All rights reserved.</p>
    </footer>
</body>
</html>