<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); 




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RU Maintained Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="Dashboard.css">
    <style>
        /* Additional styles to match page */
        .charts-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
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
            <span class="user-welcome">Welcome, <?php echo $_SESSION['Firstname']; ?></span> <!--  I THINK -->
            <a href="..\6-UserProfileManagementPage\3-ProfileHS.php"><i class="fas fa-user"></i></a>
        </div>
        <ul>
            <li><a href="..\1-GeneralPages\1-Home.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="..\4-HSDashboard\2-TicketApproval.php"><i class="fas fa-check-circle"></i> Ticket Approvals</a></li>
            <li><a href="..\4-HSDashboard\1-HSRequests.php"><i class="fas fa-tasks"></i> Requests</a></li>
            <li class="active"><a href="HSAnalyticsFinal.php"><i class="fas fa-chart-bar"></i> Analytics</a></li>
            <li><a href="..\4-HSDashboard\4-HSNotifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
            <li><a href="..\4-HSDashboard\5-HSHelp.php"><i class="fas fa-info-circle"></i> Help and Support</a></li>
        </ul>
        <div class="sidebar-footer">
            <p><a href="#"><i class="fas fa-cog"></i> Settings</a></p>
            <p><a href="..\5-UserSignInandRegistration\15-Logout.php" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</a></p>
        </div>
    </nav>
    <main>
        <header>
            <div class="header-left">
                <div id="hamburger-icon" class="hamburger-icon"><i class="fas fa-bars"></i></div>
                <strong>Hall Secretary Dashboard</strong>
            </div>
            <div class="logo"><img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="rumaintained logo"></div>
        </header>

        <div class="content">
            <h2>Analytics</h2>
            

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
        
        $results = mysqli_query($conn, "SELECT * FROM ticket WHERE Status= 'Confirmed'"); // Add this line to define $results
        

  // Query to fetch open tickets by day of the week
  $openTicketsQuery = "SELECT DAYNAME(DateCreated) AS Day, COUNT(TicketID) AS OpenTickets
  FROM Ticket
  WHERE Status = 'Open'
  GROUP BY Day
  ORDER BY FIELD(Day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');";
$openTicketsData = [];
$result = $conn->query($openTicketsQuery);
while ($row = $result->fetch_assoc()) {
$openTicketsData[] = [$row['Day'], (int)$row['OpenTickets']];
}

// Query to fetch ticket status distribution
$statusQuery = "SELECT Status, COUNT(TicketID) AS Count FROM Ticket GROUP BY Status;";
$statusData = [];
$result = $conn->query($statusQuery);
while ($row = $result->fetch_assoc()) {
$statusData[] = [$row['Status'], (int)$row['Count']];
}

// Query to fetch average resolution time by category
$resolutionTimeQuery = "SELECT CategoryName, AVG(DATEDIFF(Ticket.DateResolved, Ticket.DateCreated)) AS ResolutionTime
FROM Ticket
JOIN faultcategory ON ticket.CategoryID = faultcategory.CategoryID
WHERE Ticket.DateResolved IS NOT NULL
GROUP BY CategoryName;";
$resolutionTimeData = [];
$result = $conn->query($resolutionTimeQuery);
while ($row = $result->fetch_assoc()) {
$resolutionTimeData[] = [$row['CategoryName'], (float)$row['ResolutionTime']];
}

// Query to fetch tickets by severity and category
$severityCategoryQuery = "SELECT CategoryName, 
    SUM(CASE WHEN Severity = 'High' THEN 1 ELSE 0 END) AS High,
    SUM(CASE WHEN Severity = 'Medium' THEN 1 ELSE 0 END) AS Medium,
    SUM(CASE WHEN Severity = 'Low' THEN 1 ELSE 0 END) AS Low
FROM Ticket
JOIN faultcategory ON ticket.CategoryID = faultcategory.CategoryID
WHERE Ticket.DateResolved IS NOT NULL
GROUP BY CategoryName;";
$severityCategoryData = [];
$result = $conn->query($severityCategoryQuery);
while ($row = $result->fetch_assoc()) {
$severityCategoryData[] = [$row['CategoryName'], (int)$row['High'], (int)$row['Medium'], (int)$row['Low']];
}
?>



 
    <script>
        // Load Google Charts library
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            // Open Tickets chart data from PHP
            var openTicketsData = google.visualization.arrayToDataTable([
                ['Day', 'Open Tickets'],
                <?php
                    foreach ($openTicketsData as $data) {
                        echo "['".$data[0]."', ".$data[1]."],";
                    }
                ?>
            ]);
            var openTicketsOptions = {
                curveType: 'function',
                legend: { position: 'bottom' },
                backgroundColor: '#F8F9FC',
                chartArea: { width: '85%', height: '75%' },
                fontName: 'Arial',
                colors: ['#4E73DF']
            };
            var openTicketsChart = new google.visualization.LineChart(document.getElementById('open_tickets_chart'));
            openTicketsChart.draw(openTicketsData, openTicketsOptions);

            // Ticket Status chart data from PHP
            var statusData = google.visualization.arrayToDataTable([
                ['Status', 'Count'],
                <?php
                    foreach ($statusData as $data) {
                        echo "['".$data[0]."', ".$data[1]."],";
                    }
                ?>
            ]);
            var statusOptions = {
                pieHole: 0.4,
                backgroundColor: '#F8F9FC',
                chartArea: { width: '85%', height: '75%' },
                fontName: 'Arial',
                colors: ['#4E73DF', '#1CC88A', '#36B9CC'] // Assuming additional colors for variety



            };
            var statusChart = new google.visualization.PieChart(document.getElementById('status_chart'));
            statusChart.draw(statusData, statusOptions);

            // Resolution Time chart data from PHP
            var resolutionTimeData = google.visualization.arrayToDataTable([
                ['CategoryName', 'Resolution Time (days)'],
                <?php
                    foreach ($resolutionTimeData as $data) {
                        echo "['".$data[0]."', ".$data[1]."],";
                    }
                ?>
            ]);
            var resolutionTimeOptions = {
                backgroundColor: '#F8F9FC',
                chartArea: { width: '85%', height: '75%' },
                fontName: 'Arial',
                colors: ['#36B9CC']
            };
            var resolutionTimeChart = new google.visualization.ColumnChart(document.getElementById('resolution_time_chart'));
            resolutionTimeChart.draw(resolutionTimeData, resolutionTimeOptions);

            // Severity and Category chart data from PHP
            var severityCategoryData = google.visualization.arrayToDataTable([
                ['CategoryName', 'High', 'Medium', 'Low'],
                <?php
                    foreach ($severityCategoryData as $data) {
                        echo "[' ".$data[0]."', ".$data[1].", ".$data[2].", ".$data[3]."],";
                    }
                ?>
            ]);
            var severityCategoryOptions = {
                isStacked: true,
                backgroundColor: '#F8F9FC',
                chartArea: { width: '85%', height: '75%' },
                fontName: 'Arial',
                colors: ['#E74A3B', '#F6C23E', '#1CC88A']
            };
            var severityCategoryChart = new google.visualization.BarChart(document.getElementById('severity_category_chart'));
            severityCategoryChart.draw(severityCategoryData, severityCategoryOptions);
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
