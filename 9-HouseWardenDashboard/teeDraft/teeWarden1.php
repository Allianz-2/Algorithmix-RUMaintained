<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RU House Warden Dashboard</title>
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
        .stat-box i.icon {
            font-size: 2em;
            margin-bottom: 10px;
            color: #81589a;
        }
    </style>
</head>
<?php
        require_once('config.php');

        // Fetch all maintenance requests
        $query = "SELECT t.TicketID, t.Description, t.Status, t.Severity, t.DateCreated, u.First_name, u.Lastname, m.StaffName 
                  FROM ticket t
                  JOIN user u ON t.StudentID = u.UserID
                  JOIN maintenance_staff m ON t.StaffID = m.StaffID";  // Joining with maintenance_staff table
        $conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');
        $results = mysqli_query($conn, $query);
        
        // Fetch task statistics
        $totalRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as TotalRequests FROM ticket"))['TotalRequests'];
        $pendingRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as PendingRequests FROM ticket WHERE Status = 'Open'"))['PendingRequests'];
        $viewedRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as ViewedRequests FROM ticket WHERE Status = 'Viewed'"))['ViewedRequests']; 
        $completedRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as CompletedRequests FROM ticket WHERE Status = 'Resolved'"))['CompletedRequests'];
?>
<body>
    <nav id="sidebar" class="sidebar">
        <div class="logo">
            <span class="user-welcome">Welcome, </span><!-- Add PHP code for user name -->
            <a href="user-page"><i class="fas fa-user"></i></a>
        </div>
        <ul>
            <li class="active"><a href="#"><i class="fas fa-ticket-alt"></i>Ticket Requests</a></li>
            <li><a href="analytics.html"><i class="fas fa-chart-pie"></i>Analytics</a></li>
            <li><a href="ticket-progress.html"><i class="fas fa-tasks"></i>Ticket Progress</a></li>
            <li><a href="notifications.html"><i class="fas fa-bell"></i>Notifications</a></li>
            <li><a href="lodge-ticket.html"><i class="fas fa-plus-circle"></i>Lodge Ticket</a></li>
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
                <strong>House Warden Dashboard</strong>
            </div>
            <div class="logo">
                <img src="Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="RU Maintained Logo">
            </div>
        </header>

        <div class="content">
            <h3>Ticket Requests</h3>
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

<div class="stats">
    <div class="stat-box">
        <i class="fas fa-tasks icon"></i>
        <h4>Total Requests</h4>
        <p id="total-requests"><?php echo $totalRequests; ?></p>
    </div>
    <div class="stat-box">
        <i class="fas fa-clock icon"></i>
        <h4>Pending Requests</h4>
        <p id="pending-requests"><?php echo $pendingRequests; ?></p> 
    </div>
    <div class="stat-box">
        <i class="fas fa-eye icon"></i>
        <h4>Viewed Requests</h4>
        <p id="viewed-requests"><?php echo $viewedRequests; ?></p>
    </div>
    <div class="stat-box">
        <i class="fas fa-check-circle icon"></i>
        <h4>Completed Requests</h4>
        <p id="completed-requests"><?php echo $completedRequests; ?></p>
    </div>
</div>

            <h1>Ticket Requests</h1>

            <table>
                <tr>
                    <th>Ticket ID</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Severity</th>
                    <th>Date Created</th>
                    <th>Submitted By</th>
                </tr>
                <!-- Sample PHP Code to Fetch and Display Tickets -->
                <?php 
                        require_once('config.php');

                        // Fetch all maintenance requests
                        $query = "SELECT t.TicketID, t.Description, t.Status, t.Severity, t.DateCreated, u.First_name, u.Lastname 
                                  FROM ticket t
                                  JOIN user u ON t.StudentID = u.UserID";
                        $conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');
                        $results = mysqli_query($conn, $query);
                // Assuming you've fetched ticket data from your database
                while ($row = mysqli_fetch_assoc($results)): ?>
                <tr>
                    <td><?php echo $row['TicketID']; ?></td>
                    <td><?php echo $row['Description']; ?></td>
                    <td><?php echo $row['Status']; ?></td>
                    <td><?php echo $row['Severity']; ?></td>
                    <td><?php echo $row['DateCreated']; ?></td>
                    <td><?php echo $row['First_name'] . " " . $row['Lastname']; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </main>

    <script>
        const ctx = document.getElementById('maintenanceRequestChart').getContext('2d');
        const maintenanceRequestChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Requests', 'Pending Requests', 'Viewed Requests', 'Completed Requests'],
                datasets: [{
                    label: 'Ticket Requests',
                    data: [<?php echo $totalRequests; ?>, <?php echo $pendingRequests; ?>, <?php echo $viewedRequests; ?>, <?php echo $completedRequests; ?>],
                    backgroundColor: [
                        'rgba(130, 118, 159, 0.5)',
                        'rgba(130, 118, 159, 0.7)',
                        'rgba(130, 118, 159, 0.9)',
                        'rgba(130, 118, 159, 1)'
                    ],
                    borderColor: [
                        'rgba(130, 118, 159, 1)',
                        'rgba(130, 118, 159, 1)',
                        'rgba(130, 118, 159, 1)',
                        'rgba(130, 118, 159, 1)'
                    ],
                    borderWidth: 1
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
