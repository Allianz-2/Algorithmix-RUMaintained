<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); 
    // include '11-DashboardFunctionality\3-PermissionAnalytics.php';
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




<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); 
    require '../8-PHPTests/config.php';

    include '../8-PHPTests/connectionAzure.php';


    // Query to fetch ticket status distribution
    $statusQuery = "SELECT Status, COUNT(TicketID) AS Count FROM Ticket GROUP BY Status;";
    $statusData = [];
    $result = $conn->query($statusQuery);
    while ($row = $result->fetch_assoc()) {
        $statusData[] = [$row['Status'], (int)$row['Count']];
    }

    // Query to fetch average resolution time by category
    $resolutionTimeQuery = "SELECT fc.CategoryName, AVG(DATEDIFF(Ticket.DateResolved, Ticket.DateCreated)) AS ResolutionTime
                            FROM Ticket
                            JOIN faultcategory fc ON Ticket.CategoryID = fc.CategoryID
                            WHERE Ticket.DateResolved IS NOT NULL
                            GROUP BY CategoryName;";
    $resolutionTimeData = [];
    $result = $conn->query($resolutionTimeQuery);
    while ($row = $result->fetch_assoc()) {
        $resolutionTimeData[] = [$row['CategoryName'], (float)$row['ResolutionTime']];
    }

    // Query to fetch tickets by severity and category
    $severityCategoryQuery = "SELECT fc.CategoryName, 
        SUM(CASE WHEN Severity = 'High' THEN 1 ELSE 0 END) AS High,
        SUM(CASE WHEN Severity = 'Medium' THEN 1 ELSE 0 END) AS Medium,
        SUM(CASE WHEN Severity = 'Low' THEN 1 ELSE 0 END) AS Low
        FROM Ticket
        JOIN faultcategory fc ON Ticket.CategoryID = fc.CategoryID
        WHERE Ticket.DateResolved IS NOT NULL
        GROUP BY fc.CategoryName;";
    $severityCategoryData = [];
    $result = $conn->query($severityCategoryQuery);
    while ($row = $result->fetch_assoc()) {
        $severityCategoryData[] = [$row['CategoryName'], (int)$row['High'], (int)$row['Medium'], (int)$row['Low']];
    }
    
    function getMaintenanceFaultStatsPerSemester($conn) {
        $query = "
            SELECT r.ResName, 
                   COUNT(t.TicketID) AS FaultCount, 
                   YEAR(t.DateCreated) AS Year,
                   CASE 
                       WHEN MONTH(t.DateCreated) BETWEEN 1 AND 6 THEN 'Semester 1'
                       ELSE 'Semester 2'
                   END AS Semester
            FROM ticket t
            JOIN residence r ON t.ResidenceID = r.ResidenceID
            WHERE t.Status = 'Resolved'
            GROUP BY r.ResName, Year, Semester
        ";
    
        $result = mysqli_query($conn, $query);
        if (!$result) {
            die('Error executing query: ' . mysqli_error($conn));
        }
    
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    
        mysqli_free_result($result);
    
        return $data;
    } // *

 $faultStatsPerSemester = getMaintenanceFaultStatsPerSemester($conn); //*
?>
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
            <a href="../1-GeneralPages/1-Home.php">
            <div class="logo"><img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="rumaintained logo"></div>
            </a>
        </header>
        <div class="content">
            <h2>Analytics</h2>
            <div class="charts-container">


            <div class="chart-box">
                <h3>Semester Maintenance Fault Stats by Res</h3>
                <canvas id="faultStatsPerSemesterChart" style="width: 100%; height: 300px;"></canvas>
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
                    <div id="severity_chart" style="width: 100%; height: 300px;"></div>
                </div>

            </div>
        </div>
    </main>

    <script>
        
// Fault Stats per Semester
const faultStatsData = <?php echo json_encode($faultStatsPerSemester); ?>;
const faultStatsLabels = faultStatsData.map(data => `${data.ResName} - ${data.Year} ${data.Semester}`);
const faultStatsCounts = faultStatsData.map(data => data.FaultCount);
const customColor = '#4E73DF'; // Hex color code

const faultStatsPerSemesterCtx = document.getElementById('faultStatsPerSemesterChart').getContext('2d');

new Chart(faultStatsPerSemesterCtx, {
    type: 'bar',
    colors: [
        '#36B9CC', ],
    data: {
        labels: faultStatsLabels,
        datasets: [{
            label: 'Fault Count',
            data: faultStatsCounts,
            fontName: 'Arial',
            colors: [
                '#36B9CC', ]
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});


    // Load Google Charts library
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {

        // Ticket Status Distribution chart data
        var statusData = google.visualization.arrayToDataTable([
            ['Status', 'Count'],
            <?php
                foreach ($statusData as $data) {
                    echo "['".$data[0]."', ".$data[1]."],";
                }
            ?>
        ]);
        var statusOptions = {
            title: 'Ticket Status Distribution',
            pieHole: 0.4,
            colors: [
         '#4E73DF', // Blue
         '#1CC88A', // Green
        '#36B9CC', // Light Blue
        '#F6C23E', // Yellow
        '#E74A3B'  // Red
         ]
        };
        var statusChart = new google.visualization.PieChart(document.getElementById('status_chart'));
        statusChart.draw(statusData, statusOptions);

        // Average Resolution Time by Category chart data
        var resolutionTimeData = google.visualization.arrayToDataTable([
            ['Category', 'Average Resolution Days'],
            <?php
                foreach ($resolutionTimeData as $data) {
                    echo "['".$data[0]."', ".$data[1]."],";
                }
            ?>
        ]);
        var resolutionTimeOptions = {
            title: 'Average Resolution Days by Category',
            legend: { position: 'bottom' },
            colors: [
    '#36B9CC', ]
        };
        var resolutionTimeChart = new google.visualization.BarChart(document.getElementById('resolution_time_chart'));
        resolutionTimeChart.draw(resolutionTimeData, resolutionTimeOptions);

        // Tickets by Severity and Category chart data
        var severityData = google.visualization.arrayToDataTable([
            ['Category', 'High', 'Medium', 'Low'],
            <?php
                foreach ($severityCategoryData as $data) {
                    echo "['".$data[0]."', ".$data[1].", ".$data[2].", ".$data[3]."],";
                }
            ?>
        ]);
        var severityOptions = {
            title: 'Tickets by Severity and Category',
            hAxis: { title: 'Category' },
            vAxis: { title: 'Number of Tickets' },
            isStacked: true,
            colors: [    
           '#E74C3C', // Red
           '#F39C12', // Orange
           '#2ECC71'  // Green
           ]   
        };
        var severityChart = new google.visualization.ColumnChart(document.getElementById('severity_chart'));
        severityChart.draw(severityData, severityOptions);
    }

    // Confirm logout function
    function confirmLogout() {
        return confirm("Are you sure you want to log out?");
    }

    // Adjust chart sizes on window resize
    window.onresize = function() {
        drawCharts();
    };
    document.getElementById('hamburger-icon').addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('collapsed');
    document.querySelector('main').classList.toggle('sidebar-collapsed');
});

    </script>
</body>
</html>
