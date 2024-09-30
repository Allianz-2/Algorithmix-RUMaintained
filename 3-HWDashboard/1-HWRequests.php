<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Warden Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="Dashboard.css">
    <style>
 
    </style>
</head>

<body>
    <nav id="sidebar" class="sidebar">
        <div class="logo">
            <span class="user-welcome">Welcome,  </span><!-- Add PHP code for user name -->
            <a href="../6-UserProfileManagementPage\2-ProfileHW.php"><i class="fas fa-user"></i></a>
        </div>
        <ul>
            <li><a href="../1-GeneralPages\1-Home.php"><i class="fas fa-home"></i>Home</a></li>
            <li><a href="../7-TicketCreation\1-TicketCreation.php"><i class="fas fa-ticket"></i>Create Ticket</a></li>
            <li class="active"><a href="../3-HWDashboard\1-HWRequests.php"><i class="fas fa-tools"></i>Ticket Requests</a></li>
            <li><a href="../3-HWDashboard\2-TicketApproval.php"><i class="fas fa-check-circle"></i>Ticket Approvals</a></li>
            <li><a href="../3-HWDashboard\3-HWAnalytics.php"><i class="fas fa-chart-line"></i>Analytics</a></li>
            <li><a href="../3-HWDashboard\4-HWNotifications.php"><i class="fas fa-bell"></i>Notifications</a></li>
            <li><a href="../3-HWDashboard\5-HWHelp.php"><i class="fas fa-info-circle"></i>Help and Support</a></li>
        </ul>



        <div class="sidebar-footer">
            <p><a href="../6-UserProfileManagementPage\2-ProfileHW.php"><i class="fas fa-cog"></i> Settings</a></p>
            <p><a href="../5-UserSignInandRegistration/15-Logout.php" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</a></p>
        </div>
    </nav>

    <main>
        <header>
            <div class="header-left">
                <div id="hamburger-icon" class="hamburger-icon"><i class="fas fa-bars"></i></div>
                <strong>House Warden Dashboard</strong>
            </div>
            <div class="logo">
                <img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="RU Maintained Logo">
            </div>
        </header>
        <div class="content">
            <h2>My Ticket Requests</h2>
            </div>

            <?php 

require '../8-PHPTests/config.php';

    
$conn = mysqli_init(); 
if (!file_exists($ca_cert_path)) {
    die("CA file not found: " . $ca_cert_path);
}
mysqli_ssl_set($conn, NULL, NULL, $ca_cert_path, NULL, NULL);
mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL);

if (mysqli_connect_errno()) {
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// HOUSE WARDEN TICKETS START

$sql = "SELECT * FROM ticket WHERE HouseWardenID = ? AND StudentID = NULL";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['userID']);
$stmt->execute();
$result = $stmt->get_result(); // Fetch results using get_result()
$stmt->close(); 


