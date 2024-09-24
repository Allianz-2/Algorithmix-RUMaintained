<?php
// Initialize variables for form fields
$name = $email = $subject = $role = $message = "";
$error = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    }

    // If no errors, proceed with sending email
    if (empty($error)) {
        $to = "support@rumaintained.co.za"; // Replace with your email address
        $email_subject = "New contact form submission: $subject";
        $email_body = "You have received a new message from the contact form.\n\n".
            "Name: $name\n".
            "Email: $email\n".
            "Role: $role\n".
            "Subject: $subject\n".
            "Message:\n$message";
        $headers = "From: $email\n";
        $headers .= "Reply-To: $email";

        // Send email
        if (mail($to, $email_subject, $email_body, $headers)) {
            $success_message = "Thank you for contacting us. We will get back to you soon!";
            // Clear form fields after successful submission
            $name = $email = $subject = $role = $message = "";
        } else {
            $error = "Oops! Something went wrong and we couldn't send your message.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - RUMaintained</title>
    <link rel="stylesheet" href="ContactUs.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div>
        <div class="logo">
            <img src="/path/to/RUMaintained-logo.jpg" alt="RUMaintained Logo">
        </div>
        <nav>
            <a href="#">Home</a>
            <a href="#">About Us</a>
            <div class="dropdown">
                <a href="#">Services ▾</a>
                <div class="dropdown-content">
                    <a href="#">Create Ticket</a>
                    <a href="#">Maintenance Dashboard</a>
                    <a href="#">Admin Dashboard</a>
                    <a href="#">Student Dashboard</a>
                </div>
            </div>
            <a href="#" class="active">Contact Us</a>
        </nav>
        <div class="auth-buttons">
            <a href="#" class="cta-button login-button">Register</a>
            <a href="#" class="cta-button">Sign in</a>
        </div>
    </div>

    <main role="main">
        <div class="container">
            <h1>Contact Us</h1>
            <p>Get in touch with the RUMaintained team for any queries, support, or feedback.</p>

            <?php
            if (!empty($error)) {
                echo "<p class='error'>$error</p>";
            }
            if (!empty($success_message)) {
                echo "<p class='success'>$success_message</p>";
            }
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <input type="text" id="name" name="name" required placeholder="Your Name" value="<?php echo $name; ?>">
                </div>
                <div class="form-group">
                    <input type="email" id="email" name="email" required placeholder="Your Email" value="<?php echo $email; ?>">
                </div>
                <div class="form-group">
                    <input type="text" id="subject" name="subject" required placeholder="Subject" value="<?php echo $subject; ?>">
                </div>
                <div class="form-group">
                    <select id="role" name="role" required>
                        <option value="" disabled selected>Select your role</option>
                        <option value="Student" <?php if ($role == "Student") echo "selected"; ?>>Student</option>
                        <option value="Staff" <?php if ($role == "Staff") echo "selected"; ?>>Staff</option>
                        <option value="House Warden" <?php if ($role == "House Warden") echo "selected"; ?>>House Warden</option>
                        <option value="Maintenance Staff" <?php if ($role == "Maintenance Staff") echo "selected"; ?>>Maintenance Staff</option>
                    </select>
                </div>
                <div class="form-group full-width">
                    <textarea id="message" name="message" required placeholder="Your Message"><?php echo $message; ?></textarea>
                </div>
                <button type="submit" class="cta-button">Send Message</button>
            </form>

            <div class="map-container">
                <h2>Visit Us</h2>
                <div class="map-and-info">
                    <div class="map">
                        <img src="/path/to/map-image.jpg" alt="map">
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

            <div class="section social-media">
                <h2>Connect With Us</h2>
                <div class="social-icons">
                    <a href="#" title="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                    <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>RUMaintained - A Residence Maintenance System</p>
        <p>Developed by Algorithmix</p>
        <p>&copy; 2024 Algorithmix. All rights reserved.</p>
    </footer>

    <script src="scripts.js"></script>
</body>
</html>