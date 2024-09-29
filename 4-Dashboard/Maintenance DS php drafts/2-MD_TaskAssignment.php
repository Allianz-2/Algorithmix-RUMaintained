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
            color:#81589a; 
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
    color: #81589a; /* Color of icons */
}

.btn-delete {
    background-color: #e57373; /* Light red color */
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-delete:hover {
    background-color: #c62828; /* Darker red on hover */
}

        
    </style>
</head>
<body>
    <nav id="sidebar" class="sidebar">
        <div class="logo">
            <span class="user-welcome">Welcome, </span><!-- Add PHP code for user name -->
            <a href="user-page"><i class="fas fa-user"></i></a>
        </div>
        <ul>
            <li><a href="1-MD_MaintenanceRequests.php"><i class="fas fa-tools"></i>Maintenance Requests</a></li>
        <li class="active"><a href="2-MD_TaskAssignment.php"><i class="fas fa-clipboard-list"></i>Task Assignment</a></li>
            <li><a href="3-MD_PerformanceAnalytics.php"><i class="fas fa-chart-line"></i>Performance Analytics</a></li>
            <li><a href="4-MD_Notifications.php"><i class="fas fa-bell"></i>Notifications</a></li>
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
                <img src="Images\General\93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="RU Maintained Logo">
            </div>
        </header>

        <?php 
//require_once('config.php');






// Connection information//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




require '../../8-PHPTests/config.php';

// Initializes MySQLi
$conn = mysqli_init();
$ca_cert_path = "../../CACertificate/DigiCertGlobalRootCA.crt.pem"; // Absolute path to the CA cert

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


// Connection information//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




// Fetch all maintenance requests
$query = "
    SELECT t.TicketID, t.Description, t.Status, t.Severity, t.DateCreated, 
           u.Firstname AS StudentFirstName, u.Lastname AS StudentLastName
    FROM ticket t
    JOIN user u ON t.StudentID = u.UserID";
// $conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');
$results = mysqli_query($conn, $query);

// Fetch task statistics
$totalTasks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as TotalTasks FROM ticket"))['TotalTasks'];
$pendingTasks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as PendingTasks FROM ticket WHERE Status = 'Open'"))['PendingTasks'];
$completedTasks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as CompletedTasks FROM ticket WHERE Status = 'Resolved'"))['CompletedTasks'];
$emergencyTasks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as EmergencyTasks FROM ticket WHERE Severity = 'High'"))['EmergencyTasks'];

// Fetch assigned maintenance staff for each ticket
$sql_assignments = "SELECT a.TicketID, u.Firstname AS AssignedFirstName, u.Lastname AS AssignedLastName
    FROM assignment a
    JOIN maintenancestaff ms ON a.MaintenanceStaffID = ms.MaintenanceStaffID
    JOIN user u ON ms.UserID = u.UserID";
$assignments_result = mysqli_query($conn, $sql_assignments);

// Create an associative array to store assignments by TicketID
$assignments = [];
while ($assignment = mysqli_fetch_assoc($assignments_result)) {
    $assignments[$assignment['TicketID']] = $assignment['AssignedFirstName'] . " " . $assignment['AssignedLastName'];
}
?>

<div class="content">
    <h2>Task Assignment</h2>

    <table>
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Description</th>
                <th>Status</th>
                <th>Severity</th>
                <th>Date Created</th>
                <th>Assigned to</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($results)): ?>
            <tr>
                <td><?php echo $row['TicketID']; ?></td>
                <td><?php echo $row['Description']; ?></td>
                <td>
                    <span class="status <?php echo strtolower($row['Status']); ?>">
                        <?php echo $row['Status']; ?>
                    </span>
                </td>
                <td><?php echo ucfirst($row['Severity']); ?></td>
                <td><?php echo date('d-m-Y', strtotime($row['DateCreated'])); ?></td>
                
                <!-- Check if assigned to exists, otherwise show 'Unassigned' -->
                <td>
                    <?php 
                    $assignedTo = isset($assignments[$row['TicketID']]) ? $assignments[$row['TicketID']] : 'Unassigned';
                    echo $assignedTo; 
                    ?>
                </td>
                <td>
<!-- Form for Update -->
<form action="update-ticket.php" method="post" style="display:inline; margin-right: 10px;"> <!-- Added margin to right -->
    <input type="hidden" name="TicketID" value="<?php echo $row['TicketID']; ?>">
    <button type="submit" class="btn-update" style="padding: 5px 10px; font-size: 14px;">Update</button> <!-- Smaller button -->
</form>

<!-- Form for Delete -->
<form action="delete-ticket.php" method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
    <input type="hidden" name="TicketID" value="<?php echo htmlspecialchars($row['TicketID']); ?>">
    <button type="submit" class="btn-delete" style="background-color: #e57373; color: white; border: none; padding: 5px 10px; font-size: 14px; border-radius: 5px; cursor: pointer; margin-left: 10px;">Delete</button> <!-- Added margin to left -->
</form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>


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

            function confirmLogout() {
                return confirm("Are you sure you want to log out?");
            }


        // we can also add the second chart for residence tasks here similarly
    </script>
    
</body>
</html>