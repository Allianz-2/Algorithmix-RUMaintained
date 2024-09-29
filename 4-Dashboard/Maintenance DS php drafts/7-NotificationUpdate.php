<?php
            // Include the database connection file
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        require '../../8-PHPTests/config.php';

        $conn = mysqli_init();
        $ca_cert_path = "../../CACertificate/DigiCertGlobalRootCA.crt.pem"; // Absolute path to the CA cert

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

        // Fetch new tickets that are not closed with a limit of 10
        $sql_tickets = "SELECT t.TicketID, t.Description, t.Status, t.DateCreated, u.Firstname, u.Lastname, r.ResName
                        FROM ticket t
                        JOIN user u ON t.StudentID = u.UserID
                        JOIN residence r ON t.ResidenceID = r.ResidenceID
                        WHERE t.Status = 'Requisitioned' AND t.Accesses = 0
                        ORDER BY t.DateCreated DESC";

        $tickets_stmt = $conn->prepare($sql_tickets);
        $tickets_stmt->execute();
        $tickets_result = $tickets_stmt->get_result();

        // Variable to track if there's an approved ticket

        if ($tickets_result->num_rows > 0) {
            while ($ticket = $tickets_result->fetch_assoc()) {

                echo "<div class='notification'>";
                echo "<p><strong>Ticket ID:</strong> " . $ticket['TicketID'] . "</p>";
                echo "<p><strong>Description:</strong> " . $ticket['Description'] . "</p>";
                echo "<p><strong>Status:</strong> " . $ticket['Status'] . "</p>";
                echo "<p><strong>Reported by:</strong> " . $ticket['Firstname'] . " " . $ticket['Lastname'] . "</p>";
                echo "<p><strong>Residence:</strong> " . $ticket['ResName'] . "</p>";
                echo "<p><strong>Date Created:</strong> " . date('F j, Y, g:i a', strtotime($ticket['DateCreated'])) . "</p>";
                echo "</div>";

                $update_sql = "UPDATE ticket SET Accesses = Accesses + 1 WHERE TicketID = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("s", $ticket['TicketID']);
                $update_stmt->execute();

            }

            // Close the statements
            $tickets_stmt->close();
            $update_stmt->close();

            // Close the connection
            $conn->close();
        } 
        else {
            echo "<p>No new tickets available.</p>";
        }
        ?>