<?php
require_once("../5-UserSignInandRegistration/14-secure.php"); 
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

// Function to fetch open tickets data
function getOpenTicketsData($conn) {
    $query = "SELECT COUNT(TicketID) AS Count, DATE(DateCreated) AS Day FROM Ticket WHERE Status = 'Open' GROUP BY Day ORDER BY Day;";
    $result = mysqli_query($conn, $query);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [$row['Day'], (int)$row['Count']];
    }
    mysqli_free_result($result);
    return $data;
}

// Fetching data
$openTicketsData = getOpenTicketsData($conn);

// Query to fetch fault stats per semester
$sql = "SELECT 
            R.ResName, 
            YEAR(T.DateCreated) AS Year, 
            CASE 
                WHEN MONTH(T.DateCreated) BETWEEN 1 AND 6 THEN 'Semester 1'
                ELSE 'Semester 2'
            END AS Semester,
            COUNT(T.TicketID) AS FaultCount
        FROM 
            ticket T
        JOIN 
            residence R ON T.ResidenceID = R.ResidenceID
        GROUP BY 
            R.ResName, 
            YEAR(T.DateCreated), 
            CASE 
                WHEN MONTH(T.DateCreated) BETWEEN 1 AND 6 THEN 'Semester 1'
                ELSE 'Semester 2'
            END
        ORDER BY 
            Year, Semester";

// Execute the query
$result = $conn->query($sql);

// Check if query execution was successful
if (!$result) {
    die('Query failed: ' . $conn->error);
}

// Initialize array for fault stats
$faultStatsPerSemester = array();

// Proceed only if there are results
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $faultStatsPerSemester[] = $row;
    }
} else {
    echo "No data found.";
}

// Close the database connection
$conn->close();
?>

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
            <span class="user-welcome">Welcome, <?php echo $_SESSION['Firstname']; ?></span>
            <a href="../6-UserProfileManagementPage/2-ProfileHW.php"><i class="fas fa-user"></i></a>
        </div>
        <ul>
            <li><a href="../1-GeneralPages/1-Home.php"><i class="fas fa-home"></i>Home</a></li>
            <li><a href="../7-TicketCreation/1-TicketCreation.php"><i class="fas fa-ticket"></i>Create Ticket</a></li>
            <li class="active"><a href="../3-HWDashboard/1-HWRequests.php"><i class="fas fa-tools"></i>Ticket Requests</a></li>
            <li><a href="../3-HWDashboard/2-TicketApproval.php"><i class="fas fa-check-circle"></i>Ticket Approvals</a></li>
            <li><a href="../3-HWDashboard/3-HWAnalytics.php"><i class="fas fa-chart-line"></i>Analytics</a></li>
            <li><a href="../3-HWDashboard/4-HWNotifications.php"><i class="fas fa-bell"></i>Notifications</a></li>
            <li><a href="../3-HWDashboard/5-HWHelp.php"><i class="fas fa-info-circle"></i>Help and Support</a></li>
        </ul>
        <div class="sidebar-footer">
            <p><a href="../6-UserProfileManagementPage/2-ProfileHW.php"><i class="fas fa-cog"></i> Settings</a></p>
            <p><a href="../5-UserSignInandRegistration/15-Logout.php" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</a></p>
        </div>
    </nav>
    <main>
        <header>
            <div class="header-left">
                <div id="hamburger-icon" class="hamburger-icon"><i class="fas fa-bars"></i></div>
                <strong>House Maintenance Dashboard</strong>
            </div>
        </header>

        <div class="content">
            <div class="charts-container">
                <div class="chart-box">
                    <h3>Fault Stats per Semester</h3>
                    <canvas id="faultStatsPerSemesterChart"></canvas>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Fault Stats data from PHP
        const faultStatsData = <?php echo json_encode($faultStatsPerSemester); ?>;
        const faultStatsLabels = faultStatsData.map(data => `${data.ResName} - ${data.Year} ${data.Semester}`);
        const faultStatsCounts = faultStatsData.map(data => data.FaultCount);

        // Chart.js initialization
        const ctx = document.getElementById('faultStatsPerSemesterChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: faultStatsLabels,
                datasets: [{
                    label: 'Fault Count',
                    data: faultStatsCounts,
                    backgroundColor: '#4E73DF',
                    borderColor: '#4E73DF',
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
    </script>

</body>
</html>
