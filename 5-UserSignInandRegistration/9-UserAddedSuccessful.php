<?php
include '../8-PHPTests/config.php';

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

// Insert user into the user table
$userID = $_POST['userID'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$password = $_POST['password'];
$email = $_POST['email'];

$userQuery = "INSERT INTO user (userID, firstname, lastname, password, email, role) VALUES (?, ?, ?, ?, ?, 'S')";
$stmt = $conn->prepare($userQuery);
$stmt->bind_param("sssss", $userID, $firstname, $lastname, $password, $email);
$result = $stmt->execute();
$stmt->close();

if ($result === false) {
    die('Error executing user query: ' . $conn->error);
}

// Retrieve the resID from the form
$resIDs = $_POST['resIDs'];
$resIDArray = explode(',', $resIDs);

// Insert each resID into the student table and retrieve the residence name
foreach ($resIDArray as $resID) {
    $studentQuery = "INSERT INTO student (userID, resID) VALUES (?, ?)";
    $stmt = $conn->prepare($studentQuery);
    $stmt->bind_param("ss", $userID, $resID);
    $result = $stmt->execute();
    $stmt->close();

    if ($result === false) {
        die('Error executing student query: ' . $conn->error);
    }

    // Retrieve the residence name
    $resQuery = "SELECT resName FROM residence WHERE resID = ?";
    $stmt = $conn->prepare($resQuery);
    $stmt->bind_param("s", $resID);
    $stmt->execute();
    $stmt->bind_result($resName);
    $stmt->fetch();
    $stmt->close();

    // Echo the student's name and residence name
    echo "Student {$firstname} {$lastname} was allocated to residence: {$resName}<br>";
}

mysqli_close($conn);
?>