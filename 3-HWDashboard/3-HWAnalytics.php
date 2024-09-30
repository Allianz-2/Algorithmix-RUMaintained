<?php
require_once("../5-UserSignInandRegistration/14-secure.php"); 
require '../8-PHPTests/config.php'; // Include your database configuration

// Database connection
$conn = mysqli_init(); 
if (!file_exists($ca_cert_path)) {
    die("CA file not found: " . $ca_cert_path);
}

mysqli_ssl_set($conn, NULL, NULL, $ca_cert_path, NULL, NULL);
mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL);

if (mysqli_connect_errno()) {
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Query to fetch the required data for charts
$ticketData = [];

// Example query to fetch counts of open tickets by day
$query = "SELECT DATE(DateCreated) as Date, COUNT(*) as OpenTickets 
          FROM ticket 
          WHERE Status = 'Requisitioned' 
          GROUP BY DATE(DateCreated)";

$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result === false) {
    // Log the error and display it
    $error = mysqli_error($conn);
    error_log("MySQL error: " . $error);
    die("An error occurred while fetching the data: " . $error);
}

// Fetch data from result set
while ($row = mysqli_fetch_assoc($result)) {
    $ticketData[] = [$row['Date'], (int)$row['OpenTickets']];
}

mysqli_free_result($result); // Free the result set
mysqli_close($conn); // Close the database connection

// You can now use $ticketData as needed, e.g., for JSON output
// echo json_encode($ticketData);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Warden Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="Dashboard.css">
    <style>
        /* Additional styles to match page */
        .charts-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2px;
            margin: 20px 0;
        }

        .chart-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .chart-box h3 {
            font-size: 18px;
            margin-bottom: 10px;
            text-align: center;
            font-weight: bold;
        }

        .content {
            margin: 20px;
        }

        /* Responsive layout for smaller screens */
        @media (max-width: 768px) {
            .charts-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
       <nav id="sidebar" class="sidebar">
            <div class="logo">
            <span class="user-welcome">Welcome, <?php echo $_SESSION['Firstname']; ?></span>
                <a href="../6-UserProfileManagementPage/2-ProfileHW.php"><i class="fas fa-user"></i></a>
            </div>
        <ul>
            <li><a href="../1-GeneralPages/1-Home.php"><i class="fas fa-home"></i>Home</a></li>
            <li><a href="../7-TicketCreation/1-TicketCreation.php"><i class="fas fa-ticket"></i>Create Ticket</a></li>
            <li><a href="../3-HWDashboard/1-HWRequests.php"><i class="fas fa-tools"></i>Ticket Requests</a></li>
            <li><a href="../3-HWDashboard/2-TicketApproval.php"><i class="fas fa-check-circle"></i>Ticket Approvals</a></li>
            <li class="active"><a href="../3-HWDashboard/3-HWAnalytics.php"><i class="fas fa-chart-line"></i>Analytics</a></li>
            <li><a href="../3-HWDashboard/4-HWNotifications.php"><i class="fas fa-bell"></i>Notifications</a></li>
            <li><a href="../3-HWDashboard/5-HWHelp.php"><i class="fas fa-info-circle"></i>Help and Support</a></li>
        </ul>
        <div class="sidebar-footer">
            <p><a href="../6-UserProfileManagementPage/2-ProfileHW.php"><i class="fas fa-cog"></i> Settings</a></p>
            <p><a href="../5-UserSignInandRegistration/15-Logout.php" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</a></p>
        </div>
    </nav>
    <main>
        <header>
            <div class="header-left">
                <div id="hamburger-icon" class="hamburger-icon"><i class="fas fa-bars"></i></div>
                <strong>House Warden Dashboard</strong>
            </div>
            <div class="logo"><img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="rumaintained logo"></div>
        </header>

        <div class="content">
            <h2>Analytics</h2>
           
            </div>

            <div class="charts-container">
                <!-- Chart containers -->
                <div class="chart-box">
                    <h3>Open Tickets by Day</h3>
                    <div id="open_tickets_chart" style="width: 100%; height: 300px;"></div>
                </div>
                <div class="chart-box">
                    <h3>Ticket Status Distribution</h3>
                    <div id="status_chart" style="width: 100%; height: 300px;"></div>
                </div>
                <div class="chart-box">
                    <h3>Average Resolution Time by Category</h3>
                    <div id="resolution_time_chart" style="width: 100%; height: 300px;"></div>
                </div>
                <div class="chart-box">
                    <h3>Tickets by Severity and Category</h3>
                    <div id="severity_category_chart" style="width: 100%; height: 300px;"></div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Load Google Charts library
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            // Data for Open Tickets chart from PHP
            var openTicketsData = google.visualization.arrayToDataTable([
                ['Day', 'Open Tickets'],
                <?php
                // Output the data for the Open Tickets chart
                echo "['Date', 'Open Tickets'],";
                foreach ($ticketData as $data) {
                    echo "['" . $data[0] . "', " . $data[1] . "],";
                }
                ?>
            ]);
            var openTicketsOptions = {
                curveType: 'function',
                legend: { position: 'bottom' },
                backgroundColor: '#f9f9f9',
                colors: ['#4caf50']
            };
            var openTicketsChart = new google.visualization.LineChart(document.getElementById('open_tickets_chart'));
            openTicketsChart.draw(openTicketsData, openTicketsOptions);

            // Placeholder for other chart data
            var statusData = google.visualization.arrayToDataTable([
                ['Status', 'Count'],
                ['Open', 10],
                ['Closed', 15],
                ['Pending', 5]
            ]);
            var statusOptions = {
                title: 'Ticket Status Distribution',
                backgroundColor: '#f9f9f9',
                colors: ['#2196F3', '#FFC107', '#F44336']
            };
            var statusChart = new google.visualization.PieChart(document.getElementById('status_chart'));
            statusChart.draw(statusData, statusOptions);

            // Placeholder for average resolution time data
            var resolutionData = google.visualization.arrayToDataTable([
                ['Category', 'Average Time (days)'],
                ['Plumbing', 2],
                ['Electrical', 3],
                ['Repairs', 1]
            ]);
            var resolutionOptions = {
                title: 'Average Resolution Time by Category',
                backgroundColor: '#f9f9f9',
                colors: ['#4caf50', '#ff9800', '#f44336']
            };
            var resolutionChart = new google.visualization.BarChart(document.getElementById('resolution_time_chart'));
            resolutionChart.draw(resolutionData, resolutionOptions);

            // Placeholder for tickets by severity and category
            var severityData = google.visualization.arrayToDataTable([
                ['Category', 'High', 'Medium', 'Low'],
                ['Plumbing', 4, 2, 1],
                ['Electrical', 3, 1, 2],
                ['Repairs', 5, 0, 1]
            ]);
            var severityOptions = {
                title: 'Tickets by Severity and Category',
                backgroundColor: '#f9f9f9',
                colors: ['#f44336', '#ff9800', '#4caf50'],
                isStacked: true
            };
            var severityChart = new google.visualization.BarChart(document.getElementById('severity_category_chart'));
            severityChart.draw(severityData, severityOptions);
        }

        function confirmLogout() {
            return confirm("Are you sure you want to log out?");
        }
    </script>
</body>
</html>