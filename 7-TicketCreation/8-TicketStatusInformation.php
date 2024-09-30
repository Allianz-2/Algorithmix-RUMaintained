<?php
if (isset($_GET['ticketID'])) {
    require_once('../8-PHPTests/config.php');

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

    $ticketID = $_GET['ticketID'];

    // Prepare the SQL statement to retrieve ticket information
    $stmt = $conn->prepare('SELECT * FROM ticket WHERE TicketID = ?');
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param('s', $ticketID);
    $stmt->execute();
    $result = $stmt->get_result();
    $ticketInfo = $result->fetch_assoc();
    $stmt->close();

    if ($ticketInfo) {
        $Status = $ticketInfo['Status'];
        $Description = $ticketInfo['Description'];
        $Severity = $ticketInfo['Severity'];
        $DateCreated = $ticketInfo['DateCreated'];
        $DateConfirmed = $ticketInfo['DateConfirmed'];
        $DateRequisitioned = $ticketInfo['DateRequisitioned'];
        $DateResolved = $ticketInfo['DateResolved'];
        $DateClosed = $ticketInfo['DateClosed'];
        $ResidenceID = $ticketInfo['ResidenceID'];
        $StudentID = $ticketInfo['StudentID'];
        $HouseWardenID = $ticketInfo['HouseWardenID'];
        $CategoryID = $ticketInfo['CategoryID'];
        $SAccesses = $ticketInfo['SAccesses'];
        $HSAccesses = $ticketInfo['HSAccesses'];
        $HWAccesses = $ticketInfo['HWAccesses'];




        // Retrieve the Residence name using the ResidenceID
        $stmt = $conn->prepare('SELECT ResName FROM residence WHERE ResidenceID = ?');
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param('s', $ResidenceID);
        $stmt->execute();
        $stmt->bind_result($ResidenceName);
        $stmt->fetch();
        $stmt->close();

        if (empty($ResidenceName)) {
            $ResidenceName = "Unknown Residence";
        }
        

        // Retrieve the Category name using the CategoryID
        $stmt = $conn->prepare('SELECT CategoryName FROM faultcategory WHERE CategoryID = ?');
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param('s', $CategoryID);
        $stmt->execute();
        $stmt->bind_result($categoryName);
        $stmt->fetch();
        $stmt->close();

        if (empty($categoryName)) {
            $CategoryName = "Unknown Category";
        }

        // Retrieve comments from ticketcomment using TicketID
        $stmt = $conn->prepare('SELECT CommentText FROM ticketcomment WHERE TicketID = ?');
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param('s', $ticketID);
        $stmt->execute();
        $result = $stmt->get_result();
        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row['CommentText'];
        }
        $stmt->close();

        // Use the fields and comments as needed
    } else {
        echo "Ticket not found.";
    }
} else {
    echo "No ticket ID provided.";
}
?>
