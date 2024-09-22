<?php
$Description = $_REQUEST['Description'];
$Severity = $_REQUEST['Severity'];
$Status = $_REQUEST['Status'];
$DateCreated = date('Y-m-d H:i:s'); 
$DateConfirmed = date('Y-m-d H:i:s'); 
$DateRequisitioned = date('Y-m-d H:i:s'); 
$DateResolved = date('Y-m-d H:i:s'); 
$DateClosed = date('Y-m-d H:i:s'); 



// Include database credentials
require_once('config.php');



// Create connection
$conn = new mysqli(SERVERNAME,Username,Password,Database);

// Check connection
if ($conn->connect_error) {
    die("Unable to connect to the database: " . $conn->connect_error);
}

// Prepare SQL query
$sql = "INSERT INTO ticket (Description, Severity,Status,DateCreated,DateConfirmed,DateRequisitioned,DateResolved,DateClosed) 
        VALUES ('$Description', '$Severity','$Status','$DateCreated','$DateConfirmed','$da)";

// Execute query
if ($conn->query($sql) === TRUE) {
    echo "The ticket was added successfully";
} else {
    die("Unable to add to the database: " . $conn->error);
}

// Close connection
$conn->close();
?>
