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
            <span class="user-welcome">Welcome, </span>
            <a href="Z:\Algorithmix-RUMaintained\6-UserProfileManagementPage\2-ProfileHW.php"><i class="fas fa-user"></i></a>
        </div>
        <ul>
        <li><a href="../1-GeneralPages\1-Home.php"><i class="fas fa-home"></i>Home</a></li>
            <li><a href="../7-TicketCreation\1-TicketCreation.php"><i class="fas fa-ticket"></i>Create Ticket</a></li>
            <li><a href="../2-StudentDashboard\1-StudentRequests.php"><i class="fas fa-tools"></i>Ticket Requests</a></li>
            <li class='active'><a href="../2-StudentDashboard\3-StudentAnalytics.php"><i class="fas fa-chart-line"></i>Analytics</a></li>
            <li><a href="../2-StudentDashboard\4-StudentNotifications.php"><i class="fas fa-bell"></i>Notifications</a></li>
            <li><a href="../2-StudentDashboard\5-StudentHelp.php"><i class="fas fa-info-circle"></i>Help and Support</a></li> 
        </ul>
        <div class="sidebar-footer">
            <p><a href="#"><i class="fas fa-cog"></i> Settings</a></p>
            <p><a href="#" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</a></p>
        </div>
    </nav>
    <main>
        <header>
            <div class="header-left">
                <div id="hamburger-icon" class="hamburger-icon"><i class="fas fa-bars"></i></div>
                <strong>Student Dashboard</strong>
            </div>
            <div class="logo"><img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="rumaintained logo"></div>
        </header>

        <div class="content">
            <h2>Analytics</h2>
            <div class="filters">
                <!-- Filters for date, residence, severity, etc. -->
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
                    <label for="residence-filter">Residence</label>
                    <select id="residence-filter">
                        <option>Chris Hani House</option>
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
                        <option value="Pending">Pending</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
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
            // Sample data for Open Tickets chart
            var openTicketsData = google.visualization.arrayToDataTable([
                ['Day', 'Open Tickets'],
                ['Monday',  12],
                ['Tuesday',  5],
                ['Wednesday',  7],
                ['Thursday',  8],
                ['Friday',  15],
                ['Saturday',  6],
                ['Sunday',  4]
            ]);
            var openTicketsOptions = {
                curveType: 'function',
                legend: { position: 'bottom' },
                backgroundColor: '#f9f9f9',
                chartArea: { width: '85%', height: '75%' },
                fontName: 'Arial'
            };
            var openTicketsChart = new google.visualization.LineChart(document.getElementById('open_tickets_chart'));
            openTicketsChart.draw(openTicketsData, openTicketsOptions);

            // Sample data for Status chart
            var statusData = google.visualization.arrayToDataTable([
                ['Status', 'Count'],
                ['Active',  20],
                ['Pending',  15],
                ['Closed',  30]
            ]);
            var statusOptions = {
                pieHole: 0.4,
                backgroundColor: '#f9f9f9',
                chartArea: { width: '85%', height: '75%' },
                fontName: 'Arial'
            };
            var statusChart = new google.visualization.PieChart(document.getElementById('status_chart'));
            statusChart.draw(statusData, statusOptions);

            // Sample data for Resolution Time chart
            var resolutionTimeData = google.visualization.arrayToDataTable([
                ['Category', 'Resolution Time (days)'],
                ['Plumbing', 3],
                ['Electrical', 2],
                ['Roofing', 4],
                ['Repairs', 1],
                ['Other', 5]
            ]);
            var resolutionTimeOptions = {
                hAxis: { title: 'Category' },
                vAxis: { title: 'Resolution Time (days)' },
                backgroundColor: '#f9f9f9',
                chartArea: { width: '85%', height: '75%' },
                fontName: 'Arial',
                legend: 'none'
            };
            var resolutionTimeChart = new google.visualization.ColumnChart(document.getElementById('resolution_time_chart'));
            resolutionTimeChart.draw(resolutionTimeData, resolutionTimeOptions);

            // Sample data for Severity and Category chart
            var severityCategoryData = google.visualization.arrayToDataTable([
                ['Category', 'High', 'Medium', 'Low'],
                ['Plumbing', 10, 5, 2],
                ['Electrical', 8, 4, 1],
                ['Roofing', 6, 3, 1],
                ['Repairs', 4, 2, 0],
                ['Other', 12, 6, 3]
            ]);
            var severityCategoryOptions = {
                isStacked: true,
                hAxis: { title: 'Category' },
                vAxis: { title: 'Number of Tickets' },
                backgroundColor: '#f9f9f9',
                chartArea: { width: '85%', height: '75%' },
                fontName: 'Arial',
                legend: { position: 'bottom' }
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
