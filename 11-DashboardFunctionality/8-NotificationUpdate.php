<?php
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

        if (!empty($residenceIDs)) {
            $idField = 'ResidenceID';
            $update_sql = "UPDATE ticket SET Accesses = 1 WHERE $idField IN (" . implode(',', array_fill(0, count($residenceIDs), '?')) . ")";
            $update_stmt = $conn->prepare($update_sql);
            if ($update_stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $update_stmt->bind_param(str_repeat('s', count($residenceIDs)), ...$residenceIDs);
        }

        } else {
            die('Execute failed: ' . htmlspecialchars($stmt->error));
        }
        
    } else {
        $idField = 'StudentID';
        $accessField = 'SAccesses';
    }

    
    if (isset($_POST['mark_all_as_read'])) {
        require '../8-PHPTests/config.php';

        $conn = mysqli_init();
        $ca_cert_path = "../CACertificate/DigiCertGlobalRootCA.crt.pem"; // Absolute path to the CA cert

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
            header("Location: 4-StudentNotifications.php");
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
        require '../8-PHPTests/config.php';

        $conn = mysqli_init();
        $ca_cert_path = "../CACertificate/DigiCertGlobalRootCA.crt.pem"; // Absolute path to the CA cert

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
            header("Location: 4-StudentNotifications.php");
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