?>


    <table>
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Description</th>
                <th>Status</th>
                <th>Severity</th>
                <th>Date Created</th>
                <th>Details</th>
            </tr>
        </thead>
        
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['TicketID']; ?></td>
                <td><?php echo $row['Description']; ?></td>
                <td><?php echo $row['Status']; ?></td>
                <td><?php echo $row['Severity']; ?></td>
                <td><?php echo $row['DateCreated']; ?></td>
                <td><a href = "#"><button>View</button></a></td>
                
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>




            <div class="content">
            <h2>My Residence Ticket Requests</h2>
            </div>


            <?php


        $sql = "SELECT ResidenceID FROM residence WHERE HouseWardenID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_SESSION['userID']);
        $stmt->execute();
        $stmt->bind_result($resID);
        $stmt->fetch();
        $stmt->close(); 

        $sql = "SELECT * FROM ticket WHERE ResidenceID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $resID);
        $stmt->execute();
        $resultRes = $stmt->get_result(); // Fetch results using get_result()
        $stmt->close(); 


        $stmt = $conn->prepare("SELECT COUNT(*) as TotalTickets FROM ticket WHERE ResidenceID = ?");
        $stmt->bind_param("s", $resID); 
        $stmt->execute();
        $result = $stmt->get_result();
        $totalTickets = $result->fetch_assoc()['TotalTickets'];
        $stmt->close();
        
        
        $stmt = $conn->prepare("SELECT COUNT(*) as PendingTickets FROM ticket WHERE Status NOT IN (?, ?) AND ResidenceID = ?");
        $status1 = 'Rejected';
        $status2 = 'Closed';
        $stmt->bind_param("sss", $status1, $status2, $resID); 
        $stmt->execute();
        $result = $stmt->get_result(); // Get the result set from the statement
        $pendingTickets = $result->fetch_assoc()['PendingTickets']; // Fetch the count
        $stmt->close();


        $stmt = $conn->prepare("SELECT COUNT(*) as CompletedTickets FROM ticket WHERE Status = ? AND ResidenceID = ?");
        $status = 'Closed';
        $stmt->bind_param("ss", $status, $resID); 
        $stmt->execute();
        $result = $stmt->get_result(); // Get the result set from the statement
        $completedTickets = $result->fetch_assoc()['CompletedTickets']; // Fetch the count
        $stmt->close();

        $stmt = $conn->prepare("SELECT COUNT(*) as ViewedTickets FROM ticket WHERE Status IN (?,?) AND ResidenceID = ?");
        $status3 = 'Closed';
        $status4 = 'Resolved';
        $stmt->bind_param("sss", $status3, $status4, $resID); 
        $stmt->execute();
        $result = $stmt->get_result(); // Get the result set from the statement
        $viewedTickets = $result->fetch_assoc()['ViewedTickets']; // Fetch the count
        $stmt->close();

            
        ?>

        
        <div class="content">
           
            <div class="charts">
                <!-- Google Chart containers -->
                <div id="requestChart" class="chart-box"></div>
                <div id="residenceChart" class="chart-box"></div>
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
            </div>

            <table>
                <thead>
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
                <?php while ($row = $resultRes->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['TicketID']; ?></td>
                        <td><?php echo $row['Description']; ?></td>
                        <td><?php echo $row['Status']; ?></td>
                        <td><?php echo $row['Severity']; ?></td>
                        <td><?php echo $row['DateCreated']; ?></td>
                        <td><?php echo $row['StudentID']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Load Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            // Request Pie Chart
            var requestData = google.visualization.arrayToDataTable([
                ['Task', 'Count'],
                ['incomplete Tickets', <?php echo $totalTickets; ?>],
                ['Pending Tickets', <?php echo $pendingTickets; ?>],
                ['Completed Tickets', <?php echo $completedTickets; ?>],
                ['Viewed Tickets', <?php echo $viewedTickets; ?>]
            ]);

            var requestOptions = {
                title: 'Requests Overview',
                backgroundColor: 'transparent',
                pieHole: 0.4,
                colors: ['#a64b89', '#5c4b8a', '#f1eaf5', '#81589a'],
                legend: 'bottom'
            };

            var requestChart = new google.visualization.PieChart(document.getElementById('requestChart'));
            requestChart.draw(requestData, requestOptions);

            // Residence Column Chart
            var residenceData = google.visualization.arrayToDataTable([
                ['Task', 'Count'],
                ['Pending', <?php echo $pendingTickets; ?>],
                ['Completed', <?php echo $completedTickets; ?>]
            ]);

            var residenceOptions = {
                title: 'Residence Tasks',
                backgroundColor: 'transparent',
                colors: ['#f4a5d2', '#c0a1f5'],
                legend: 'none',
                chartArea: { width: '70%' },
                hAxis: {
                    title: 'Task Status',
                    minValue: 0
                },
                vAxis: {
                    title: 'Count'
                }
            };

            var residenceChart = new google.visualization.ColumnChart(document.getElementById('residenceChart'));
            residenceChart.draw(residenceData, residenceOptions);
        }

        function confirmLogout() {
            return confirm('Are you sure you want to log out?');
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

</html>
