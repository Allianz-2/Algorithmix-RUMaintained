<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); 
    include '../11-DashboardFunctionality\1-PermissionRequests.php';





?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall Secretary Dashboard</title>
    <link rel="icon" href="../Images\General\favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="Dashboard.css">
    <style>
       

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            background-color: white;
        }

        th {
            background-color: #81589a;
            color: #fff;
        }

        .filters,
        .stats {
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
        <span class="user-welcome">Welcome, <?php echo $_SESSION['Firstname']; ?></span> <!--  I THINK -->
            <a href="..\6-UserProfileManagementPage\3-ProfileHS.php"><i class="fas fa-user"></i></a>
        </div>
        <ul>
            <li><a href="..\1-GeneralPages\1-Home.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="..\4-HSDashboard\2-TicketApproval.php"><i class="fas fa-check-circle"></i> Ticket Approvals</a></li>
            <li class="active"><a href="#"><i class="fas fa-tasks"></i> Requests</a></li>
            <li><a href="..\4-HSDashboard\3-HSAnalytics.php"><i class="fas fa-chart-bar"></i> Analytics</a></li>
            <li><a href="..\4-HSDashboard\4-HSNotifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
            <li><a href="..\4-HSDashboard\5-HSHelp.php"><i class="fas fa-info-circle"></i> Help and Support</a></li>
            
        </ul>
        <div class="sidebar-footer">
        <p><a href="..\6-UserProfileManagementPage\3-ProfileHS.php"><i class="fas fa-cog"></i> Settings</a></p>
        <p><a href="..\5-UserSignInandRegistration\15-Logout.php" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</a></p>
        </div>
    </nav>

    <main>
        <header>
            <div class="header-left">
                <div id="hamburger-icon" class="hamburger-icon"><i class="fas fa-bars"></i></div>
                <strong>Hall Secretary Dashboard</strong>
            </div>
            <div class="logo">
            <a href="../1-GeneralPages/1-Home.php">
                <img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="RU Maintained Logo">
            </a>
            </div>
        </header>
        <div class="content">
            <h2>Requests</h2>
            <!-- <div class="filters">
                <div class="filter-group">
                    <label for="date-filter">Date Range</label>
                    <select id="date-filter">
                        <option>Last 7 Days</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="today">Today</option>
                        <option value="2 weeks">Last 2 weeks</option>
                        <option value="Month">Last Month</option>
                        <option value="3 months">Last 3 Months</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="residence-filter">Residence</label> <!-- DEConfirmed ON PERSONS HALL IT WILL SHOW RELEVANT RESIDENCES USING PHP 
                    <select id="residence-filter">
                        <option></option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="severity-filter">Severity</label>
                    <select id="severity-filter">
                        <option value="High">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                        <option value="emergency">Emergency</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="category-filter">Category</label>
                    <select id="category-filter">
                        <option>Any</option>
                        <option value="Plumbing">Plumbing</option>
                        <option value="electrical">Electrical</option>
                        <option value="roofing">Roofing</option>
                        <option value="broken and repairs">Repairs and breakage</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="status-filter">Status</label>
                    <select id="status-filter">
                        <option>Any</option>
                        <option value="active">Active</option>
                        <option value="Confirmed">Confirmed</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
            </div> -->

        <?php 
            require '../8-PHPTests/config.php';

            // Initializes MySQLi
            $conn = mysqli_init();

            // Test if the CA certificate file can be read
            if (!file_exists($ca_cert_path)) {
                die("CA file not found: " . $ca_cert_path);
            }

            mysqli_ssl_set($conn, NULL, NULL, $ca_cert_path, NULL, NULL);

            // Establish the connection
            mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL);

            // If connection failed, show the error
            if (mysqli_connect_errno()) {
                die('Failed to connect to MySQL: ' . mysqli_connect_error());
            }
        
            $results = mysqli_query($conn, "SELECT t.TicketID, t.Description, t.Status, t.Severity, t.DateCreated, t.StudentID
                                            FROM ticket t
                                            INNER JOIN residence r ON t.ResidenceID = r.ResidenceID
                                            WHERE t.Status = 'Confirmed' AND r.HallSecretaryID = '".$_SESSION['userID']."'
                                        ");
        
        $totalTickets = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as TotalTickets FROM ticket"))['TotalTickets'];
        $ConfirmedTickets = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as ConfirmedTickets FROM ticket WHERE Status = 'Confirmed'"))['ConfirmedTickets'];
        $completedTickets = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as CompletedTickets FROM ticket WHERE Status = 'Resolved'"))['CompletedTickets'];
        $viewedTickets = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as ViewedTickets FROM ticketcomment WHERE TicketID IN (SELECT TicketID FROM ticket WHERE Status IN ('Resolved', 'Closed'))"))['ViewedTickets'];
        ?>

        <div class="content">
           
            <div class="charts">
                <!-- Google Chart containers -->
                <div id="requestChart" class="chart-box"></div>
                <div id="residenceChart" class="chart-box"></div>
            </div>

            <div class="stats">
                <div class="stat-box">
                    <h4>Total Tickets</h4>
                    <p id="total-tickets"><?php echo $totalTickets; ?></p>
                </div>
                <div class="stat-box">
                    <h4>Confirmed Tickets</h4>
                    <p id="Confirmed-tickets"><?php echo $ConfirmedTickets; ?></p>
                </div>
                <div class="stat-box">
                    <h4>Completed Tickets</h4>
                    <p id="completed-tickets"><?php echo $completedTickets; ?></p>
                </div>
                <div class="stat-box">
                    <h4>Viewed Tickets</h4>
                    <p id="viewed-tickets"><?php echo $viewedTickets; ?></p>
                </div>
            </div>

            <table>
                <thead>
                    <h3>Maintenance Requests:</h3>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Severity</th>
                        <th>Date Created</th>
                        <th>Student Number</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($results)): ?>
                    <tr>
                        <td><?php echo $row['TicketID']; ?></td>
                        <td><?php echo $row['Description']; ?></td>
                        <td><?php echo $row['Status']; ?></td>
                        <td><?php echo $row['Severity']; ?></td>
                        <td><?php echo $row['DateCreated']; ?></td>
                        <td><?php echo $row['StudentID']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Load Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            // Request Pie Chart
            var requestData = google.visualization.arrayToDataTable([
                ['Task', 'Count'],
                ['incomplete Tickets', <?php echo $totalTickets; ?>],
                ['Confirmed Tickets', <?php echo $ConfirmedTickets; ?>],
                ['Completed Tickets', <?php echo $completedTickets; ?>],
                ['Viewed Tickets', <?php echo $viewedTickets; ?>]
            ]);

            var requestOptions = {
                title: 'Requests Overview',
                backgroundColor: 'transparent',
                pieHole: 0.4,
                colors: ['#a64b89', '#5c4b8a', '#f1eaf5', '#81589a'],
                legend: 'bottom'
            };

            var requestChart = new google.visualization.PieChart(document.getElementById('requestChart'));
            requestChart.draw(requestData, requestOptions);

            // Residence Column Chart
            var residenceData = google.visualization.arrayToDataTable([
                ['Task', 'Count'],
                ['Confirmed', <?php echo $ConfirmedTickets; ?>],
                ['Completed', <?php echo $completedTickets; ?>]
            ]);

            var residenceOptions = {
                title: 'Residence Tasks',
                backgroundColor: 'transparent',
                colors: ['#f4a5d2', '#c0a1f5'],
                legend: 'none',
                chartArea: { width: '70%' },
                hAxis: {
                    title: 'Task Status',
                    minValue: 0
                },
                vAxis: {
                    title: 'Count'
                }
            };

            var residenceChart = new google.visualization.ColumnChart(document.getElementById('residenceChart'));
            residenceChart.draw(residenceData, residenceOptions);
        }

        function confirmLogout() {
            return confirm('Are you sure you want to log out?');
        }
    </script>
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
</body>
</html>

</html>
