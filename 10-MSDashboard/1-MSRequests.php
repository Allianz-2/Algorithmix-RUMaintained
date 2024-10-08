<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); 
    include '../11-DashboardFunctionality\1-PermissionRequests.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Staff Dashboard</title>
    <link rel="icon" href="../Images\General\favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
            max-width: 600px;
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


       <?php
        //Database connection parameters
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

        // Fetch maintenance fault stats per semester per residence
        // function getMaintenanceFaultStatsPerSemester($conn) {
        //     $query = "
        //         SELECT r.ResName, 
        //                COUNT(t.TicketID) AS FaultCount, 
        //                YEAR(t.DateCreated) AS Year,
        //                CASE 
        //                    WHEN MONTH(t.DateCreated) BETWEEN 1 AND 6 THEN 'Semester 1'
        //                    ELSE 'Semester 2'
        //                END AS Semester
        //         FROM ticket t
        //         JOIN residence r ON t.ResidenceID = r.ResidenceID
        //         WHERE t.Status = 'Resolved'
        //         GROUP BY r.ResName, Year, Semester
        //  ";
        //     return $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
        // }



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
        }
        
    



        // Fetch maintenance fault progress


        function getMaintenanceFaultProgress($conn) {
            $query = "
                SELECT COUNT(TicketID) AS TotalTickets,
                       SUM(CASE WHEN Status = 'Resolved' THEN 1 ELSE 0 END) AS ResolvedTickets,
                       SUM(CASE WHEN Status = 'In Progress' THEN 1 ELSE 0 END) AS InProgressTickets
                FROM ticket
            ";
            $result = mysqli_query($conn, $query);
            if (!$result) {
                die('Error executing query: ' . mysqli_error($conn));
            }
            return mysqli_fetch_assoc($result);
        }

        // Fetch turnaround time stats

        function getTurnaroundTimeStats($conn) {
            $query = "
                SELECT AVG(TIMESTAMPDIFF(HOUR, DateCreated, DateResolved)) AS AvgTurnaroundTime
                FROM ticket
                WHERE Status = 'Resolved'
            ";
            $result = mysqli_query($conn, $query);
            if (!$result) {
                die('Error executing query: ' . mysqli_error($conn));
            }
            return mysqli_fetch_assoc($result);
        }

        // Fetch maintenance category stats

        function getMaintenanceCategoryStats($conn) {
            $query = "
                SELECT fc.CategoryName, COUNT(t.TicketID) AS FaultCount
                FROM ticket t
                JOIN faultcategory fc ON t.CategoryID = fc.CategoryID
                GROUP BY fc.CategoryName
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
        }

        // Fetch history of complaint categories

        function getComplaintCategoryHistory($conn) {
            $query = "
                SELECT YEAR(DateCreated) AS Year, 
                       fc.CategoryName, 
                       COUNT(t.TicketID) AS FaultCount
                FROM ticket t
                JOIN faultcategory fc ON t.CategoryID = fc.CategoryID
                GROUP BY Year, fc.CategoryName
                ORDER BY Year
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
        }




        
        // Prepare data for graphs
        $faultStatsPerSemester = getMaintenanceFaultStatsPerSemester($conn);
        $faultProgress = getMaintenanceFaultProgress($conn);
        $turnaroundTime = getTurnaroundTimeStats($conn);
        $categoryStats = getMaintenanceCategoryStats($conn);
        $complaintHistory = getComplaintCategoryHistory($conn);
        ?>
