<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - RUMaintained</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* General styles for the entire page */
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: white;
        }

        /* New styles for the image container */
        .top-image-container {
            width: 100%;
            height: 300px;
            overflow: hidden;
            position: relative;
            opacity: 100%;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .top-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        /* Header styles */
        header {
            background-color: white;
            padding: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo img {
            height: 60px;
            width: auto;
        }

        nav {
            display: flex;
            justify-content: center;
            flex-grow: 1;
            align-items: center;
        }

        nav a, .dropdown > a {
            margin: 0 1rem;
            text-decoration: none;
            color: #333;
            font-size: 20px;
            padding: 10px 15px;
            border-radius: 5px;
            transition: color 0.3s ease;
        }

        nav a:hover, .dropdown > a:hover {
            color: #81589a;
        }
       
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 300px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            top: 100%;
            left: 0;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-weight: normal;
            font-size: 15px;
        }

        .dropdown-content a:hover {
            background-color: lightgray;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .cta-button {
            background-color: #81589a;
            color: white;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }

        .login-button {
            background-color: rgb(233, 233, 233);
            color: #81589a;
        }

        /* Main content styles */
        main {
            background-color: white;
            padding: 0;
        }

        .container {
            width: 100%;
            height: auto;
            padding: 0.0005rem 0;
            background-color: #81589a; 
            color: white;
            margin-top: 0;
            padding-top: 0.05px;
            padding-bottom: 0.05px;
        }

        h1 {
            font-size: 2.5rem;
            color: white;
            text-align: center;
            margin-top: 1%;
        }

        .subtitle {
            text-align: center;
            color: whitesmoke;
            margin-bottom: 2rem;
        }

        /* Contact form styles */
        .contact-form {
            width: 80%;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: auto;
            padding: 2rem 0;
        }

        .form-group {
            width: 48%;
            margin-bottom: 1rem;
        }

        .form-group.full-width {
            width: 100%;
        }

        input, textarea, select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        textarea {
            height: 150px;
            background-color: #f9f9f9;
        }

        .submit-button {
            background-color: #81589a;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        /* Map and contact info styles */
        .map-container {
            width: 80%;
            margin: 4rem auto 0;
        }

        .map-and-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .map {
            width: 60%;
            height: 300px;
            overflow: hidden;
            border-radius: 8px;
            background-color: #f0f0f0;
        }

        .map img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        } 
        
        .map-container {
            width: 80%;
            margin: 4rem auto 0;
        }
        
        .map-and-info {
            display: flex; 
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .map {
            width: 60%;
            height: 300px; 
            overflow: hidden;
            border-radius: 8px;
            background-color: #f0f0f0;
            position: relative;
            }
            
        .map iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }
        
        .contact-info {
            width: 35%;
        }

        .contact-info {
            width: 35%;
        }

        .social-media {
            padding: 2rem 0;
            text-align: center;
        }

        .social-media h2 {
            color: #333;
            margin-bottom: 1rem;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
        }

        .social-icons a {
            color: #333;
            font-size: 1.5rem;
            transition: color 0.3s ease;
        }

        .social-icons a:hover {
            color: #81589a;
        }

        footer {
            background-color: #81589a;
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: 2rem;
        }

        footer p {
            margin: 0.5rem 0;
        }
    </style>
</head>
<body>
<header>
<div class="logo"><img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="RUMaintained Logo"></div>
        <nav>
            <a href="1-Home.html">Home</a>
            <a href="3-AboutUs.html">About Us</a>
            <div class="dropdown">
                <a href="#">Services ▾</a>
                <div class="dropdown-content">
                    <a href="../7-TicketCreation/1-TicketCreationPage.html">Create Ticket</a>
                    <a href="#">Student Dashboard</a>
                    <a href="#">House Warden Dashboard</a>
                    <a href="#">Hall Secretary Dashboard</a>
                    <a href="#">Maintenance Staff Dashboard</a>
                </div>
            </div>
            <a href="2-ContactUs.html"><strong>Contact Us</strong></a>
        </nav>
        <div class="auth-buttons">
            <a href="../5-UserSignInandRegistration/5-RegistrationStep1.html" class="cta-button login-button">Register</a>
            <a href="../5-UserSignInandRegistration/6-SignInPage.html" class="cta-button">Sign in</a>
        </div>
</header>

<main>
    <div class="top-image-container">
        <img src="../Images/General/contact-banner.jpg" alt="Campus">
    </div> 
    <div class="container">
        <h1>Contact Us</h1>
        <p class="subtitle">Get in touch with the RUMaintained team for any queries, support, or feedback.</p>
    </div>
    <form class="contact-form" action="submit_contact.php" method="POST">
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
        <button type="submit" name="submit" class="submit-button">Send Message</button>
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