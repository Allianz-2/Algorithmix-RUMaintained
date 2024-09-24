<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RU Maintained Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="Dashboard.css">
</head>
<body>
    <nav id="sidebar" class="sidebar">
        <div class="logo">
            <span class="user-welcome">Welcome, </span><!-- Add PHP code here for user name -->
            <a href="user page"><i class="fas fa-user"></i></a> 
        </div>
        <ul>
            <li class="active"><a href="#"><i class="fas fa-tools"></i>Maintenance Requests</a></li>
            <li><a href="TaskAssignment.html"><i class="fas fa-clipboard-list"></i>Task Assignment</a></li>
            <li><a href="inventorymanagement.html"><i class="fas fa-wrench"></i>Inventory Management</a></li>
            <li><a href="performance analytics.html"><i class="fas fa-chart-line"></i>Performance Analytics</a></li>
            <li><a href="MaintenanceNotifications.html"><i class="fas fa-bell"></i>Notifications</a></li>
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
                <img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="rumaintained logo">
            </div>
        </header>
        
        <?php 
        require_once('config.php');

        // Fetch all maintenance requests
        $query = "SELECT t.TicketID, t.Description, t.Status, t.Severity, t.DateCreated, u.First_name, u.Lastname 
                  FROM ticket t
                  JOIN user u ON t.StudentID = u.UserID";

        $conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');
        $results = mysqli_query($conn, $query);

        // Fetch total tasks, pending tasks, completed tasks, and emergency tasks
        $totalTasksQuery = "SELECT COUNT(*) as TotalTasks FROM ticket";
        $totalTasksResult = mysqli_query($conn, $totalTasksQuery);
        $totalTasks = mysqli_fetch_assoc($totalTasksResult)['TotalTasks'];

        $pendingTasksQuery = "SELECT COUNT(*) as PendingTasks FROM ticket WHERE Status = 'Open'";
        $pendingTasksResult = mysqli_query($conn, $pendingTasksQuery);
        $pendingTasks = mysqli_fetch_assoc($pendingTasksResult)['PendingTasks'];

        $completedTasksQuery = "SELECT COUNT(*) as CompletedTasks FROM ticket WHERE Status = 'Resolved'";
        $completedTasksResult = mysqli_query($conn, $completedTasksQuery);
        $completedTasks = mysqli_fetch_assoc($completedTasksResult)['CompletedTasks'];

        $emergencyTasksQuery = "SELECT COUNT(*) as EmergencyTasks FROM ticket WHERE Severity = 'high'";
        $emergencyTasksResult = mysqli_query($conn, $emergencyTasksQuery);
        $emergencyTasks = mysqli_fetch_assoc($emergencyTasksResult)['EmergencyTasks'];
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
                        <option value="Month">Last Month</option>
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
        <h4>Total Tasks</h4>
        <p id="total-tasks"><?php echo $totalTasks; ?></p>
    </div>
    <div class="stat-box">
        <i class="fas fa-clock icon"></i>
        <h4>Pending Tasks</h4>
        <p id="pending-tasks"><?php echo $pendingTasks; ?></p>
    </div>
    <div class="stat-box">
        <i class="fas fa-check-circle icon"></i>
        <h4>Completed Tasks</h4>
        <p id="completed-tasks"><?php echo $completedTasks; ?></p>
    </div>
    <div class="stat-box">
        <i class="fas fa-exclamation-triangle icon"></i>
        <h4>Emergency Tasks</h4>
        <p id="emergency-tasks"><?php echo $emergencyTasks; ?></p>
    </div>
</div>

            <h1>Maintenance Requests</h1>

            <table>
                <tr>
                    <th>Ticket ID</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Severity</th>
                    <th>Date Created</th>
                    <th>Student</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($results)): ?>
                <tr>
                    <td><?php echo $row['TicketID']; ?></td>
                    <td><?php echo $row['Description']; ?></td>
                    <td><?php echo $row['Status']; ?></td>
                    <td><?php echo $row['Severity']; ?></td>
                    <td><?php echo $row['DateCreated']; ?></td>
                    <td><?php echo $row['First_name'] . ' ' . $row['Lastname']; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
            <?php mysqli_close($conn); ?>
        </div>

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
        <style>
            body { font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 20px; }
            h1 { text-align: center; color: #5c4b8a; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 5px; overflow: hidden; }
            th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
            th { background-color: #5c4b8a; color: #fff; }
            .filters, .stats { display: flex; justify-content: space-around; margin-top: 20px; }
            .filter-group { flex: 1; }
            .stat-box { background-color: #fff; padding: 20px; border-radius: 5px; text-align: center; margin: 10px; flex: 1; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
            .charts { display: flex; justify-content: space-around; margin-top: 20px; }

            .stats {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
}

.stat-box {
    background-color: #E6E6FA; /* Light purple background */
    color: #5c4b8a; /* Dark purple text */
    border-radius: 10px; /* Smooth corners */
    padding: 20px;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15); /* Deeper shadow for depth */
    text-align: center; /* Center text */
    flex: 1; /* Equal space distribution */
    margin: 10px; /* Space between boxes */
    position: relative; /* For icon positioning */
    transition: transform 0.2s; /* Smooth hover effect */
}

.stat-box:hover {
    transform: translateY(-5px); /* Lift effect on hover */
    box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.2); /* Darker shadow on hover */
}

.stat-box h4 {
    margin: 10px 0; /* Spacing between title and value */
    font-size: 1.2em; /* Increase font size */
}

.stat-box p {
    font-size: 2em; /* Larger number display */
    font-weight: bold; /* Bold text for emphasis */
}

/* Icon styles */
.icon {
    font-size: 40px; /* Larger icon size */
    color: #5c4b8a; /* Icon color */
    position: absolute; /* Positioning */
    top: 10px; /* Position from the top */
    right: 10px; /* Position from the right */
}

/* Optional Background Pattern */
.stat-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.1); /* Light overlay */
    border-radius: 10px; /* Match the corners */
    z-index: 0; /* Behind content */
}

.stat-box > * {
    position: relative; /* Ensures content is above the background */
    z-index: 1; /* Ensure text is above the background */
}
        </style>
    </main>
</body>
</html>