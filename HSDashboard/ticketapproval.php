<?php

require_once("config.php");

// Establishing connection with error handling
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM ticket";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall Secretary Dashboard</title>
    <link rel="stylesheet" href="path/to/fontawesome.css">
</head>
<body>
    <nav id="sidebar" class="sidebar">
        <div class="logo">
            <span class="user-welcome">Welcome, <?php echo $_SESSION['user_name']; ?></span> <!-- Display the username -->
            <a href="user_profile.php"><i class="fas fa-user"></i></a> 
        </div>
        <ul>
            <li class="active"><a href="StudentDBTicketHistory.php"><i class="fas fa-tools"></i>My Ticket History</a></li>
            <li><a href="StudentDBAnalytics.php"><i class="fas fa-chart-line"></i>Performance Analytics</a></li>
            <li><a href="StudentDBNotifications.php"><i class="fas fa-bell"></i>Notifications</a></li>
            <li><a href="StudentDBHelp.php"><i class="fas fa-info-circle"></i>Help and Support</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="#"><i class="fas fa-cog"></i> Settings</a>
            <a href="#" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>
    </nav>

    <main>
        <header>
            <div class="logo">
                <span class="user-welcome">Welcome, <?php echo $_SESSION['user_name']; ?></span>
                <a href="user_profile.php"></a>
            </div>
            <nav>
                <ul>
                    <li class="active"><a href="HSDSTicketApproval.php"><i class="fas fa-check-circle"></i> Ticket Approvals</a></li>
                    <li><a href="HSDSAnalytics.php"><i class="fas fa-chart-bar"></i> Analytics</a></li>
                    <li><a href="#"><i class="fas fa-tasks"></i> Requests</a></li>
                    <li><a href="#"><i class="fas fa-building"></i> Residences</a></li>
                    <li><a href="HSDSNotifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
                </ul>
            </nav>
        </header>

        <div class="header-left">
            <div id="hamburger-icon" class="hamburger-icon">
                <i class="fas fa-bars"></i>
            </div>
            <h2>Hall Secretary Dashboard</h2>
            <div class="logo">
                <img src="/path/to/rumaintained-logo.jpg" alt="RUMaintained logo">
            </div>
        </div>

        <div class="content">
            <h2>Ticket Approval</h2>

            <div class="progress-container">
                <div class="progress-bar">
                    <div id="progress" class="progress"></div>
                    <div class="step-wrapper">
                        <div class="step">1</div>
                        <div class="step-label">Create Ticket</div>
                    </div>
                    <div class="step-wrapper">
                        <div class="step">2</div>
                        <div class="step-label">House Warden Approval</div>
                    </div>
                    <div class="step-wrapper">
                        <div class="step active">3</div>
                        <div class="step-label">Hall Secretary Approval</div>
                    </div>
                </div>
            </div>
         
            <h3>Recent Requests</h3>
            <table>
                <tr>
                    <th>Ticket #</th>
                    <th>Date Created</th>
                    <th>Student Number</th>
                    <th>Fault Description</th>
                    <th>Approve</th>
                    <th>Deny</th>
                    <th>Comments</th>
                </tr>
            </table>

            <h3>My Tickets</h3>

            <?php
            if ($result->num_rows > 0) {
                echo "<table class='requests-table'>
                        <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>Date Created</th>
                            <th>Status</th>
                            <th>Fault Description</th>
                            <th>Severity</th>
                            <th></th>
                        </tr>
                        </thead>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['TicketID']}</td>";
                    echo "<td>{$row['DateCreated']}</td>";
                    echo "<td>{$row['Status']}</td>";
                    echo "<td>{$row['Description']}</td>";
                    echo "<td>{$row['Severity']}</td>";
                    echo "<td><a href='#'>Details</a></td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "<p>No tickets found</p>";
            }
            ?>
            
            <div>
                <a href="#"><button class="ticketButton" type="submit">Create New Ticket</button></a>
            </div>
        </div>
    </main>

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
    </script>

    <div id="piechart" style="width: 900px; height: 500px;"></div>
</body>
</html>
