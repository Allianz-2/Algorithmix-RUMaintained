<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Progress Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<style> 
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 0px;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            color: #333333;
            background-color: #f2f2f2;
        }

        .sidebar {
            width: var(--sidebar-width);
            background-color: #81589a;
            color: white;
            height: 100vh;
            position: fixed;
            transition: all 0.3s;
            left: 0;
    
        }

        .sidebar a {
    color: white;
    text-decoration: none;
}

        .sidebar.collapsed {
            left: calc(-1 * var(--sidebar-width));
            display: none;
        }

        .sidebar .logo {
            padding: 20px;
            text-align: right;
        }

        .sidebar .logo a {
            color: white;  /* Change user icon to white */
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar li {
            padding: 15px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .sidebar li:hover, .sidebar li.active {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar .badge {
            background-color: purple;
            color: white;
            padding: 2px 6px;
            border-radius: 50%;
            font-size: 0.8em;
            margin-left: 5px;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 20px;
            font-size: 16px;
        }

        .sidebar li i, .sidebar-footer button i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        main {
            flex-grow: 1;
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: margin-left 0.3s;
        }

        main.sidebar-collapsed {
            margin-left: 0;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background-color: white;  /* Change header to a banner style */
            color: #333333;
            padding: 10px 20px;
            /* Adjust for padding */
        }

        .hamburger-icon {
            font-size: 24px;
            cursor: pointer;
            display: inline-block;
            padding: 10px;
        }

        .filters {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-group select {
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #d1d5db;
            background-color: white;
            margin-right: 10px;

        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .charts {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-box {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            flex: 1;
            text-align: center;
        }

        table {
            align-self: center;
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            align-items: center;
            border: 1px solid #ddd;
            padding: 12px;
            text-align: middle;
        }

        th {
            background-color: white;
        }


.logo img {
    max-height: 60px;  /* Adjust this value as needed */
    width: auto;
    object-fit: contain;
}
.user-welcome {
    align-items: center;
    text-align: left;
    margin-right: 20px;
}   

.ticketButton {
    padding: 10px 20px; 
    font-size: 1rem; 
    font-weight: bold;
    border: none;
    border-radius: 5px;
    background-color: #81589a;
    color: #fff;
    cursor: pointer;
}

button {
    padding: 5px 10px; 
    font-size: 1rem; 
    font-weight: bold;
    border: none;
    border-radius: 5px;
    background-color: #81589a;
    color: #ffffff;
    cursor: pointer;
}
</style>
<?php
// Include database connection
require_once('config.php');
$conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');

// Message variable to store feedback messages
$message = '';

// Delete ticket logic
if (isset($_POST['delete_ticketid'])) {
    $ticketid = $_POST['delete_ticketid'];
    $delete_query = "DELETE FROM ticket WHERE ticketid = '$ticketid'";

    if (mysqli_query($conn, $delete_query)) {
        $message = "Ticket deleted successfully.";
    } else {
        $message = "Error deleting ticket: " . mysqli_error($conn);
    }
}

// Fetch approved tickets
$query = "SELECT ticketid, description, DateCreated, Status, Severity FROM ticket WHERE status = 'confirmed'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<nav id="sidebar" class="sidebar">
    <div class="logo">
        <span class="user-welcome">Welcome, </span>
        <a href="user-page"><i class="fas fa-user"></i></a>
    </div>
    <ul>
        <li><a href="2-HouseWardenAnalytics.php"><i class="fas fa-chart-pie"></i>Analytics</a></li>
        <li><a href="4-Ticketapproval.php"><i class="fas fa-tasks"></i>Ticket Progress</a></li>
        <li><a href="notifications.html"><i class="fas fa-bell"></i>Notifications</a></li>
        <li><a href="lodge-ticket.html"><i class="fas fa-plus-circle"></i>Lodge Ticket</a></li>
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
            <strong>House Warden Dashboard</strong>
        </div>
        <div class="logo">
            <img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="rumaintained logo">
        </div>
    </header>

    <div class="content">
        <h3>Ticket Management</h3>
        
        <?php if ($message): ?>
            <div class='message'><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <h4>Approved Tickets</h4>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table border='1'>
                <tr>
                    <th>Ticket ID</th>
                    <th>Description</th>
                    <th>Date Created</th>
                    <th>Status</th>
                    <th>Severity</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['ticketid']; ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo $row['DateCreated']; ?></td>
                        <td>
                            <form method='post' action='ticketupdate.php' style='display:inline;'>
                                <input type='hidden' name='update_ticketid' value='<?php echo $row['ticketid']; ?>'>
                                <select name='status'>
                                    <option value='confirmed'<?php echo ($row['Status'] == 'confirmed' ? ' selected' : ''); ?>>Confirmed</option>
                                    <option value='resolved'<?php echo ($row['Status'] == 'resolved' ? ' selected' : ''); ?>>Resolved</option>
                                    <option value='closed'<?php echo ($row['Status'] == 'closed' ? ' selected' : ''); ?>>Closed</option>
                                </select>
                        </td>
                        <td>
                                <select name='severity'>
                                    <option value='low'<?php echo ($row['Severity'] == 'low' ? ' selected' : ''); ?>>Low</option>
                                    <option value='medium'<?php echo ($row['Severity'] == 'medium' ? ' selected' : ''); ?>>Medium</option>
                                    <option value='high'<?php echo ($row['Severity'] == 'high' ? ' selected' : ''); ?>>High</option>
                                </select>
                                <button type='submit' name='action' value='update'>Update</button>
                            </form>
                        </td>
                        <td>
                            <form method='post' action='3-TicketProgress.php' style='display:inline;'>
                                <input type='hidden' name='delete_ticketid' value='<?php echo $row['ticketid']; ?>'>
                                <button type='submit' name='action' value='delete'>Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No approved tickets found.</p>
        <?php endif; ?>
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