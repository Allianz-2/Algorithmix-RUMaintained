<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUMaintained - Campus Maintenance Made Easy</title>
    <link rel="stylesheet" href="/1-GeneralPages/CSS/3-AboutUs.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="/1-GeneralPages/1-Home.php">
                <img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="RUMaintained Logo">
            </a>
        </div>
        <nav>
            <a href="1-Home.php">Home</a>
            <a href="3-AboutUs.php"><strong>About Us</strong></a>
            <!-- <div class="dropdown">
                <span>Services ▾</span>
                <div class="dropdown-content">
                    <a href="/7-TicketCreation/1-TicketCreationPage.html">Create Ticket</a>
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
            <a href="2-ContactUs.php">Contact Us</a>
        </nav>
        <div class="auth-buttons">
            <a href="/5-UserSignInandRegistration/5-RegistrationStep1.html" class="login-button">Register</a>
            <a href="/5-UserSignInandRegistration/6-SignInPage.php" class="signup-button">Sign in</a>
        </div>
    </header>

    <img src="../Images/General/Image.jpeg" alt="Campus view" class="hero" />

    <section class="side-content">
        <h1>About Us</h1>
        <a href="#" class="cta-button">Meet our team</a>
    </section>

    <section class="main-content">
        <p>RUMaintained is a technology platform that supports maintenance reporting, tracking, and resolution across all residence halls and campus buildings. Hundreds of students and staff members across Rhodes University use RUMaintained to ensure a safe and comfortable living and learning environment.</p>
    </section>

    <div class="container">
        <section class="section">
            <h2>Who We Are</h2>
            <div class="section-content">
                <div class="section-text">
                    <p>At Algorithmix, we are pioneers in data-driven decision-making solutions. Specializing in advanced algorithms and machine learning tools, we empower businesses to optimize processes, predict trends, and make informed decisions in real time. Headquartered in Makhanda, South Africa, we are committed to transforming the way businesses interact with data, enabling them to navigate the complexities of the modern world with precision and confidence.</p>
                </div>
                <div class="section-image">
                    <img src="../Images/General/1.png" alt="algorithmix logo">
                </div>
            </div>
        </section>

        <section class="section">
            <h2>Our Founding Story</h2>
            <div class="section-content">
                <div class="section-text">
                    <p>Algorithmix was born in 2024 from the innovative minds of five Rhodes University students in their final year of an Information Systems class. What began as a simple class project quickly evolved into a vision to revolutionize the business world.</p>
                    <p>Drawn together by their shared passion for data science and technology, the team worked tirelessly to develop algorithms and machine learning models that could analyze real-time data and predict market trends. Their initial project, inspired by the challenges faced by local businesses, demonstrated immense potential.</p>
                    <p>With the guidance and support of their professors, the team decided to turn their academic project into a full-fledged startup. Thus, Algorithmix was founded, with a mission to harness the power of data to drive intelligent decision-making. Today, we stand as a leader in our field, offering innovative solutions that empower businesses across the globe.</p>
                </div>
            </div>
        </section>

        <section class="section">
            <h2>Our Team</h2>
            <div class="team-section">
                <div class="team-member">
                    <img src="../Images/General/man.svg" alt="Project Manager">
                    <p>Project Manager</p>
                    <p>Daniel Spies</p>
                </div>
                <div class="team-member">
                    <img src="../Images/General/female.svg" alt="System Developer">
                    <p>System Analyst</p>
                    <p>Kate Jackson-Moss</p>
                </div>
                <div class="team-member">
                    <img src="../Images/General/female.svg" alt="System Analyst">
                    <p>System Analyst</p>
                    <p>Naledi Mabusela</p>
                </div>
                <div class="team-member">
                    <img src="../Images/General/man.svg" alt="System Analyst">
                    <p>System Analyst</p>
                    <p>Thulebona Tsele </p>
                </div>
                <div class="team-member">
                    <img src="../Images/General/man.svg" alt="System Analyst">
                    <p>System Analyst</p>
                    <p>Banoyolo Sicwebu</p>
                </div>
            </div>
            
        </section>
    </div>

    <footer>
        <p>RUMaintained - A Residence Maintenance System</p>
        <p>Developed by Algorithmix</p>
        <p>&copy; 2024 Algorithmix. All rights reserved.</p>
    </footer>

</body>
</html>