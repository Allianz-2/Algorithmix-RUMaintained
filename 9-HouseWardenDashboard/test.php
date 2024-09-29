<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
}

.chart-container {
    display: flex;
    flex-wrap: wrap; /* Allows wrapping to the next line */
    justify-content: space-around; /* Distributes space evenly */
}

.chart-container h2 {
    flex-basis: 100%; /* Ensures headings are on their own line */
    text-align: center; /* Centers headings */
    margin-bottom: 10px; /* Space below headings */
}

.chart {
    flex: 1 1 calc(45% - 20px); /* Adjust width of each chart */
    margin: 10px; /* Space around each chart */
    max-width: 400px; /* Limit max width */
}

canvas {
    max-width: 100%; /* Responsive canvas */
    height: 250px; /* Fixed height for uniformity */
    border: 1px solid #ddd; /* Optional border for better visibility */
}

    </style>
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

// Fetch data functions
function getMaintenanceFaultStatsPerSemester($conn) {
    $query = "SELECT r.ResName, COUNT(t.TicketID) AS FaultCount, YEAR(t.DateCreated) AS Year,
              CASE WHEN MONTH(t.DateCreated) BETWEEN 1 AND 6 THEN 'Semester 1' ELSE 'Semester 2' END AS Semester
              FROM ticket t JOIN residence r ON t.ResidenceID = r.ResidenceID
              WHERE t.Status = 'Resolved' GROUP BY r.ResName, Year, Semester";
    return $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
}

function getMaintenanceFaultProgress($conn) {
    $query = "SELECT COUNT(TicketID) AS TotalTickets,
              SUM(CASE WHEN Status = 'Resolved' THEN 1 ELSE 0 END) AS ResolvedTickets,
              SUM(CASE WHEN Status = 'In Progress' THEN 1 ELSE 0 END) AS InProgressTickets FROM ticket";
    return $conn->query($query)->fetch(PDO::FETCH_ASSOC);
}

function getTurnaroundTimeStats($conn) {
    $query = "SELECT AVG(TIMESTAMPDIFF(HOUR, DateCreated, DateResolved)) AS AvgTurnaroundTime FROM ticket WHERE Status = 'Resolved'";
    return $conn->query($query)->fetch(PDO::FETCH_ASSOC);
}

function getMaintenanceCategoryStats($conn) {
    $query = "SELECT fc.CategoryName, COUNT(t.TicketID) AS FaultCount FROM ticket t
              JOIN faultcategory fc ON t.CategoryID = fc.CategoryID GROUP BY fc.CategoryName";
    return $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
}

function getComplaintCategoryHistory($conn) {
    $query = "SELECT YEAR(DateCreated) AS Year, fc.CategoryName, COUNT(t.TicketID) AS FaultCount FROM ticket t
              JOIN faultcategory fc ON t.CategoryID = fc.CategoryID GROUP BY Year, fc.CategoryName ORDER BY Year";
    return $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
}

// Prepare data for graphs
$faultStatsPerSemester = getMaintenanceFaultStatsPerSemester($conn);
$faultProgress = getMaintenanceFaultProgress($conn);
$turnaroundTime = getTurnaroundTimeStats($conn);
$categoryStats = getMaintenanceCategoryStats($conn);
$complaintHistory = getComplaintCategoryHistory($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Staff Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .chart-container {
            width: 80%;
            margin: auto;
            margin-bottom: 40px;
        }
        canvas {
            max-width: 100%;
            height: 400px;
        }
    </style>
</head>
<body>

<h1>Maintenance Staff Dashboard Performance Analytics</h1>

<div class="chart-container">
    <h2>Maintenance Fault Stats per Semester per Residence</h2>
    <canvas id="faultStatsPerSemesterChart"></canvas>
</div>

<div class="chart-container">
    <h2>Maintenance Fault Progress</h2>
    <canvas id="faultProgressChart"></canvas>
</div>

<div class="chart-container">
    <h2>Average Turnaround Time (in Hours)</h2>
    <canvas id="turnaroundTimeChart"></canvas>
</div>

<div class="chart-container">
    <h2>Maintenance Fault Stats by Category</h2>
    <canvas id="categoryStatsChart"></canvas>
</div>

<div class="chart-container">
    <h2>History of Complaint Categories</h2>
    <canvas id="complaintHistoryChart"></canvas>
</div>

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
            borderWidth: 1,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        animations: {
            tension: {
                duration: 1000,
                easing: 'linear',
                from: 1,
                to: 0,
                loop: true
            }
        },
        scales: {
            y: { beginAtZero: true }
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
    options: { responsive: true }
});

// Average Turnaround Time
const turnaroundTimeData = <?php echo json_encode($turnaroundTime); ?>;
const turnaroundTimeCtx = document.getElementById('turnaroundTimeChart').getContext('2d');
new Chart(turnaroundTimeCtx, {
    type: 'line',
    data: {
        labels: ['Average Turnaround Time (Hours)'],
        datasets: [{
            label: 'Turnaround Time',
            data: [turnaroundTimeData.AvgTurnaroundTime],
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 1,
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
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
            label: 'Fault Count by Category',
            data: categoryStatsCounts,
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            borderColor: 'rgba(255, 159, 64, 1)',
            borderWidth: 1,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
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
            label: 'Fault Count History',
            data: complaintHistoryCounts,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1,
            fill: false,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});
</script>

</body>
</html>
