<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Dashboard.css">
    <title>Ticket Update Page</title>
</head>
<body>
<nav id="sidebar" class="sidebar">
        <div class="logo">
            <span class="user-welcome">Welcome, </span><!-- Add PHP code for user name -->
            <a href="user-page"><i class="fas fa-user"></i></a>
        </div>
        <ul>
            <li><a href="2-HouseWardenAnalytics .php"><i class="fas fa-chart-pie"></i>Analytics</a></li>
            <li><a href="3-TicketProgress.php"><i class="fas fa-tasks"></i>Ticket Progress</a></li>
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

    <?php
    // Include database connection
    require_once('config.php');
    $conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');

    // Message variable to store feedback messages
    $message = '';

    // Handle ticket deletion
    if (isset($_POST['delete_ticketid'])) {
        $ticketid = $_POST['delete_ticketid'];
        $delete_query = "DELETE FROM ticket WHERE ticketid = '$ticketid'";

        if (mysqli_query($conn, $delete_query)) {
            $message = "Ticket deleted successfully.";
        } else {
            $message = "Error deleting ticket: " . mysqli_error($conn);
        }
    }

    // Handle ticket update (description, status, severity)
    if (isset($_POST['update_ticketid'])) {
        $ticketid = $_POST['update_ticketid'];
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $status = $_POST['status'];
        $severity = $_POST['severity'];

        $update_query = "UPDATE ticket SET description = '$description', Status = '$status', Severity = '$severity' WHERE ticketid = '$ticketid'";

        if (mysqli_query($conn, $update_query)) {
            $message = "Ticket updated successfully.";
        } else {
            $message = "Error updating ticket: " . mysqli_error($conn);
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
                        <form method='post' action='TICKETUPDATE.PHP' style='display:inline;'>
                            <input type='hidden' name='update_ticketid' value='" . $row['ticketid'] . "'>

                            <!-- Description textarea -->
                            <textarea name='description' required>" . htmlspecialchars($row['description']) . "</textarea>

                            <!-- Status dropdown -->
                            <select name='status'>
                                <option value='confirmed'" . ($row['Status'] == 'confirmed' ? ' selected' : '') . ">Confirmed</option>
                                <option value='resolved'" . ($row['Status'] == 'resolved' ? ' selected' : '') . ">Resolved</option>
                                <option value='closed'" . ($row['Status'] == 'closed' ? ' selected' : '') . ">Closed</option>
                            </select>

                            <!-- Severity dropdown -->
                            <select name='severity'>
                                <option value='low'" . ($row['Severity'] == 'low' ? ' selected' : '') . ">Low</option>
                                <option value='medium'" . ($row['Severity'] == 'medium' ? ' selected' : '') . ">Medium</option>
                                <option value='high'" . ($row['Severity'] == 'high' ? ' selected' : '') . ">High</option>
                            </select>

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
