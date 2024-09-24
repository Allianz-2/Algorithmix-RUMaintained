<?php
require_once('config.php');
session_start();

// Check if user is logged in and is a Hall Secretary
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Hall Secretary') {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');

// Fetch recent ticket updates
$ticket_query = "SELECT t.TicketID, t.Description, t.DateCreated, t.Status, r.ResidenceName 
                 FROM ticket t 
                 JOIN residence r ON t.ResidenceID = r.ResidenceID 
                 ORDER BY t.DateCreated DESC LIMIT 5";
$ticket_result = mysqli_query($conn, $ticket_query);

// Fetch system alerts (you might need to create this table or adjust based on your system)
$alert_query = "SELECT * FROM system_alerts ORDER BY DateCreated DESC LIMIT 2";
$alert_result = mysqli_query($conn, $alert_query);

// Fetch recent maintenance records
$maintenance_query = "SELECT m.MaintenanceID, m.Description, m.DateCompleted, r.ResidenceName 
                      FROM maintenance m 
                      JOIN residence r ON m.ResidenceID = r.ResidenceID 
                      WHERE m.Status = 'Completed' 
                      ORDER BY m.DateCompleted DESC LIMIT 3";
$maintenance_result = mysqli_query($conn, $maintenance_query);

mysqli_close($conn);

// Function to generate a notification array
function generate_notification($type, $data) {
    switch ($type) {
        case 'ticket':
            return [
                'type' => 'ticket',
                'title' => 'Ticket Update',
                'message' => "Ticket #{$data['TicketID']} for {$data['ResidenceName']} has been updated to {$data['Status']}.",
                'time' => $data['DateCreated'],
                'related_id' => $data['TicketID']
            ];
        case 'system':
            return [
                'type' => 'system',
                'title' => 'System Alert',
                'message' => $data['Message'],
                'time' => $data['DateCreated'],
                'related_id' => $data['AlertID']
            ];
        case 'maintenance':
            return [
                'type' => 'maintenance',
                'title' => 'Maintenance Completed',
                'message' => "Maintenance task for {$data['ResidenceName']} has been completed.",
                'time' => $data['DateCompleted'],
                'related_id' => $data['MaintenanceID']
            ];
    }
}

// Combine all notifications
$notifications = [];
while ($ticket = mysqli_fetch_assoc($ticket_result)) {
    $notifications[] = generate_notification('ticket', $ticket);
}
while ($alert = mysqli_fetch_assoc($alert_result)) {
    $notifications[] = generate_notification('system', $alert);
}
while ($maintenance = mysqli_fetch_assoc($maintenance_result)) {
    $notifications[] = generate_notification('maintenance', $maintenance);
}

// Sort notifications by time
usort($notifications, function($a, $b) {
    return strtotime($b['time']) - strtotime($a['time']);
});

// Function to display relative time
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
                    <?php foreach ($notifications as $notification): ?>
                        <div class="notification-item">
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
                                }
                                ?>
                            </div>
                            <div class="notification-content">
                                <div class="notification-title"><?php echo htmlspecialchars($notification['title']); ?></div>
                                <div class="notification-message"><?php echo htmlspecialchars($notification['message']); ?></div>
                                <div class="notification-time"><?php echo time_elapsed_string($notification['time']); ?></div>
                            </div>
                            <div class="notification-actions">
                                <?php if ($notification['type'] == 'ticket'): ?>
                                    <a href="view_ticket.php?id=<?php echo $notification['related_id']; ?>"><i class="fas fa-eye"></i> View Ticket</a>
                                <?php elseif ($notification['type'] == 'system'): ?>
                                    <a href="view_alert.php?id=<?php echo $notification['related_id']; ?>"><i class="fas fa-info-circle"></i> View Details</a>
                                <?php elseif ($notification['type'] == 'maintenance'): ?>
                                    <a href="view_report.php?id=<?php echo $notification['related_id']; ?>"><i class="fas fa-file-alt"></i> View Report</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </main>

    <script>
        function confirmLogout() {
            return confirm("Are you sure you want to log out?");
        }

        // Add your JavaScript for filtering and sorting notifications here
        document.addEventListener('DOMContentLoaded', function() {
            const filterSelect = document.getElementById('notification-filter');
            const sortSelect = document.getElementById('notification-sort');
            const notificationList = document.querySelector('.notification-list');

            filterSelect.addEventListener('change', updateNotifications);
            sortSelect.addEventListener('change', updateNotifications);

            function updateNotifications() {
                const filterValue = filterSelect.value;
                const sortValue = sortSelect.value;

                // Here you would typically make an AJAX call to the server to get filtered and sorted notifications
                // For this example, we'll just hide/show items based on the filter
                const notifications = notificationList.querySelectorAll('.notification-item');
                notifications.forEach(notification => {
                    if (filterValue === 'all' || notification.querySelector('.notification-icon i').classList.contains(`fa-${filterValue}`)) {
                        notification.style.display = '';
                    } else {
                        notification.style.display = 'none';
                    }
                });

                // Sort notifications (this is a simple example and might not work perfectly with your actual data)
                const sortedNotifications = Array.from(notifications).sort((a, b) => {
                    const timeA = new Date(a.querySelector('.notification-time').textContent);
                    const timeB = new Date(b.querySelector('.notification-time').textContent);
                    return sortValue === 'newest' ? timeB - timeA : timeA - timeB;
                });

                sortedNotifications.forEach(notification => {
                    notificationList.appendChild(notification);
                });
            }
        });
    </script>
</body>
</html>