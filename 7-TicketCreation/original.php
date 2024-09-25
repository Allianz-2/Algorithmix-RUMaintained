<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to generate the next ticket ID
function getNextTicketId($conn) {
    // Query to get the last ticket ID from the ticket table
    $query = "SELECT ticketid FROM ticket ORDER BY ticketid DESC LIMIT 1";
    $result = $conn->query($query);

    if ($result === false) {
        die("<p class=\"error\">ERROR: Query failed to execute: " . $conn->error . "</p>");
    }

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
    echo "<p>Form submitted!</p>"; // Debug message

    // Get form input values
    if (empty($_REQUEST['Description']) || empty($_REQUEST['Severity'])) {
        die("<p class=\"error\">ERROR: Description and Severity are required fields!</p>");
    }
    
    $Description = $_REQUEST['Description'];
    $Severity = $_REQUEST['Severity'];
    echo "<p>Form Data - Description: $Description, Severity: $Severity</p>"; // Debug message

    // Handling the file upload
    if (!isset($_FILES['picture'])) {
        die("<p class=\"error\">ERROR: No file was uploaded!</p>");
    }

    $picture = time() . "_" . $_FILES['picture']['name'];
    $destination = "images/" . $picture;

    // Check if the file upload is successful
    if (!move_uploaded_file($_FILES['picture']['tmp_name'], $destination)) {
        die("<p class=\"error\">ERROR: File upload failed. Please check file permissions or try again.</p>");
    }
    echo "<p>File uploaded successfully: $picture</p>"; // Debug message

    // Include database configuration and establish connection
    require_once('config.php');
    $conn = new mysqli(SERVERNAME, Username, Password, Database);

    // Error handling for the database connection
    if ($conn->connect_error) {
        die("<p class=\"error\">ERROR: Unable to connect to the database! " . $conn->connect_error . "</p>");
    }
    echo "<p>Database connection successful!</p>"; // Debug message

    // --- GENERATING NEXT TICKET ID ---
    $newTicketId = getNextTicketId($conn);
    echo "<p>New Ticket ID generated: $newTicketId</p>"; // Debug message

    // SQL query to insert the ticket data (including the new ticket ID)
    $SQL = "INSERT INTO ticket (ticketid, Description, Severity, Photo)
            VALUES ('$newTicketId', '$Description', '$Severity', '$picture')";

    // Executing the SQL query
    if ($conn->query($SQL) === true) {
        echo "<strong class=\"success\">The information was correctly captured! Ticket ID: $newTicketId</strong>";
    } else {
        // Error handling for the SQL query
        die("<p class=\"error\">ERROR: Unable to execute the query. " . $conn->error . "</p>");
    }

    // Closing the connection
    if (!$conn->close()) {
        die("<p class=\"error\">ERROR: Unable to close the database connection!</p>");
    }

    echo "done@"; // Final debug message
}
?>
