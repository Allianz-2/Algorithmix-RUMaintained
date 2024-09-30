<?php
    if(isset($_POST['submit-approve'])) {
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
                
        $stmt = $conn->prepare('SELECT Status FROM ticket WHERE TicketID = ?');
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param('s', $ticketID);
        $stmt->execute();
        $stmt->bind_result($status);
        $stmt->fetch();
        $user = $_SESSION['role'];
        
        
        if ($user !== null) {
            if ($user == 'HW') {
                $newStatus = 'Confirmed';
            } elseif ($user == 'HS') {
                if ($status == 'Resolved') {
                    $newStatus = 'Closed';
                } else {
                    $newStatus = 'Requisitioned';
                }
            } elseif ($user == 'MS') {
                $newStatus = 'Resolved';
            } else {
                die('Unknown user type');
            }

            $stmt->close();

            // Prepare the SQL statement to update the ticket
            $updateStmt = $conn->prepare('UPDATE ticket SET Status = ? WHERE TicketID = ?');
            if ($updateStmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }

            $updateStmt->bind_param('ss', $newStatus, $ticketID);
            $updateStmt->execute();

            if ($updateStmt->affected_rows === 0) {
                die('Update failed: ' . htmlspecialchars($conn->error));
            }

            echo 'Ticket updated successfully';
            header('Location: 7-TicketStatus.php?ticketID=' . urlencode($ticketID));
            exit();
            $updateStmt->close();
        } else {
            echo 'No ticket found with the given ID';
        }

        $conn->close();
    } 


?>
