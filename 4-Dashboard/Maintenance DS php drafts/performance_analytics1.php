<?php 
define('SERVERNAME', 'IS3-DEV.ICT.RU.AC.ZA');
define('USERNAME', 'Algorithmix');
define('PASSWORD', 'U3fuC7P5');
define('DATABASE', 'Algorithmix');

try {
    // Establish database connection
    $conn = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DATABASE, USERNAME, PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Fetching the maintenance fault stats per semester per residence
    function getFaultStatsPerSemester($conn) {
        $query = "
            SELECT R.ResName, 
                   COUNT(T.TicketID) AS FaultCount, 
                   CASE 
                       WHEN MONTH(T.DateCreated) BETWEEN 1 AND 6 THEN 'First Semester' 
                       ELSE 'Second Semester' 
                   END AS Semester
            FROM ticket T
            JOIN residence R ON T.ResidenceID = R.ResidenceID
            GROUP BY R.ResName, Semester
        ";
        return $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetching fault progress
    function getFaultProgress($conn) {
        $query = "
            SELECT Status, COUNT(TicketID) AS Count
            FROM ticket
            GROUP BY Status
        ";
        return $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetching turnaround time stats
    function getTurnaroundTimeStats($conn) {
        $query = "
            SELECT AVG(TIMESTAMPDIFF(HOUR, DateCreated, DateResolved)) AS AvgTurnaroundTime
            FROM ticket
            WHERE Status = 'Resolved'
        ";
        return $conn->query($query)->fetch(PDO::FETCH_ASSOC);
    }

    // Fetching maintenance categories stats
    function getMaintenanceCategoryStats($conn) {
        $query = "
            SELECT C.CategoryName, COUNT(T.TicketID) AS Count
            FROM ticket T
            JOIN faultcategory C ON T.CategoryID = C.CategoryID
            GROUP BY C.CategoryName
        ";
        return $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetching monthly fault trends
    function getMonthlyFaultTrends($conn) {
        $query = "
            SELECT MONTH(DateCreated) AS Month, 
                   COUNT(TicketID) AS FaultCount
            FROM ticket
            WHERE Status = 'Resolved'
            GROUP BY Month
            ORDER BY Month
        ";
        return $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetching the data
    $faultStatsPerSemester = getFaultStatsPerSemester($conn);
    $faultProgress = getFaultProgress($conn);
    $turnaroundTimeStats = getTurnaroundTimeStats($conn);
    $maintenanceCategoryStats = getMaintenanceCategoryStats($conn);
    $monthlyFaultTrends = getMonthlyFaultTrends($conn);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Staff Dashboard - Performance Analytics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .chart-container {
            margin-bottom: 40px;
        }
        canvas {
            background-color: #fff;
            border: 1px solid #ccc;
        }
        h2 {
            color: #333;
        }
    </style>
</head>
<body>

    <h1>Maintenance Staff Dashboard - Performance Analytics</h1>

    <!-- Fault Stats Per Semester Per Residence -->
    <div class="chart-container">
        <h2>Fault Stats Per Semester Per Residence</h2>
        <canvas id="faultStatsPerSemesterChart"></canvas>
    </div>

    <!-- Fault Progress -->
    <div class="chart-container">
        <h2>Fault Progress</h2>
        <canvas id="faultProgressChart"></canvas>
    </div>

    <!-- Turnaround Time Stats -->
    <div class="chart-container">
        <h2>Average Turnaround Time</h2>
        <p><?php echo round($turnaroundTimeStats['AvgTurnaroundTime'], 2); ?> hours</p>
    </div>

    <!-- Maintenance Categories Stats -->
    <div class="chart-container">
        <h2>Maintenance Categories Stats</h2>
        <canvas id="maintenanceCategoryChart"></canvas>
    </div>

    <!-- Monthly Fault Trends -->
    <div class="chart-container">
        <h2>Monthly Fault Trends</h2>
        <canvas id="monthlyFaultTrendsChart"></canvas>
    </div>

    <script>
        // Fault Stats Per Semester Per Residence Chart
        const faultStatsPerSemesterData = <?php echo json_encode($faultStatsPerSemester); ?>;
        const faultStatsLabels = faultStatsPerSemesterData.map(data => data.ResName);
        const faultStatsCounts = faultStatsPerSemesterData.map(data => data.FaultCount);

        const faultStatsCtx = document.getElementById('faultStatsPerSemesterChart').getContext('2d');
        new Chart(faultStatsCtx, {
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

        // Fault Progress Chart
        const faultProgressData = <?php echo json_encode($faultProgress); ?>;
        const progressLabels = faultProgressData.map(data => data.Status);
        const progressCounts = faultProgressData.map(data => data.Count);

        const faultProgressCtx = document.getElementById('faultProgressChart').getContext('2d');
        new Chart(faultProgressCtx, {
            type: 'doughnut',
            data: {
                labels: progressLabels,
                datasets: [{
                    label: 'Fault Progress',
                    data: progressCounts,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });

        // Maintenance Categories Stats Chart
        const maintenanceCategoryData = <?php echo json_encode($maintenanceCategoryStats); ?>;
        const categoryLabels = maintenanceCategoryData.map(data => data.CategoryName);
        const categoryCounts = maintenanceCategoryData.map(data => data.Count);

        const maintenanceCategoryCtx = document.getElementById('maintenanceCategoryChart').getContext('2d');
        new Chart(maintenanceCategoryCtx, {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Number of Faults',
                    data: categoryCounts,
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

        // Monthly Fault Trends Chart
        const monthlyFaultTrendsData = <?php echo json_encode($monthlyFaultTrends); ?>;
        const monthlyLabels = monthlyFaultTrendsData.map(data => data.Month);
        const monthlyFaultCounts = monthlyFaultTrendsData.map(data => data.FaultCount);

        const monthlyFaultTrendsCtx = document.getElementById('monthlyFaultTrendsChart').getContext('2d');
        new Chart(monthlyFaultTrendsCtx, {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Fault Count',
                    data: monthlyFaultCounts,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: true
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
    </script>

</body>
</html>