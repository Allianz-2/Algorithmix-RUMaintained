<?php
require_once('config.php');
session_start();

// Check if user is logged in and is a Hall Secretary
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Hall Secretary') {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');

// Fetch filter values (you may want to implement these as actual form inputs)
$dateRange = isset($_GET['date_range']) ? $_GET['date_range'] : 'Last 7 Days';
$residence = isset($_GET['residence']) ? $_GET['residence'] : 'All';
$severity = isset($_GET['severity']) ? $_GET['severity'] : 'All';
$category = isset($_GET['category']) ? $_GET['category'] : 'All';
$status = isset($_GET['status']) ? $_GET['status'] : 'All';

// Build the query based on filters
$query = "SELECT t.TicketID, t.CategoryID, t.ResidenceID, t.Status, t.DateCreated, t.Severity
          FROM ticket t 
          JOIN residence r ON t.ResidenceID = r.ResidenceID
          WHERE 1=1";

if ($dateRange !== 'All') {
    // Add date range filter
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

// Fetch statistics
$totalRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM ticket"))['count'];
$pendingRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM ticket WHERE Status = 'Pending'"))['count'];
$viewedRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM ticket WHERE Status = 'Viewed'"))['count'];
$completedRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM ticket WHERE Status = 'Completed'"))['count'];
$onlineUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM user WHERE LastActive > DATE_SUB(NOW(), INTERVAL 15 MINUTE)"))['count'];

mysqli_close($conn);
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
            <span class="user-welcome">Welcome, <?php echo $_SESSION['user_name']; ?></span>
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
                <div class="stat-box">
                    <h4>Online Users</h4>
                    <p><?php echo $onlineUsers; ?></p>
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
                        <th>Work Order</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Category']); ?></td>
                        <td><!-- Add photo handling logic here --></td>
                        <td><?php echo htmlspecialchars($row['ResidenceID']); ?></td>
                        <td><?php echo htmlspecialchars($row['Status']); ?></td>
                        <td><?php echo htmlspecialchars($row['DateCreated']); ?></td>
                        <td><?php echo htmlspecialchars($row['Severity']); ?></td>
                        <td><?php echo htmlspecialchars($row['WorkOrder']); ?></td>
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