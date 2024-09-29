<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Progress Page</title>
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

    // Handle ticket update
    if (isset($_POST['update_ticketid']) && isset($_POST['description'])) {
        $ticketid = $_POST['update_ticketid'];
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $update_query = "UPDATE ticket SET description = '$description' WHERE ticketid = '$ticketid'";

        if (mysqli_query($conn, $update_query)) {
            $message = "Ticket updated successfully.";
        } else {
            $message = "Error updating ticket: " . mysqli_error($conn);
        }
    }

    // Check if there's a message in the URL
    if (isset($_GET['message'])) {
        if ($_GET['message'] === 'success') {
            echo "<div class='message success'>Ticket processed successfully.</div>";
        }
    }

    // Display any feedback message
    if ($message) {
        echo "<div class='message success'>$message</div>";
    }

    // Fetch approved tickets
    $query = "SELECT ticketid, description, DateCreated, Status FROM ticket WHERE status = 'confirmed'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Check if there are any approved tickets
    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Ticket ID</th>
                    <th>Description</th>
                    <th>Date Created</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>";

        // Loop through and display each approved ticket
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . $row['ticketid'] . "</td>
                    <td>" . htmlspecialchars($row['description']) . "</td>
                    <td>" . $row['DateCreated'] . "</td>
                    <td>" . $row['Status'] . "</td>
                    <td>
                        <form method='post' action='TI.php' style='display:inline;'>
                            <input type='hidden' name='update_ticketid' value='" . $row['ticketid'] . "'>
                            <textarea name='description'>" . htmlspecialchars($row['description']) . "</textarea>
                            <button type='submit' name='action' value='update'>Update</button>
                        </form>
                        <form method='post' action='ticket_progress.php' style='display:inline;'>
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
