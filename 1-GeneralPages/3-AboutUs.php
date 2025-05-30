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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../1-GeneralPages/CSS/3-AboutUs.css">
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
                <img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="RUMaintained Logo">
            </a>
        </div>
        <nav>
            <a href="1-Home.php">Home</a>
            <a href="3-AboutUs.php"><strong>About Us</strong></a>
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
            <a href="2-ContactUs.php">Contact Us</a>
        </nav>
        <?php if (isset($_SESSION['userID'])): ?>
                <div class="profile-info">
                    <a href="7-RedirectProfile.php">
                        <?php if (isset($_SESSION['ProfilePath']) && !empty($_SESSION['ProfilePath'])): ?>
                            <img src="<?php echo htmlspecialchars($_SESSION['ProfilePath']); ?>" alt="Profile Photo" class="profile-photo" style="width: 20px; height: 20px; border-radius: 50%;">
                        <?php else: ?>
                            <i class="fas fa-user default-icon" id="default-icon"></i>
                        <?php endif; ?>
                    </a>
                    <span class="profile-name">
                        <a style="text-decoration:none; color:black;" href="7-RedirectProfile.php">
                            
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

    <img src="../Images/General/Image.jpeg" alt="Campus view" class="hero" />

    <section class="side-content">
        <h1>About Us</h1>

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
                    <img src="../Images\General\Daniel.jpg" alt="Project Manager">
                    <p><strong>Project Manager</strong></p>
                    <p>Daniel Spies</p>
                </div>
                <div class="team-member">
                    <img src="../Images\General\Kate.jpg" alt="System Developer">
                    <p><strong>System Developer</strong></p>
                    <p>Kate Jackson-Moss</p>
                </div>
                <div class="team-member">
                    <img src="../Images\General\Nelly.JPG" alt="System Analyst">
                    <p><strong>System Developer</strong></p>
                    <p>Naledi Mabusela</p>
                </div>
                <div class="team-member">
                    <img src="../Images\General\Thule.jpg" alt="System Analyst">
                    <p><strong>System Developer</strong></p>
                    <p>Thulebona Tsele </p>
                </div>
                <div class="team-member">
                    <img src="../Images\General\Yolo.jpg" alt="System Analyst">
                    <p><strong>System Developer</strong></p>
                    <p>Banoyolo Sicwebu</p>
                </div>
            </div>
            
        </section>
        <div>
        <?php
            include 'Chatbot\chatbotOllama.php';
        ?>
    </div>
    </div>

    <footer>
        <p>RUMaintained - A Residence Maintenance System</p>
        <p>Developed by Algorithmix</p>
        <p>&copy; 2024 Algorithmix. All rights reserved.</p>
    </footer>

</body>
</html>