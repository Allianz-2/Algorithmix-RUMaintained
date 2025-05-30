<?php
    // Include the database connection file
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    include '../8-PHPTests/connectionAzure.php';


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // Fetch new tickets that are not closed with a limit of 10
// Set the access column and idField based on the role
$access_column = '';
switch ($_SESSION['role']) {
    case 'S':
        $access_column = 'SAccesses';
        $idField = 'StudentID';
        break;
    case 'HW':
        $access_column = 'HWAccesses';
        $idField = 'HouseWardenID';
        break;
    case 'HS':
        $idField = 'ResidenceID';
        $access_column = 'HSAccesses';

        // Get all ResidenceIDs for the Hall Secretary (HS)
        $stmt = $conn->prepare("SELECT ResidenceID FROM residence WHERE HallSecretaryID = ?");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("s", $_SESSION['userID']);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $residenceIDs = [];
            while ($row = $result->fetch_assoc()) {
                $residenceIDs[] = $row['ResidenceID'];
            }
            $stmt->close();
        }

        // If no ResidenceIDs were found, return or handle accordingly
        if (empty($residenceIDs)) {
            die('No residences found for this Hall Secretary.');
        }

        break;
    default:
        $idField = 'ResidenceID';
        $access_column = 'HSAccesses';
}

// Create the SQL query for the HS role with multiple ResidenceIDs
if ($_SESSION['role'] === "HS") {
    // Dynamically create placeholders for each ResidenceID
    $placeholders = implode(',', array_fill(0, count($residenceIDs), '?'));
    
    $sql_tickets = "SELECT TicketID, Description, Status, DateCreated
                    FROM ticket 
                    WHERE $idField IN ($placeholders) AND $access_column = 0 AND Status = 'Confirmed'
                    ORDER BY DateCreated DESC";

    // Prepare the statement
    $tickets_stmt = $conn->prepare($sql_tickets);
    if ($tickets_stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind the ResidenceIDs dynamically
    $tickets_stmt->bind_param(str_repeat('s', count($residenceIDs)), ...$residenceIDs);

} else if ($_SESSION['role'] === "S" ){
    // Query for other roles
    $sql_tickets = "SELECT TicketID, Description, Status, DateCreated
                    FROM ticket 
                    WHERE $idField = ? AND $access_column = 0
                    ORDER BY DateCreated DESC";

    // Prepare the statement
    $tickets_stmt = $conn->prepare($sql_tickets);
    if ($tickets_stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind a single ResidenceID (or StudentID/HouseWardenID)
    $tickets_stmt->bind_param("s", $_SESSION['userID']);
} else if ($_SESSION['role'] === "HW") {
    // Query for other roles
    $sql_tickets = "SELECT TicketID, Description, Status, DateCreated
                    FROM ticket 
                    WHERE $idField = ? AND $access_column = 0 AND Status = 'Open'
                    ORDER BY DateCreated DESC";

    // Prepare the statement
    $tickets_stmt = $conn->prepare($sql_tickets);
    if ($tickets_stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind a single ResidenceID (or StudentID/HouseWardenID)
    $tickets_stmt->bind_param("s", $_SESSION['userID']);

}

// Execute the statement
if (!$tickets_stmt->execute()) {
    die('Execute failed: ' . htmlspecialchars($tickets_stmt->error));
}

// Fetch the results
$tickets_result = $tickets_stmt->get_result();
if ($tickets_result === false) {
    die('Get result failed: ' . htmlspecialchars($tickets_stmt->error));
}




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