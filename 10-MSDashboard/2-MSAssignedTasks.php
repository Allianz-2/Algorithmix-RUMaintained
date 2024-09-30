<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="6-Notifications.css">


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




<body>
    <nav id="sidebar" class="sidebar">
        <div class="logo">
            <span class="user-welcome">Welcome, User </span><!-- Add PHP code here for user name -->
            <a href="user page"><i class="fas fa-user"></i></a> 
        </div>
        <ul>
            <li><a href="#"><i class="fas fa-home"></i>Home</a></li>
            <li><a href="1-MSRequests.php"><i class="fas fa-tasks"></i> Requests</a></li>
            <li  class="active"><a href="2-MSAssignedTasks.php"><i class="fas fa-clipboard-list"></i>Task Assignment</a></li>
            <li><a href="3-MSAnalytics.php"><i class="fas fa-chart-bar"></i>Analytics</a></li>
            <li><a href="4-MSNotifications.php"><i class="fas fa-bell"></i>Notifications</a></li>
            <li><a href="5-MSHelp.php"><i class="fas fa-info-circle"></i>Help and Support</a></li>
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

        <div class="content">
            <h2>Task Assignments</h2>
           

  
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
    <!-- <h2>Task Assignment</h2> -->

    <table>
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Description</th>
                <th>Status</th>
                <th>Severity</th>
                <th>Date Created</th>
                <th>Assigned to</th>
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
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
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

                function markAllAsRead() {
                    var items = document.getElementsByClassName('notification-item');
                    for (var i = 0; i < items.length; i++) {
                        items[i].classList.remove('unread');
                    }
                }
        
                function markAsRead(button) {
                    button.closest('.notification-item').classList.remove('unread');
                }
        
                function viewTicket(ticketId) {
                    alert('Viewing Ticket #' + ticketId);
                    // In a real app, this would navigate to the ticket details page
                }
        
                function viewAlert() {
                    alert('Viewing System Alert Details');
                    // In a real app, this would show more information about the alert
                }
        
        
                document.getElementById('filterType').addEventListener('change', function() {
                    console.log('Filtering notifications by:', this.value);
                    // Implement filtering logic here
                });
        
                document.getElementById('sortOrder').addEventListener('change', function() {
                    console.log('Sorting notifications by:', this.value);
                    // Implement sorting logic here
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
        };

    </script>
</body>
</html>
