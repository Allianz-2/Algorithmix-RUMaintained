<?php
require_once('config.php');
session_start();

// Check if user is logged in and is a Hall Secretary
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Hall Secretary') {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');

// Fetch notifications (adjust the query based on your database structure)
$query = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 10";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hall Secretary Dashboard - Notifications</title>
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
                <li><a href="HSDSTicketApproval.html"><i class="fas fa-check-circle"></i> Ticket Approvals</a></li>
                <li><a href="HSDSAnalytics.html"><i class="fas fa-chart-bar"></i> Analytics</a></li>
                <li><a href="HSDS.html"><i class="fas fa-tasks"></i> Requests</a></li>
                <li><a href="#"><i class="fas fa-building"></i> Residences</a></li>
                <li class="active"><a href="#"><i class="fas fa-bell"></i> Notifications</a></li>
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
            <h2> Notifications</h2>

            <div class="container">
                <div class="notification-controls">
                    <div class="filter-sort">
                        <select id="notification-filter">
                            <option value="all">All Notifications</option>
                            <option value="ticket">Ticket Updates</option>
                            <option value="system">System Alerts</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                        <select id="notification-sort">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                        </select>
                    </div>
                    <button id="mark-all-read">Mark All as Read</button>
                </div>

                <div class="notification-list">
                    <?php while ($notification = mysqli_fetch_assoc($result)): ?>
                        <div class="notification-item <?php echo $notification['is_read'] ? '' : 'unread'; ?>">
                            <div class="notification-icon">
                                <?php
                                switch ($notification['type']) {
                                    case 'ticket':
                                        echo '<i class="fas fa-ticket-alt"></i>';
                                        break;
                                    case 'system':
                                        echo '<i class="fas fa-exclamation-circle"></i>';
                                        break;
                                    case 'maintenance':
                                        echo '<i class="fas fa-tools"></i>';
                                        break;
                                    default:
                                        echo '<i class="fas fa-bell"></i>';
                                }
                                ?>
                            </div>
                            <div class="notification-content">
                                <div class="notification-title"><?php echo htmlspecialchars($notification['title']); ?></div>
                                <div class="notification-message"><?php echo htmlspecialchars($notification['message']); ?></div>
                                <div class="notification-time"><?php echo time_elapsed_string($notification['created_at']); ?></div>
                            </div>
                            <div class="notification-actions">
                                <?php if ($notification['type'] == 'ticket'): ?>
                                    <a href="view_ticket.php?id=<?php echo $notification['related_id']; ?>"><i class="fas fa-eye"></i> View Ticket</a>
                                <?php elseif ($notification['type'] == 'system'): ?>
                                    <a href="view_alert.php?id=<?php echo $notification['related_id']; ?>"><i class="fas fa-info-circle"></i> View Details</a>
                                <?php elseif ($notification['type'] == 'maintenance'): ?>
                                    <a href="view_report.php?id=<?php echo $notification['related_id']; ?>"><i class="fas fa-file-alt"></i> View Report</a>
                                <?php endif; ?>
                                <?php if (!$notification['is_read']): ?>
                                    <a href="mark_read.php?id=<?php echo $notification['id']; ?>"><i class="fas fa-check"></i> Mark as Read</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

            </div>
        </div>
    </main>

    <script>
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
        
                function viewMaintenanceReport() {
                    alert('Viewing Maintenance Report');
                    // In a real app, this would show the maintenance report
                }
        
                document.getElementById('filterType').addEventListener('change', function() {
                    console.log('Filtering notifications by:', this.value);
                    // Implement filtering logic here
                });
        
                document.getElementById('sortOrder').addEventListener('change', function() {
                    console.log('Sorting notifications by:', this.value);
                    // Implement sorting logic here
                });
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

<?php
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>