<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); 




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Warden Dashboard</title>
    <link rel="icon" href="../Images\General\favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 0px;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            color: #333333;
            background-color: #f2f2f2;
        }

        .sidebar {
            width: var(--sidebar-width);
            background-color: #81589a;
            color: white;
            height: 100vh;
            position: fixed;
            transition: all 0.3s;
            left: 0;
    
        }

        .sidebar a {
    color: white;
    text-decoration: none;
}

        .sidebar.collapsed {
            left: calc(-1 * var(--sidebar-width));
            display: none;
        }

        .sidebar .logo {
            padding: 20px;
            text-align: right;
        }

        .sidebar .logo a {
            color: white;  /* Change user icon to white */
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar li {
            padding: 15px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .sidebar li:hover, .sidebar li.active {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar .badge {
            background-color: purple;
            color: white;
            padding: 2px 6px;
            border-radius: 50%;
            font-size: 0.8em;
            margin-left: 5px;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 20px;
            font-size: 16px;
        }

        .sidebar li i, .sidebar-footer button i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        main {
            flex-grow: 1;
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: margin-left 0.3s;
        }

        main.sidebar-collapsed {
            margin-left: 0;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background-color: white;  /* Change header to a banner style */
            color: #333333;
            padding: 10px 20px;
            /* Adjust for padding */
        }

        .hamburger-icon {
            font-size: 24px;
            cursor: pointer;
            display: inline-block;
            padding: 10px;
        }

        .filters {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-group select {
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #d1d5db;
            background-color: white;
            margin-right: 10px;

        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .charts {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-box {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            flex: 1;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: middle;
        }

        th {
            background-color: white;
        }


.logo img {
    max-height: 60px;  /* Adjust this value as needed */
    width: auto;
    object-fit: contain;
}
.user-welcome {
    align-items: center;
    text-align: left;
    margin-right: 20px;
}   

.sectionfaq-section {
    background-color: #f8f9fa;
    padding: 2rem;
    border-radius: 8px;
}
.faq-item {
    margin-bottom: 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
}
.faq-question {
    background-color: #fff;
    color: #81589a;
    padding: 15px;
    cursor: pointer;
    position: relative;
}
.faq-question::after {
    content: '+';
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
}
.faq-answer {
    display: none;
    padding: 15px;
    background-color: #f8f9fa;
}

.contactButton {
    padding: 10px 20px; 
    font-size: 1rem; 
    font-weight: bold;
    border: none;
    border-radius: 5px;
    background-color: #81589a;
    color: #fff;
    cursor: pointer;
}
    </style>
</head>
<body>
    <nav id="sidebar" class="sidebar">
        <div class="logo">
        <span class="user-welcome">Welcome, <?php echo $_SESSION['Firstname']; ?></span> <!--  I THINK -->
            <a href="../6-UserProfileManagementPage\2-ProfileHW.php"><i class="fas fa-user"></i></a> 
        </div>
        <ul>
            <li><a href="../1-GeneralPages\1-Home.php"><i class="fas fa-home"></i>Home</a></li>
            <li><a href="../7-TicketCreation\1-TicketCreation.php"><i class="fas fa-ticket"></i>Create Ticket</a></li>
            <li><a href="../3-HWDashboard\1-HWRequests.php"><i class="fas fa-tools"></i>Ticket Requests</a></li>
            <li><a href="../3-HWDashboard\2-TicketApproval.php"><i class="fas fa-check-circle"></i>Ticket Approvals</a></li>
            <li><a href="../3-HWDashboard\3-HWAnalytics.php"><i class="fas fa-chart-line"></i>Analytics</a></li>
            <li><a href="../3-HWDashboard\4-HWNotifications.php"><i class="fas fa-bell"></i>Notifications</a></li>
            <li class="active"><a href="../3-HWDashboard\5-HWHelp.php"><i class="fas fa-info-circle"></i>Help and Support</a></li>
        </ul>
        <div class="sidebar-footer">
            <p><a href="../6-UserProfileManagementPage\2-ProfileHW.php"><i class="fas fa-cog"></i> Settings</a></p>
            <p><a href="../5-UserSignInandRegistration/15-Logout.php" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</a></p>
        </div>
    </nav>

    <main>
        <header>
            <div class="header-left">
                <div id="hamburger-icon" class="hamburger-icon"><i class="fas fa-bars"></i></div>
                <strong>House Warden Dashboard</strong>
            </div>
            <div class="logo">
            <a href="../1-GeneralPages/1-Home.php">
                <img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="rumaintained logo">
            </a>
            </div>
        </header>

        
            <h2>Frequently Asked Questions</h2>
            <div class="faq-item">
                <div class="faq-question">What is this system used for?</div>
                <div class="faq-answer">This system is designed to help students and staff report and manage maintenance issues within university residences efficiently.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">How do I report a maintenance issue?</div>
                <div class="faq-answer">You can report a fault by simply clicking on "Create Ticket" in the menu on the left side of the screen which will take you to a page where you can fill in the necessary details.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">Can I track the status of my maintenance request?</div>
                <div class="faq-answer">Yes, once you report an issue, you can track its status from your dashboard to see if it's been acknowledged, scheduled, or resolved.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">What type of maintenance fault can I report?</div>
                <div class="faq-answer">You can report any issue related to the upkeep and maintenance of your residence, including plumbing, electrical, or general repairs.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">What should I do if I forget my password?</div>
                <div class="faq-answer">Click on the "Forgot Password" link on the login page and follow the instructions to reset your password.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">Didn't find an answer?</div>
                <div class="faq-answer"><a href="../1-GeneralPages\2-ContactUs.php"><button class="contactButton" type ="submit">Contact Us</button></a></div>
            </div>
       
       

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerIcon = document.getElementById('hamburger-icon');
            const sidebar = document.getElementById('sidebar');
            const main = document.querySelector('main');

            hamburgerIcon.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                main.classList.toggle('sidebar-collapsed');
            });
        });

        function confirmLogout() {
            return confirm("Are you sure you want to log out?");
        }

        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
                question.classList.toggle('active');
            });
        });
    </script>
</body>
</html>