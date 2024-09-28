<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RU Maintained Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            background-color: #f1eaf5;
            color:#81589a; 
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
            color: #81589a;
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
            justify-content: space-around;
            margin-top: 20px;
            max-width: 800px; 
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <nav id="sidebar" class="sidebar">
        <div class="logo">
            <span class="user-welcome">Welcome, </span><!-- Add PHP code for user name -->
            <a href="user-page"><i class="fas fa-user"></i></a>
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

    <main>
        <header>
            <div class="header-left">
                <div id="hamburger-icon" class="hamburger-icon"><i class="fas fa-bars"></i></div>
                <strong>Maintenance Staff Dashboard</strong>
            </div>
            <div class="logo">
                <img src="Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="RU Maintained Logo">
            </div>
        </header>

        <?php 
require_once('config.php');

// Connect to the database
$conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');

// Fetch statistics
$totalTickets = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as TotalTickets FROM ticket"))['TotalTickets'];
$pendingTickets = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as PendingTickets FROM ticket WHERE Status = 'Pending'"))['PendingTickets'];
$completedTickets = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as CompletedTickets FROM ticket WHERE Status = 'Resolved'"))['CompletedTickets'];
$viewedTickets = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as ViewedTickets FROM ticketcomment WHERE TicketID IN (SELECT TicketID FROM ticket WHERE Status IN ('Resolved', 'Closed'))"))['ViewedTickets'];

// Fetch online users
$onlineUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as OnlineUsers FROM user WHERE role = 'ST' AND UserID IN (SELECT UserID FROM student)"))['OnlineUsers'];

?>
        <div class="content">
            <h3>Maintenance Requests</h3>
            <div class="filters">
                <div class="filter-group">
                    <label for="date-filter">Date Range</label>
                    <select id="date-filter">
                        <option>Last 7 Days</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="today">Today</option>
                        <option value="2 weeks">Last 2 weeks</option>
                        <option value="month">Last Month</option>
                        <option value="3 months">Last 3 Months</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="residence-filter">Residence</label>
                    <select id="residence-filter">
                        <option>All Residences</option>
                        <option>Chris Hani House</option>
                        <!-- Add more residences here -->
                    </select>
                </div>
                <div class="filter-group">
                    <label for="severity-filter">Severity</label>
                    <select id="severity-filter">
                        <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option>
                        <option value="Emergency">Emergency</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="category-filter">Category</label>
                    <select id="category-filter">
                        <option>Any</option>
                        <option value="Plumbing">Plumbing</option>
                        <option value="Electrical">Electrical</option>
                        <option value="Roofing">Roofing</option>
                        <option value="Repairs">Repairs and Breakages</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="status-filter">Status</label>
                    <select id="status-filter">
                        <option>Any</option>
                        <option value="Resolved">Resolved</option>
                        <option value="Open">Open</option>
                        <option value="Closed">Closed</option>
                    </select>
                </div>
            </div>

            <div class="charts">
                <canvas id="maintenanceRequestChart"></canvas>
                <canvas id="residenceTaskChart"></canvas>
            </div>



            <div class="charts">
                <!-- Charts will be added here -->
                <canvas id="requestChart"></canvas>
                <canvas id="residenceChart"></canvas>
            </div>
            <div class="stats">
    <div class="stat-box">
        <h4>Total Tickets</h4>
        <p id="total-tickets"><?php echo $totalTickets; ?></p>
    </div>
    <div class="stat-box">
        <h4>Pending Tickets</h4>
        <p id="pending-tickets"><?php echo $pendingTickets; ?></p>
    </div>
    <div class="stat-box">
        <h4>Completed Tickets</h4>
        <p id="completed-tickets"><?php echo $completedTickets; ?></p>
    </div>
    <div class="stat-box">
        <h4>Viewed Tickets</h4>
        <p id="viewed-tickets"><?php echo $viewedTickets; ?></p>
    </div>
    <div class="stat-box">
        <h4>Online Users</h4>
        <p id="online-users"><?php echo $onlineUsers; ?></p>
    </div>
</div>

            <table>
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Severity</th>
                        <th>Date Created</th>
                        <th>Assigned User</th>
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
                            <td><?php echo $row['First_name'] . " " . $row['Lastname']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        // Chart.js code for maintenance requests and residence tasks
        const maintenanceRequestChartCtx = document.getElementById('maintenanceRequestChart').getContext('2d');
        const residenceTaskChartCtx = document.getElementById('residenceTaskChart').getContext('2d');

        const maintenanceRequestChart = new Chart(maintenanceRequestChartCtx, {
            type: 'pie',
            data: {
                labels: ['Total Tasks', 'Pending Tasks', 'Completed Tasks', 'Emergency Tasks'],
                datasets: [{
                    label: 'Maintenance Requests',
                    data: [<?php echo $totalTasks; ?>, <?php echo $pendingTasks; ?>, <?php echo $completedTasks; ?>, <?php echo $emergencyTasks; ?>],
                    backgroundColor: ['#a64b89', '#5c4b8a', '#f1eaf5', '#81589a'],
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw;
                                return label;
                            }
                        }
                    }
                }
            }
        });

        const residenceTaskChart = new Chart(residenceTaskChartCtx, {
            type: 'bar',
            data: {
                labels: ['Pending', 'Completed', 'Emergency'],
                datasets: [{
                    label: 'Tasks by Status',
                    data: [<?php echo $pendingTasks; ?>, <?php echo $completedTasks; ?>, <?php echo $emergencyTasks; ?>],
                    backgroundColor: ['#5c4b8a', '#a64b89', '#81589a'],
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Tasks'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw;
                                return label;
                            }
                        }
                    }
                }
            }
        });

        function confirmLogout() {
            return confirm('Are you sure you want to log out?');
        }


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
