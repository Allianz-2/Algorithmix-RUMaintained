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

h1 {
    text-align: center; 
    color: #81589a;
    margin: 0; 
    width: 100%; /* Ensures it occupies the full width */
}

        .content {
    padding: 20px;
}


.charts-row {
    display: flex;
    flex-wrap: wrap; /* Allows wrapping for smaller screens */
    justify-content: space-between; /* Distributes space between items */
}

.chart-column {
    flex: 1 1 30%; /* Allows the columns to grow and shrink, with a minimum width of 30% */
    margin: 10px; /* Adds some space between columns */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Optional: adds a slight shadow for better visibility */
    padding: 10px; /* Optional: adds some padding inside the column */
    background-color: #fff; /* Optional: sets background color for better visibility */
    border-radius: 8px; /* Optional: rounds the corners of the column */
}

/* Ensuring canvas elements are responsive */
canvas {
    width: 100% !important; /* Forces canvas to take full width of column */
    height: auto !important; /* Keeps the height proportional */
}

.chart-column h2 {
    text-align: center; /* Centers the text horizontally */
    margin: 0; /* Removes default margin (optional) */
}
    </style>
</head>

<body>
    <nav id="sidebar" class="sidebar">
        <div class="logo">
            <span class="user-welcome">Welcome, </span><!-- Add PHP code here for user name -->
            <a href="user page"><i class="fas fa-user"></i></a> 
        </div>
        <ul>
            <li><a href="#"><i class="fas fa-tools"></i>Maintenance Requests</a></li>
            <li><a href="TaskAssignment.html"><i class="fas fa-clipboard-list"></i>Task Assignment</a></li>
            <li><a href="inventorymanagement.html"><i class="fas fa-wrench"></i>Inventory Management</a></li>
            <li class="active"><a href="performance analytics.html"><i class="fas fa-chart-line"></i>Performance Analytics</a></li>
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
        // Database connection parameters
        define('SERVERNAME', 'IS3-DEV.ICT.RU.AC.ZA');
        define('USERNAME', 'Algorithmix');
        define('PASSWORD', 'U3fuC7P5');
        define('DATABASE', 'Algorithmix');

        try {
            // Create a new PDO instance
            $conn = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DATABASE, USERNAME, PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit();
        }

        // Fetch maintenance fault stats per semester per residence
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
            return $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
        }

        // Fetch maintenance fault progress
        function getMaintenanceFaultProgress($conn) {
            $query = "
                SELECT COUNT(TicketID) AS TotalTickets,
                       SUM(CASE WHEN Status = 'Resolved' THEN 1 ELSE 0 END) AS ResolvedTickets,
                       SUM(CASE WHEN Status = 'In Progress' THEN 1 ELSE 0 END) AS InProgressTickets
                FROM ticket
            ";
            return $conn->query($query)->fetch(PDO::FETCH_ASSOC);
        }

        // Fetch turnaround time stats
        function getTurnaroundTimeStats($conn) {
            $query = "
                SELECT AVG(TIMESTAMPDIFF(HOUR, DateCreated, DateResolved)) AS AvgTurnaroundTime
                FROM ticket
                WHERE Status = 'Resolved'
            ";
            return $conn->query($query)->fetch(PDO::FETCH_ASSOC);
        }

        // Fetch maintenance category stats
        function getMaintenanceCategoryStats($conn) {
            $query = "
                SELECT fc.CategoryName, COUNT(t.TicketID) AS FaultCount
                FROM ticket t
                JOIN faultcategory fc ON t.CategoryID = fc.CategoryID
                GROUP BY fc.CategoryName
            ";
            return $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
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
            return $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
        }

        // Prepare data for graphs
        $faultStatsPerSemester = getMaintenanceFaultStatsPerSemester($conn);
        $faultProgress = getMaintenanceFaultProgress($conn);
        $turnaroundTime = getTurnaroundTimeStats($conn);
        $categoryStats = getMaintenanceCategoryStats($conn);
        $complaintHistory = getComplaintCategoryHistory($conn);
        ?>
        
        <div class="content">
    <h1>Performance Analytics</h1>

    <div class="charts-row">
        <div class="chart-column">
            <h2>Semester Maintenance Fault Stats by Res</h2>
            <canvas id="faultStatsPerSemesterChart"></canvas>
        </div>
        <div class="chart-column">
            <h2>Maintenance Fault Progress</h2>
            <canvas id="faultProgressChart"></canvas>
        </div>
        <div class="chart-column">
            <h2>Average Turnaround Time (in Hours)</h2>
            <canvas id="turnaroundTimeChart"></canvas>
        </div>
        <div class="chart-column">
            <h2>Maintenance Fault Stats by Category</h2>
            <canvas id="categoryStatsChart"></canvas>
        </div>
        <div class="chart-column">
            <h2>History of Complaint Categories</h2>
            <canvas id="complaintHistoryChart"></canvas>
        </div>
    </div>
</div>

    </main>

    <script>
        // Prepare data for charts

        // Fault Stats per Semester
        const faultStatsData = <?php echo json_encode($faultStatsPerSemester); ?>;
        const faultStatsLabels = faultStatsData.map(data => `${data.ResName} - ${data.Year} ${data.Semester}`);
        const faultStatsCounts = faultStatsData.map(data => data.FaultCount);

        const faultStatsPerSemesterCtx = document.getElementById('faultStatsPerSemesterChart').getContext('2d');
        new Chart(faultStatsPerSemesterCtx, {
            type: 'bar',
            data: {
                labels: faultStatsLabels,
                datasets: [{
                    label: 'Fault Count',
                    data: faultStatsCounts,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
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

        // Fault Progress
        const faultProgressData = <?php echo json_encode($faultProgress); ?>;
        const faultProgressLabels = ['Total Tickets', 'Resolved Tickets', 'In Progress Tickets'];
        const faultProgressCounts = [faultProgressData.TotalTickets, faultProgressData.ResolvedTickets, faultProgressData.InProgressTickets];

        const faultProgressCtx = document.getElementById('faultProgressChart').getContext('2d');
        new Chart(faultProgressCtx, {
            type: 'doughnut',
            data: {
                labels: faultProgressLabels,
                datasets: [{
                    label: 'Fault Progress',
                    data: faultProgressCounts,
                    backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)'],
                    borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
            }
        });

        // Average Turnaround Time
        const turnaroundTimeData = <?php echo json_encode($turnaroundTime); ?>;
        const avgTurnaroundTime = turnaroundTimeData.AvgTurnaroundTime;

        const turnaroundTimeCtx = document.getElementById('turnaroundTimeChart').getContext('2d');
        new Chart(turnaroundTimeCtx, {
            type: 'bar',
            data: {
                labels: ['Average Turnaround Time'],
                datasets: [{
                    label: 'Hours',
                    data: [avgTurnaroundTime],
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
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
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    borderColor: 'rgba(255, 159, 64, 1)',
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
                    borderColor: 'rgba(75, 192, 192, 1)',
                    fill: false,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
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

        // Sidebar toggle
        document.getElementById('hamburger-icon').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
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
    </script>
    <style>

.content {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    padding: 10px;
}

.column {
    flex: 1 1 45%;
    box-sizing: border-box;
    padding: 10px;
    background-color: #f9f9f9;
    border-radius: 8px;
}
    </style>
</body>
</html>
