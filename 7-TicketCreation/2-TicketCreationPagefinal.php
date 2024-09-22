<?php
$Description = $_REQUEST['Description'];
$Severity = $_REQUEST['Severity'];
$Status = $_REQUEST['Status'];
// Include database credentials
require_once('config.php');



// Create connection
$conn = new mysqli(SERVERNAME,Username,Password,Database);

// Check connection
if ($conn->connect_error) {
    die("Unable to connect to the database: " . $conn->connect_error);
}

// Prepare SQL query
$sql = "INSERT INTO ticket (Description, Severity) 
        VALUES ('$Description', '$Severity')";

// Execute query
if ($conn->query($sql) === TRUE) {
    echo "The ticket was added successfully";
} else {
    die("Unable to add to the database: " . $conn->error);
}

// Close connection
$conn->close();
?>
