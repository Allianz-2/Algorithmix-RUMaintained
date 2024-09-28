<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Approval Page</title>
    <style>
        .success {
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        .message {
            margin-bottom: 20px; /* Add space before the message */
        }
    </style>
</head>
<body>
    <?php 
    // Include database connection
    require_once('config.php');
    $conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');

    // Include ticket processing logic
    include('process_ticket.php'); // Include this file to handle ticket actions and display messages

    // Display any success or error messages
    if (isset($message)) {
        echo "<div class='message'>" . htmlspecialchars($message) . "</div>";
    }

    // Fetch tickets pending house warden approval
    $query = "SELECT ticketid, description, DateCreated, Status FROM ticket WHERE status = 'OPEN'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn)); // This will show any SQL error
    }

    // Check if there are any tickets
    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Ticket ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Date Created</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>";

        // Loop through and display each ticket
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . $row['ticketid'] . "</td>
                    <td>" . $row['description'] . "</td>
                    <td>" . $row['DateCreated'] . "</td>
                    <td>" . $row['Status'] . "</td>
                    <td>
                        <form method='post' action='process_ticket.php'>
                            <input type='hidden' name='ticketid' value='" . $row['ticketid'] . "'>
                            <button type='submit' name='action' value='approve'>Approve</button>
                            <button type='submit' name='action' value='reject'>Reject</button>
                        </form>
                    </td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "No tickets pending approval.";
    }
    ?>
</body>
</html>
