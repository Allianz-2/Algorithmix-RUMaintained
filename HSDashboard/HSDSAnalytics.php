<?php
require_once('config.php');
$conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE);

if (!$conn) {
    die('Unable to connect to the database: ' . mysqli_connect_error());
}

// Function to fetch data for charts
function fetchChartData($conn, $query) {
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die('Query failed: ' . mysqli_error($conn));
    }
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    mysqli_free_result($result);
    return $data;
}

// Queries for each chart
$openTicketsQuery = "SELECT DATE(DateCreated) as date, COUNT(*) as count FROM ticket WHERE Status != 'Closed' GROUP BY DATE(DateCreated) ORDER BY date DESC LIMIT 30";
$statusQuery = "SELECT Status, COUNT(*) as count FROM ticket GROUP BY Status";
$resolutionTimeQuery = "SELECT AVG(DATEDIFF(DateResolved, DateCreated)) as avg_days, EXTRACT(YEAR_MONTH FROM DateCreated) as month FROM ticket WHERE Status = 'Closed' GROUP BY EXTRACT(YEAR_MONTH FROM DateCreated) ORDER BY month DESC LIMIT 12";
$severityCategoryQuery = "SELECT Severity, Category, COUNT(*) as count FROM ticket GROUP BY Severity, Category";

// Fetch data for each chart
$openTicketsData = fetchChartData($conn, $openTicketsQuery);
$statusData = fetchChartData($conn, $statusQuery);
$resolutionTimeData = fetchChartData($conn, $resolutionTimeQuery);
$severityCategoryData = fetchChartData($conn, $severityCategoryQuery);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hall Secretary Dashboard - Analytics</title>
    <link rel="stylesheet" href="Dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            drawOpenTicketsChart();
            drawStatusChart();
            drawResolutionTimeChart();
            drawSeverityCategoryChart();
        }

        function drawOpenTicketsChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('date', 'Date');
            data.addColumn('number', 'Open Tickets');

            <?php
            foreach ($openTicketsData as $row) {
                echo "data.addRow([new Date('" . $row['date'] . "'), " . $row['count'] . "]);\n";
            }
            ?>

            var options = {
                title: 'Open Tickets Over Time',
                curveType: 'function',
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('open_tickets_chart'));
            chart.draw(data, options);
        }

        function drawStatusChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Status');
            data.addColumn('number', 'Count');

            <?php
            foreach ($statusData as $row) {
                echo "data.addRow(['" . $row['Status'] . "', " . $row['count'] . "]);\n";
            }
            ?>

            var options = {
                title: 'Ticket Status Distribution',
                pieHole: 0.4,
            };

            var chart = new google.visualization.PieChart(document.getElementById('status_chart'));
            chart.draw(data, options);
        }

        function drawResolutionTimeChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Month');
            data.addColumn('number', 'Average Resolution Time (Days)');

            <?php
            foreach ($resolutionTimeData as $row) {
                $month = date('M Y', strtotime($row['month'] . '01'));
                echo "data.addRow(['" . $month . "', " . $row['avg_days'] . "]);\n";
            }
            ?>

            var options = {
                title: 'Average Resolution Time by Month',
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('resolution_time_chart'));
            chart.draw(data, options);
        }

        function drawSeverityCategoryChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Category');
            data.addColumn('number', 'High');
            data.addColumn('number', 'Medium');
            data.addColumn('number', 'Low');

            var categories = {};
            <?php
            foreach ($severityCategoryData as $row) {
                echo "if (!categories['" . $row['Category'] . "']) { categories['" . $row['Category'] . "'] = {High: 0, Medium: 0, Low: 0}; }\n";
                echo "categories['" . $row['Category'] . "']['" . $row['Severity'] . "'] = " . $row['count'] . ";\n";
            }
            ?>

            for (var category in categories) {
                data.addRow([category, categories[category]['High'], categories[category]['Medium'], categories[category]['Low']]);
            }

            var options = {
                title: 'Ticket Distribution by Severity and Category',
                isStacked: true,
                legend: { position: 'top', maxLines: 3 },
                hAxis: { title: 'Category', titleTextStyle: { color: '#333' } },
                vAxis: { minValue: 0 }
            };

            var chart = new google.visualization.BarChart(document.getElementById('severity_category_chart'));
            chart.draw(data, options);
        }
    </script>
</head>
</head>
<body>
    <header>
        <div class="logo">
            <span class="user-welcome">Welcome, <?php echo $_SESSION['user_name']; ?></span>
            <a href="user_profile.php"></a>
        </div>
        <nav>
            <ul>
                <li><a href="HSDSTicketApproval.php"><i class="fas fa-check-circle"></i> Ticket Approvals</a></li>
                <li class="active"><a href="HSDSAnalytics.php"><i class="fas fa-chart-bar"></i> Analytics</a></li>
                <li><a href="#"><i class="fas fa-tasks"></i> Requests</a></li>
                <li><a href="#"><i class="fas fa-building"></i> Residences</a></li>
                <li><a href="HSDSNotifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <a href="#"><i class="fas fa-cog"></i> Settings</a>
            <a href="#" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>
    </header>

    <main role="main">
        <div>
            <div class="header-left">
                <div id="hamburger-icon" class="hamburger-icon">
                    <i class="fas fa-bars"></i>
                </div>
                <h2>Hall Secretary Dashboard</h2>
            </div>
            <div class="logo">
                <img src="/path/to/rumaintained-logo.jpg" alt="rumaintained logo">
            </div>
        </div>



        <div class="content">
            <h2>Analytics</h2>

            <div class="filters">
                <!-- Add filter dropdowns here if needed -->
            </div>

            <div class="charts">
                <div id="open_tickets_chart" style="width: 100%; height: 400px;"></div>
                <div id="status_chart" style="width: 100%; height: 400px;"></div>
                <div id="resolution_time_chart" style="width: 100%; height: 400px;"></div>
                <div id="severity_category_chart" style="width: 100%; height: 400px;"></div>
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
</body>
</html>