<?php
// Include database credentials and establish connection
require_once('config.php'); 

// Create connection
$conn = new mysqli(SERVERNAME, Username,Password, Database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch ticket data
$sql = "SELECT t.description, r.ResName, t.Status, t.DateCreated, t.Severity
        FROM ticket t
        JOIN residence r ON t.ticketResidenceID = r.residenceID";

$result = $conn->query($sql);

// Check if any rows are returned from the database
if ($result->num_rows > 0) {
    // Output each row of the data
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td><input type='checkbox' class='select-ticket'></td>";
        echo "<td>" . $row['description'] . "</td>";
        echo "<td>" . $row['ResName'] . "</td>";
        echo "<td>" . $row['Status'] . "</td>";
        echo "<td>" . $row['DateCreated'] . "</td>";
        echo "<td>" . $row['Severity'] . "</td>";
        echo "<td><a href='view_ticket.php?id=" . $row['id'] . "'>View</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>No tickets found</td></tr>";
}

// Close the connection
$conn->close();
?>