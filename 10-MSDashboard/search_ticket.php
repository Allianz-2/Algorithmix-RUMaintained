<?php
require '../8-PHPTests/config.php';

// Initializes MySQLi
$conn = mysqli_init();

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

// Check if ticketID is set and not empty
if (isset($_POST['ticketID']) && !empty($_POST['ticketID'])) {
    $ticketID = mysqli_real_escape_string($conn, $_POST['ticketID']);

    // Your SQL query to search for the ticket by ID
    $query = "SELECT * FROM ticket WHERE TicketID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $ticketID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if there are results
    if (mysqli_num_rows($result) > 0) {
        // Output the results
        echo "<table>";
        echo "<tr>";
        echo "<th>Ticket ID</th>";
        echo "<th>Description</th>";
        echo "<th>Status</th>";
        echo "<th>Severity</th>";
        echo "<th>Date Created</th>";
        echo "<th>Submitted By</th>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['TicketID'] . "</td>";
            echo "<td>" . $row['Description'] . "</td>";
            echo "<td>" . $row['Status'] . "</td>";
            echo "<td>" . $row['Severity'] . "</td>";
            echo "<td>" . date('Y-m-d H:i:s', strtotime($row['DateCreated'])) . "</td>";
            echo "<td>" . $row['Firstname'] . " " . $row['Lastname'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No results found for Ticket ID: $ticketID</p>";
    }
} else {
    echo "<p>Please enter a Ticket ID to search.</p>";
}

// Close the database connection
mysqli_close($conn);
?>