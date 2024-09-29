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
<nav id="sidebar" class="sidebar">
    <div class="logo">
        <span class="user-welcome">Welcome, </span>
        <a href="user-page"><i class="fas fa-user"></i></a>
    </div>
    <ul>
        <li><a href="analytics.html"><i class="fas fa-chart-pie"></i>Analytics</a></li>
        <li><a href="3-TicketProgress.php"><i class="fas fa-tasks"></i>Ticket Progress</a></li>
        <li><a href="notifications.html"><i class="fas fa-bell"></i>Notifications</a></li>
        <li><a href="lodge-ticket.html"><i class="fas fa-plus-circle"></i>Lodge Ticket</a></li>
    </ul>
    <div class="sidebar-footer">
        <p><a href="#"><i class="fas fa-cog"></i> Settings</a></p>
        <p><a href="#" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</a></p>
    </div>
</nav>

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

// Display any success or error messages
if ($message) {
    echo "<div class='message'>" . htmlspecialchars($message) . "</div>";
}

// Check if there are any approved tickets
if (mysqli_num_rows($result) > 0) {
    echo "<table border='1'>
            <tr>
                <th>Ticket ID</th>
                <th>Description</th>
                <th>Date Created</th>
                <th>Status</th>
                <th>Severity</th>
                <th>Action</th>
            </tr>";

    // Loop through and display each approved ticket
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . $row['ticketid'] . "</td>
                <td>
                    <form method='post' action='ticketupdate.php' style='display:inline;'>
                        <input type='hidden' name='update_ticketid' value='" . $row['ticketid'] . "'>

                        <!-- Description textarea -->
                        <textarea name='description' required>" . htmlspecialchars($row['description']) . "</textarea>

                        <!-- Status select dropdown -->
                        <select name='status'>
                            <option value='confirmed'" . ($row['Status'] == 'confirmed' ? ' selected' : '') . ">Confirmed</option>
                            <option value='resolved'" . ($row['Status'] == 'resolved' ? ' selected' : '') . ">Resolved</option>
                            <option value='closed'" . ($row['Status'] == 'closed' ? ' selected' : '') . ">Closed</option>
                        </select>

                        <!-- Severity select dropdown -->
                        <select name='severity'>
                            <option value='low'" . ($row['Severity'] == 'low' ? ' selected' : '') . ">Low</option>
                            <option value='medium'" . ($row['Severity'] == 'medium' ? ' selected' : '') . ">Medium</option>
                            <option value='high'" . ($row['Severity'] == 'high' ? ' selected' : '') . ">High</option>
                        </select>

                        <!-- Update button -->
                        <button type='submit' name='action' value='update'>Update</button>
                    </form>
                </td>
                <td>" . $row['DateCreated'] . "</td>
                <td>" . $row['Status'] . "</td>
                <td>" . $row['Severity'] . "</td>
                <td>
                    <form method='post' action='3-TicketProgress.php' style='display:inline;'>
                        <input type='hidden' name='delete_ticketid' value='" . $row['ticketid'] . "'>
                        <button type='submit' name='action' value='delete'>Delete</button>
                    </form>
                </td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "No approved tickets found.";
}
?>
</body>
</html>
