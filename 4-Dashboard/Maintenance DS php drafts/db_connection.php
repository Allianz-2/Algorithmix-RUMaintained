<?php
// Check if constants are not already defined before defining them
if (!defined('SERVERNAME')) {
    define('SERVERNAME', 'IS3-DEV.ICT.RU.AC.ZA');
}

if (!defined('USERNAME')) {
    define('USERNAME', 'Algorithmix');
}

if (!defined('PASSWORD')) {
    define('PASSWORD', 'U3fuC7P5');
}

if (!defined('DATABASE')) {
    define('DATABASE', 'Algorithmix');
}

// Create the database connection
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>