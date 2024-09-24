<?php
// Function to generate the next ticket ID
function getNextTicketId($conn) {
    // Query to get the last ticket ID from the ticket table
    $query = "SELECT ticketid FROM ticket ORDER BY ticketid DESC LIMIT 1";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Fetch the last ticket ID
        $row = $result->fetch_assoc();
        $lastTicketId = $row['ticketid'];

        // Extract the numeric part of the ticketid (assuming format 't000051')
        $numericPart = intval(substr($lastTicketId, 1));

        // Increment the numeric part
        $newTicketNumber = $numericPart + 1;

        // Format the new ticket ID (e.g., t000052)
        $newTicketId = 't' . str_pad($newTicketNumber, 6, '0', STR_PAD_LEFT);

        return $newTicketId;
    } else {
        // If no tickets exist, start from t000001
        return 't000001';
    }
}

// Check if the form has been submitted
if (isset($_REQUEST['submit'])) {

    // Get form input values
    $Description = $_REQUEST['Description'];
    $Severity = $_REQUEST['Severity'];

    // Handling the file upload
    $picture = time() . "_" . $_FILES['picture']['name'];
    $destination = "images/" . $picture;
    move_uploaded_file($_FILES['picture']['tmp_name'], $destination);

    // Include database configuration and establish connection
    require_once('config.php');
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Error handling for the database connection
    if ($conn->connect_error) {
        die("<p class=\"error\">ERROR: Unable to connect to the database!</p>");
    }

    // --- GENERATING NEXT TICKET ID ---
    $newTicketId = getNextTicketId($conn);

    // SQL query to insert the ticket data (including the new ticket ID)
    $SQL = "INSERT INTO ticket (ticketid, Description, Severity, Photo)
            VALUES ('$newTicketId', '$Description', '$Severity', '$picture')";

    // Executing the SQL query
    if ($conn->query($SQL) === true) {
        echo "<strong class=\"success\">The information was correctly captured! Ticket ID: $newTicketId</strong>";
    } else {
        // Error handling for the SQL query
        die("<p class=\"error\">ERROR: Unable to execute the query!</p>");
    }

    // Closing the connection
    $conn->close();
}
?>
