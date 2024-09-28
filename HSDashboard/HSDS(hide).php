<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); 

    if (isset($_POST['submit'])) {
        require_once('../8-PHPTests/config.php');

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
    

        // Fetch filter values
        $dateRange = isset($_POST['date_range']) ? $_POST['date_range'] : 'Last 7 Days';
        $residence = isset($_POST['residence']) ? $_POST['residence'] : 'All';
        $severity = isset($_POST['severity']) ? $_POST['severity'] : 'All';
        $category = isset($_POST['category']) ? $_POST['category'] : 'All';
        $status = isset($_POST['status']) ? $_POST['status'] : 'All';

        // Use prepared statements to avoid SQL injection
        $query = "SELECT t.TicketID, t.Category, t.ResidenceID, t.Status, t.DateCreated, t.Severity, t.PhotoURL, r.ResName
                FROM ticket t 
                JOIN residence r ON t.ResidenceID = r.ResidenceID 
                WHERE 1=1";

        if ($dateRange !== 'All') {
            $date = date('Y-m-d', strtotime("-7 days")); // Example for "Last 7 Days"
            $query .= " AND t.DateCreated >= '$date'";
        }
        if ($residence !== 'All') {
            $query .= " AND r.ResidenceName = '$residence'";
        }
        if ($severity !== 'All') {
            $query .= " AND t.Severity = '$severity'";
        }
        if ($category !== 'All') {
            $query .= " AND t.Category = '$category'";
        }
        if ($status !== 'All') {
            $query .= " AND t.Status = '$status'";
        }

        $query .= " ORDER BY t.DateCreated DESC LIMIT 10";

        $result = mysqli_query($conn, $query);
        if (!$result) {
            die('Error: ' . mysqli_error($conn));
        }

        mysqli_close($conn);

    }
        // Fetch statistics
        $totalRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM ticket"))['count'];
        $pendingRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM ticket WHERE Status = 'Pending'"))['count'];
        $viewedRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM ticket WHERE Status = 'Viewed'"))['count'];
        $completedRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM ticket WHERE Status = 'Completed'"))['count'];


    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hall Secretary Dashboard - Requests</title>
    <link rel="stylesheet" href="Dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>
    <header>
        <div class="logo">
            <span class="user-welcome">Welcome, <?php echo $_SESSION['Firstname']; ?></span>
            <a href="user_profile.php"></a>
        </div>
        <nav>
            <ul>
                <li><a href="HSDSTicketApproval.php"><i class="fas fa-check-circle"></i> Ticket Approvals</a></li>
                <li><a href="HSDSAnalytics.php"><i class="fas fa-chart-bar"></i> Analytics</a></li>
                <li class="active"><a href="#"><i class="fas fa-tasks"></i> Requests</a></li>
                <li><a href="#"><i class="fas fa-building"></i> Residences</a></li>
                <li><a href="HSDSNotifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <a href="#"><i class="fas fa-cog"></i> Settings</a>
            <a href="#" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>
    </header>

    <main role="main">
        <div>
            <div class="header-left">
                <div id="hamburger-icon" class="hamburger-icon">
                    <i class="fas fa-bars"></i>
                </div>
                <h2>Hall Secretary Dashboard</h2>
            </div>
            <div class="logo">
                <img src="/path/to/rumaintained-logo.jpg" alt="rumaintained logo">
            </div>
        </div>


        <div class="content">
            <h2>Requests</h2>

            <div class="filters">
                <!-- Add filter dropdowns here -->
            </div>

            <!-- look at what database has  -->
            <div class="stats">
                <div class="stat-box">
                    <h4>Total Requests</h4>
                    <p><?php echo $totalRequests; ?></p>
                </div>
                <div class="stat-box">
                    <h4>Pending Requests</h4>
                    <p><?php echo $pendingRequests; ?></p>
                </div>
                <div class="stat-box">
                    <h4>Viewed Requests</h4>
                    <p><?php echo $viewedRequests; ?></p>
                </div>
                <div class="stat-box">
                    <h4>Completed Requests</h4>
                    <p><?php echo $completedRequests; ?></p>
                </div>
                
            </div>

            <div class="recent-requests">
                <h3>Most Recent Requests</h3>
                <table>
                    <tr>
                        <th>Category</th>
                        <th>Photo</th>
                        <th>Residence</th>
                        <th>Status</th>
                        <th>Submission Date</th>
                        <th>Severity</th>
                
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Category']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($row['PhotoURL']); ?>" alt="Request Photo" width="50"></td><!-- Add photo handling logic here -->
                        <td><?php echo htmlspecialchars($row['ResidenceID']); ?></td>
                        <td><?php echo htmlspecialchars($row['Status']); ?></td>
                        <td><?php echo htmlspecialchars($row['DateCreated']); ?></td>
                        <td><?php echo htmlspecialchars($row['Severity']); ?></td>
            
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </main>

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