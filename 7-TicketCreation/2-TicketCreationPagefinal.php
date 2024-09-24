<?php


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

    // SQL query to insert the ticket data
    $SQL = "INSERT INTO ticket (Description, Severity, Photo)
            VALUES ('$Description', '$Severity', '$picture')";

    // Executing the SQL query
    if ($conn->query($SQL) === true) {
        echo "<strong class=\"success\">The information was correctly captured!</strong>";
    } else {
        // Error handling for the SQL query
        die("<p class=\"error\">ERROR: Unable to execute the query!</p>");
    }

    // Closing the connection
    $conn->close();
}
?>