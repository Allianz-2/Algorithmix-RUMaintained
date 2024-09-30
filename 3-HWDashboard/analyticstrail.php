<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); 




?>
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
        
        $results = mysqli_query($conn, "SELECT * FROM ticket WHERE Status= 'Confirmed'"); // Add this line to define $results

// Function to run a query and return results as an associative array
function runQuery($conn, $query, $params = []) {
    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param(str_repeat('i', count($params)), ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Query 1: Ticket Status
$statusQuery = "SELECT Status, COUNT(*) as Count 
                FROM ticket 
                WHERE HouseWardenID = ? 
                GROUP BY Status";
$statusData = runQuery($conn, $statusQuery, [$houseWardenID]);

// Query 2: Ticket Severity
$severityQuery = "SELECT Severity, COUNT(*) as Count 
                  FROM ticket 
                  WHERE HouseWardenID = ? 
                  GROUP BY Severity";
$severityData = runQuery($conn, $severityQuery, [$houseWardenID]);

// Query 3: Average Resolution Time
$resolutionQuery = "SELECT fc.CategoryName, AVG(DATEDIFF(t.DateResolved, t.DateCreated)) as AvgResolutionTime 
                    FROM ticket t
                    JOIN faultcategory fc ON t.CategoryID = fc.CategoryID
                    WHERE t.HouseWardenID = ? AND t.DateResolved IS NOT NULL 
                    GROUP BY fc.CategoryID";
$resolutionData = runQuery($conn, $resolutionQuery, [$houseWardenID]);

// Query 4: Open Tickets Trend
$trendQuery = "SELECT DATE(DateCreated) as Date, COUNT(*) as OpenTickets 
               FROM ticket 
               WHERE HouseWardenID = ? 
               AND DateCreated >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
               AND DateClosed IS NULL 
               GROUP BY DATE(DateCreated)";
$trendData = runQuery($conn, $trendQuery, [$houseWardenID]);

// Prepare data for charts
$statusLabels = array_column($statusData, 'Status');
$statusCounts = array_column($statusData, 'Count');

$severityLabels = array_column($severityData, 'Severity');
$severityCounts = array_column($severityData, 'Count');

$categoryNames = array_column($resolutionData, 'CategoryName');
$avgResolutionTimes = array_column($resolutionData, 'AvgResolutionTime');

$dates = array_column($trendData, 'Date');
$openTicketCounts = array_column($trendData, 'OpenTickets');

// Get Residence Name
$residenceQuery = "SELECT r.ResidenceName 
                   FROM housewarden hw
                   JOIN residence r ON hw.ResidenceID = r.ResidenceID
                   WHERE hw.HouseWardenID = ?";
$residenceData = runQuery($conn, $residenceQuery, [$houseWardenID]);
$residenceName = $residenceData[0]['ResidenceName'] ?? 'Unknown Residence';

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Warden Maintenance Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f0f0f0; }
        h1 { text-align: center; color: #333; }
        .chart-container { width: 45%; display: inline-block; margin: 10px; background-color: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <h1>Maintenance Dashboard for <?php echo htmlspecialchars($residenceName); ?></h1>
    <div class="chart-container">
        <canvas id="statusChart"></canvas>
    </div>
    <div class="chart-container">
        <canvas id="severityChart"></canvas>
    </div>
    <div class="chart-container">
        <canvas id="resolutionTimeChart"></canvas>
    </div>
    <div class="chart-container">
        <canvas id="openTicketsChart"></canvas>
    </div>

    <script>
    // Status Chart
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($statusLabels); ?>,
            datasets: [{
                data: <?php echo json_encode($statusCounts); ?>,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Current Ticket Status'
            }
        }
    });

    // Severity Chart
    new Chart(document.getElementById('severityChart'), {
        type: 'horizontalBar',
        data: {
            labels: <?php echo json_encode($severityLabels); ?>,
            datasets: [{
                label: 'Number of Tickets',
                data: <?php echo json_encode($severityCounts); ?>,
                backgroundColor: ['#4BC0C0', '#FFCE56', '#FF6384']
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Tickets by Severity'
            },
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    // Resolution Time Chart
    new Chart(document.getElementById('resolutionTimeChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($categoryNames); ?>,
            datasets: [{
                label: 'Average Resolution Time (Days)',
                data: <?php echo json_encode($avgResolutionTimes); ?>,
                backgroundColor: '#36A2EB'
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Average Resolution Time by Category'
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    // Open Tickets Chart
    new Chart(document.getElementById('openTicketsChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Open Tickets',
                data: <?php echo json_encode($openTicketCounts); ?>,
                borderColor: '#FF6384',
                fill: false
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Open Tickets Trend (Last 7 Days)'
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    </script>
</body>
</html>