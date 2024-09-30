<?php
    // Include the database connection file
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    require '../8-PHPTests/config.php';

    $conn = mysqli_init();
    $ca_cert_path = "../CACertificate/DigiCertGlobalRootCA.crt.pem"; // Absolute path to the CA cert

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

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // Fetch new tickets that are not closed with a limit of 10
    $access_column = '';
    switch ($_SESSION['role']) {
        case 'S':
            $access_column = 'SAccesses';
            $idField = 'HallSecretaryID';
            break;
        case 'HW':
            $access_column = 'HWAccesses';
            $idField = 'HouseWardenID';
            break;
        case 'HS':
            $access_column = 'HSAccesses';
            $idField = 'HallSecretaryID';
            break;
        default:
            $access_column = 'SAccesses';
            $idField = 'HallSecretaryID';
    }

    $sql_tickets = "SELECT TicketID, Description, Status, DateCreated
                    FROM ticket 
                    WHERE $idField = ? AND $access_column = 0
                    ORDER BY DateCreated DESC";


    $tickets_stmt = $conn->prepare($sql_tickets);

    if ($tickets_stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $tickets_stmt->bind_param("s", $_SESSION['userID']);
    $tickets_stmt->execute();
    $tickets_result = $tickets_stmt->get_result();

    // Variable to track if there's an approved ticket

    if ($tickets_result->num_rows > 0) {
        while ($ticket = $tickets_result->fetch_assoc()) {
            echo "<div class='notification-item unread'>";
            echo "<div class='notification-icon'><i class='fas fa-ticket-alt'></i></div>";
            echo "<div class='notification-content'>";
            echo "<p><strong>Ticket ID:</strong> " . $ticket['TicketID'] . "</p>";
            echo "<p><strong>Description:</strong> " . $ticket['Description'] . "</p>";
            echo "<p><strong>Status:</strong> " . $ticket['Status'] . "</p>";
            echo "<p><strong>Date Created:</strong> " . date('F j, Y, g:i a', strtotime($ticket['DateCreated'])) . "</p>";
            echo "</div>";
            echo "<div class='notification-actions'>";
            echo "<button class='btn btn-primary' onclick='viewTicket(" . $ticket['TicketID'] . ")'>View Ticket</button>";
            echo "<form method='POST' action='' style='display:inline;'>";
            echo "<input type='hidden' name='ticketID' value='" . $ticket['TicketID'] . "'>";
            echo "<button type='submit' name='mark_as_read' class='btn btn-secondary'>Mark as Read</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";

        }

        // Close the statements
        $tickets_stmt->close();

        // Close the connection
        $conn->close();
    } else {
        echo "<p>No new tickets available.</p>";
    }
?>