<body>
    <nav id="sidebar" class="sidebar">
        <div class="logo">
        <span class="user-welcome">Welcome, <?php echo $_SESSION['Firstname']; ?></span> <!--  I THINK -->
            <a href="..\6-UserProfileManagementPage\4-ProfileMS.php"><i class="fas fa-user"></i></a>
        </div>
        <ul>
            <li><a href="../1-GeneralPages\1-Home.php"><i class="fas fa-home"></i>Home</a></li>
            <li class="active"><a href="1-MSRequests.php"><i class="fas fa-tasks"></i> Requests</a></li>
            <li><a href="2-MSAssignedTasks.php"><i class="fas fa-clipboard-list"></i>Task Approvals</a></li>
            <li><a href="3-MSAnalytics.php"><i class="fas fa-chart-bar"></i>Analytics</a></li>
            <li><a href="4-MSNotifications.php"><i class="fas fa-bell"></i>Notifications</a></li>
            <li><a href="5-MSHelp.php"><i class="fas fa-info-circle"></i>Help and Support</a></li>
        </ul>
        <div class="sidebar-footer">
            <p><a href="..\6-UserProfileManagementPage\4-ProfileMS.php"><i class="fas fa-cog"></i> Settings</a></p>
            <p><a href="..\5-UserSignInandRegistration\15-Logout.php" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</a></p>
        </div>
    </nav>
    <main>
        <header>
            <div class="header-left">
                <div id="hamburger-icon" class="hamburger-icon"><i class="fas fa-bars"></i></div>
                <strong>Maintenance Staff Dashboard</strong>
            </div>
            <div class="logo">
            <a href="../1-GeneralPages/1-Home.php">
                <img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="RU Maintained Logo">
            </a>
            </div>
        </header>
        <div class="content">
            <h2>Requests</h2>
            <!-- <div class="filters"> -->
                <!-- <div class="filter-group">
                    <label for="date-filter">Date Range</label>
                    <select id="date-filter">
                        <option>Last 7 Days</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="today">Today</option>
                        <option value="2 weeks">Last 2 weeks</option>
                        <option value="Month">Last Month</option>
                        <option value="3 months">Last 3 Months</option>
                    </select>
                </div> -->
                <!-- <div class="filter-group">
                    <label for="residence-filter">Residence</label> 
                    <select id="residence-filter">
                        <option>Chris Hani House</option>
                    </select>
                </div>  -->
                <!-- <div class="filter-group">
                    <label for="severity-filter">Severity</label>
                    <select id="severity-filter">
                        <option value="High">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                        <option value="emergency">Emergency</option>
                    </select>
                </div> -->
                <!-- <div class="filter-group">
                    <label for="category-filter">Category</label>
                    <select id="category-filter">
                        <option>Any</option>
                        <option value="Plumbing">Plumbing</option>
                        <option value="electrical">Electrical</option>
                        <option value="roofing">Roofing</option>
                        <option value="broken and repairs">Repairs and breakage</option>
                        <option value="other">Other</option>
                    </select>
                </div> -->
                <!-- <div class="filter-group">
                    <label for="status-filter">Status</label>
                    <select id="status-filter">
                        <option>Any</option>
                        <option value="active">Active</option>
                        <option value="Pending">Pending</option>
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
        
        $results = mysqli_query($conn, "SELECT * FROM ticket ORDER BY DateCreated DESC"); // Add this line to define $results
  

        $totalTasks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as TotalTasks FROM ticket"))['TotalTasks'];
        $pendingTasks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as PendingTasks FROM ticket WHERE Status = 'Open'"))['PendingTasks'];
        $completedTasks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as CompletedTasks FROM ticket WHERE Status = 'Resolved'"))['CompletedTasks'];
        $emergencyTasks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as EmergencyTasks FROM ticket WHERE Severity = 'high'"))['EmergencyTasks'];
        ?>




<div class="charts">
                <canvas id="maintenanceRequestChart"></canvas>
                <canvas id="residenceTaskChart"></canvas>
            </div>












        <div class="content">
           
            <div class="charts">
                <!-- Google Chart containers -->
                <!-- <div id="requestChart" class="chart-box"></div> -->
                <!-- <div id="residenceChart" class="chart-box"></div> -->
            </div>

            <div class="stats">
                <div class="stat-box">
                    <i class=""></i>
                    <h4>Total Tasks</h4>
                    <p id="total-tasks"><?php echo $totalTasks; ?></p>
                </div>
                <div class="stat-box">
                    <i class=""></i>
                    <h4>Pending Tasks</h4>
                    <p id="pending-tasks"><?php echo $pendingTasks; ?></p>
                </div>
                <div class="stat-box">
                    <i class=""></i>
                    <h4>Completed Tasks</h4>
                    <p id="completed-tasks"><?php echo $completedTasks; ?></p>
                </div>
                <div class="stat-box">
                    <i class=""></i>
                    <h4>Emergency Tasks</h4>
                    <p id="emergency-tasks"><?php echo $emergencyTasks; ?></p>
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
                        <?php if ($row['Status'] === 'Requisitioned'): ?>

                    <tr>
                        <td><?php echo $row['TicketID']; ?></td>
                        <td><?php echo $row['Description']; ?></td>
                        <td><?php echo $row['Status']; ?></td>
                        <td><?php echo $row['Severity']; ?></td>
                        <td><?php echo $row['DateCreated']; ?></td>
                        <td><?php echo $row['StudentID']; ?></td>
                    </tr>
                    <?php endif; ?>

                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
    </main>


    
<script>
    // Add chart.js scripts here for rendering charts
        const ctx = document.getElementById('maintenanceRequestChart').getContext('2d');
        const maintenanceRequestChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total', 'Pending', 'Completed', 'Emergency'],
                datasets: [{
                    label: 'Maintenance Requests',
                    data: [<?php echo $totalTasks; ?>, <?php echo $pendingTasks; ?>, <?php echo $completedTasks; ?>, <?php echo $emergencyTasks; ?>],
                    backgroundColor: [
                        'rgba(92, 75, 138, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
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
