<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RU Maintained Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="Dashboard.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #81589a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #81589a;
            color: #fff;
        }

        .filters, .stats {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .filter-group {
            flex: 1;
        }

        .stat-box {
            background-color: white;
            color: #81589a;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
            text-align: center;
            flex: 1;
            margin: 10px;
            position: relative;
            transition: transform 0.2s;
        }

        .stat-box:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.2);
        }

        .stat-box h4 {
            margin: 10px 0;
            font-size: 1.2em;
            color: #333;
        }

        .stat-box p {
            font-size: 2em;
            font-weight: bold;
        }

        .icon {
            font-size: 40px;
            color: #5c4b8a;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .charts {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .chart-box {
            background-color: white;
            color: #81589a;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
            text-align: center;
            width: 48%;
        }
    </style>
</head>

<body>
    <nav id="sidebar" class="sidebar">
        <div class="logo">
            <span class="user-welcome">Welcome, <?php echo $_SESSION['user_name']; ?></span> <!-- Display the user name dynamically -->
            <a href="user-page.php"><i class="fas fa-user"></i></a>
        </div>
        <ul>
            <li><a href="HSDSTicketApproval.php"><i class="fas fa-check-circle"></i> Ticket Approvals</a></li>
            <li><a href="HSDSAnalytics.php"><i class="fas fa-chart-bar"></i> Analytics</a></li>
            <li class="active"><a href="#"><i class="fas fa-tasks"></i> Requests</a></li>
            <li><a href="#"><i class="fas fa-building"></i> Residences</a></li>
            <li><a href="HSDSNotifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
        </ul>
        <div class="sidebar-footer">
            <p><a href="#"><i class="fas fa-cog"></i> Settings</a></p>
            <p><a href="#" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</a></p>
        </div>
    </nav>

<?php
session_start(); // Add this line

require_once("config.php");

// Establish connection with error handling
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM ticket";
$result = $conn->query($sql);
?>

    <main>
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
                <!-- Populate recent requests here dynamically -->
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
