<?php
    include '../8-PHPTests/connectionAzure.php';

    if ($_SESSION['role'] == 'HW') {
        $idField = 'HouseWardenID';
        $accessField = 'HWAccesses';

    } elseif ($_SESSION['role'] == 'HS') {
        $idField = 'ResidenceID';
        $accessField = 'HSAccesses';

        $stmt = $conn->prepare("SELECT ResidenceID FROM residence WHERE HallSecretaryID = ?");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("s", $_SESSION['userID']);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $residenceIDs = [];
            while ($row = $result->fetch_assoc()) {
                $residenceIDs[] = $row['ResidenceID'];
            }
            $stmt->close();
        }
        
    } else {
        $idField = 'StudentID';
        $accessField = 'SAccesses';
    }


    if (isset($_POST['mark_all_as_read'])) {
        include '../8-PHPTests/connectionAzure.php';


        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Prepare the SQL statement to update the Accesses field for all tickets associated with the userID
        // Determine the appropriate ID field based on the user's role
        

        $update_sql = "UPDATE ticket SET $accessField = 1 WHERE $idField = ?";
        $update_stmt = $conn->prepare($update_sql);
        if ($update_stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $update_stmt->bind_param("s", $_SESSION['userID']);
        if ($update_stmt->execute()) {
            // Redirect back to the notifications page with a success message
            $_SESSION['alert'] = "All tickets marked as read successfully.";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            // Handle execution error
            die('Execute failed: ' . htmlspecialchars($update_stmt->error));
        }

        // Close the statements
        $update_stmt->close();
        $conn->close();
        }







    if (isset($_POST['ticketID'])) {
        include '../8-PHPTests/connectionAzure.php';

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $ticketID = $_POST['ticketID'];

        // Prepare the SQL statement to update the Accesses field
        $update_sql = "UPDATE ticket SET $accessField = $accessField + 1 WHERE TicketID = ?";
        $update_stmt = $conn->prepare($update_sql);
        if ($update_stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $update_stmt->bind_param("s", $ticketID);
        if ($update_stmt->execute()) {
            // Redirect back to the notifications page with a success message
            $_SESSION['alert'] = "Ticket marked as read successfully.";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            // Handle execution error
            die('Execute failed: ' . htmlspecialchars($update_stmt->error));
        }

        // Close the statements
        $update_stmt->close();
        $conn->close();

    }

    // Close the connection
?>
