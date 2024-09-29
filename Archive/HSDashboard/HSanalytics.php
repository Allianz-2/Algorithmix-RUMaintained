<?php

require_once("config.php");

// Establishing connection with error handling
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

    // Define queries for chart data
    $queries = [
        'openTickets' => "SELECT DATE(DateCreated) as date, COUNT(*) as count FROM ticket WHERE Status != 'Closed' GROUP BY DATE(DateCreated) ORDER BY date DESC LIMIT 30",
        'statusDistribution' => "SELECT Status, COUNT(*) as count FROM ticket GROUP BY Status",
        'avgResolutionTime' => "SELECT AVG(DATEDIFF(DateResolved, DateCreated)) as avg_days, EXTRACT(YEAR_MONTH FROM DateCreated) as month FROM ticket WHERE Status = 'Closed' GROUP BY EXTRACT(YEAR_MONTH FROM DateCreated) ORDER BY month DESC LIMIT 12",
        'severityCategory' => "SELECT Severity, Category, COUNT(*) as count FROM ticket GROUP BY Severity, Category"
    ];

    // Fetch data for each query
    //$openTicketsData = fetchData($conn, $queries['openTickets']);
   // $statusData = fetchData($conn, $queries['statusDistribution']);
    //$resolutionTimeData = fetchData($conn, $queries['avgResolutionTime']);
    //$severityCategoryData = fetchData($conn, $queries['severityCategory']);

    // Close the connection
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

            <?php foreach ($openTicketsData as $row): ?>
                data.addRow([new Date('<?= $row['date'] ?>'), <?= $row['count'] ?>]);
            <?php endforeach; ?>

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

            <?php foreach ($statusData as $row): ?>
                data.addRow(['<?= $row['Status'] ?>', <?= $row['count'] ?>]);
            <?php endforeach; ?>

            var options = {
                title: 'Ticket Status Distribution',
                pieHole: 0.4
            };

            var chart = new google.visualization.PieChart(document.getElementById('status_chart'));
            chart.draw(data, options);
        }

        function drawResolutionTimeChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Month');
            data.addColumn('number', 'Average Resolution Time (Days)');

            <?php foreach ($resolutionTimeData as $row):
                $month = date('M Y', strtotime($row['month'] . '01')); ?>
                data.addRow(['<?= $month ?>', <?= $row['avg_days'] ?>]);
            <?php endforeach; ?>

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
            <?php foreach ($severityCategoryData as $row): ?>
                if (!categories['<?= $row['Category'] ?>']) {
                    categories['<?= $row['Category'] ?>'] = {High: 0, Medium: 0, Low: 0};
                }
                categories['<?= $row['Category'] ?>']['<?= $row['Severity'] ?>'] = <?= $row['count'] ?>;
            <?php endforeach; ?>

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
<body>
    <header>
        <div class="logo">
            <span class="user-welcome">Welcome, <?= $_SESSION['user_name'] ?></span>
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
    </header>

    <main role="main">
        <div class="content">
            <h2>Analytics</h2>
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
