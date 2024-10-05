<?php
if (isset($_GET['ticketID'])) {
    include '../8-PHPTests/connectionAzure.php';

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

        // Retrieve the first and last name of the user who created the ticket
        $userID = $StudentID ? $StudentID : $HouseWardenID;
        $stmt = $conn->prepare('SELECT Firstname, Lastname FROM user WHERE UserID = ?');
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param('s', $userID);
        $stmt->execute();
        $stmt->bind_result($firstName, $lastName);
        $stmt->fetch();
        $stmt->close();

        if (empty($firstName) || empty($lastName)) {
            $firstName = "Unknown";
            $lastName = "User";
        }



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
