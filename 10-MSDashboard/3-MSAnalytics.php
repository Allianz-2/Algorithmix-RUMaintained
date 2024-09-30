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
            <span class="user-welcome">Welcome, </span>
            <a href="Z:\Algorithmix-RUMaintained\6-UserProfileManagementPage\2-ProfileHW.php"><i class="fas fa-user"></i></a>
        </div>
        <ul>
        <li><a href="../1-GeneralPages\1-Home.php"><i class="fas fa-home"></i>Home</a></li>    
        <li><a href="1-MSRequests.php"><i class="fas fa-tasks"></i> Requests</a></li>
            <li><a href="2-MD_TaskAssignment.php"><i class="fas fa-clipboard-list"></i>Task Assignment</a></li>
            <li  class="active"><a href="3-MSAnalytics.php"><i class="fas fa-chart-bar"></i>Analytics</a></li>
            <li><a href="4-MSNotifications.php"><i class="fas fa-bell"></i>Notifications</a></li>

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
            <div class="logo"><img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="rumaintained logo"></div>
        </header>

        <div class="content">
            <h2>Analytics</h2>
            <div class="filters">
                <!-- Filters for date, residence, severity, etc. -->
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
                </div> -->
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
                </div> -->
            </div>

            <div class="charts-container">
            <div class="chart-box">
                <h3>Semester Maintenance Fault Stats by Res</h3>
                <canvas id="faultStatsPerSemesterChart" style="width: 100%; height: 300px;"></canvas>
            </div>
            <div class="chart-box">
                <h3>Maintenance Fault Progress</h3>
                <canvas id="faultProgressChart" style="width: 100%; height: 300px;"></canvas>
            </div>
            <div class="chart-box">
                <h3>Maintenance Fault Stats by Category</h3>
                <canvas id="categoryStatsChart" style="width: 100%; height: 300px;"></canvas>
            </div>
            <div class="chart-box">
                <h3>History of Complaint Categories</h3>
                <canvas id="complaintHistoryChart" style="width: 100%; height: 300px;"></canvas>
            </div>

            <!-- <div class="chart-box">
                <h3>Average Turnaround Time (in Hours)</h3>
                <canvas id="turnaroundTimeChart" style="width: 100%; height: 300px;"></canvas>
            </div> -->


            <div>
                <h3>Average Turnaround Time (in Hours)</h3>
                <strong><?php echo $turnaroundTime['AvgTurnaroundTime']; ?></strong>
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
    data: {
        labels: faultStatsLabels,
        datasets: [{
            label: 'Fault Count',
            data: faultStatsCounts,
            backgroundColor: customColor,  // Set background color to #81589a
            borderColor: customColor,      // Set border color to #81589a
            borderWidth: 0,                // Set the border width for the bars
            fontName: 'Arial'
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




// Fault Progress
const faultProgressData = <?php echo json_encode($faultProgress); ?>;
const faultProgressLabels = ['Total Tickets', 'Resolved Tickets', 'In Progress Tickets'];
const faultProgressCounts = [faultProgressData.TotalTickets, faultProgressData.ResolvedTickets, faultProgressData.InProgressTickets];
const customColors = ['#4E73DF', '#8a9dd7', '#b3c6f0'];  // Light shades of purple

const faultProgressCtx = document.getElementById('faultProgressChart').getContext('2d');
new Chart(faultProgressCtx, {
    type: 'doughnut',
    data: {
        labels: faultProgressLabels,
        datasets: [{
            label: 'Fault Progress',
            data: faultProgressCounts,
            backgroundColor: customColors,  // Set background colors using light shades of purple
            borderColor: customColors,      // Set border colors to match the background
            borderWidth: 0,                 // Set border width
            fontName: 'Arial'
        }]
    },
    options: {
        responsive: false,
    }
});







// Maintenance Fault Stats by Category
const categoryStatsData = <?php echo json_encode($categoryStats); ?>;
const categoryStatsLabels = categoryStatsData.map(data => data.CategoryName);
const categoryStatsCounts = categoryStatsData.map(data => data.FaultCount);

const categoryStatsCtx = document.getElementById('categoryStatsChart').getContext('2d');
new Chart(categoryStatsCtx, {
    type: 'bar',
    data: {
        labels: categoryStatsLabels,
        datasets: [{
            label: 'Fault Count',
            data: categoryStatsCounts,
            backgroundColor: customColor,  // Apply background color for bars
            borderColor: customColor,      // Apply border color for bars
            borderWidth: 0,                // Set bar border width
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





        // History of Complaint Categories
            const complaintHistoryData = <?php echo json_encode($complaintHistory); ?>;
            const complaintHistoryLabels = complaintHistoryData.map(data => `${data.Year} - ${data.CategoryName}`);
            const complaintHistoryCounts = complaintHistoryData.map(data => data.FaultCount);


            const complaintHistoryCtx = document.getElementById('complaintHistoryChart').getContext('2d');
            new Chart(complaintHistoryCtx, {
                type: 'line',
                data: {
                    labels: complaintHistoryLabels,
                    datasets: [{
                        label: 'Complaint Count',
                        data: complaintHistoryCounts,
                        borderColor: customColor,      // Set line color
                        backgroundColor: customColor,  // Set background color (for points)
                        pointBackgroundColor: customColor,  // Set point color
                        fill: false,                   // No area fill below the line
                        tension: 0.1,                  // Smooth curve
                        borderWidth: 2                 // Line thickness
                    }]
                },
                options: {
                    responsive: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

        // Logout confirmation
        function confirmLogout() {
            return confirm("Are you sure you want to log out?");
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
