<?php
    // Check if the form has been submitted by looking for the 'submit' button press
    if (isset($_POST['submit'])) {
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

        $ticketID = 'T' . strval(time());
        $status = $_POST['status'];
        $description =  $_POST['description'];
        $severity = $_POST['severity'];
        $date_created = strval(date("Y-m-d H:i:s"));
        // $hallWardenID = "SHWS6666";
        // $categoryID = "CE049";
        $studentID = $_SESSION["userID"];
        $categoryID = $_POST['category'];
        $comment = $_POST['comments'];

        $SQL_res = $conn->prepare("
            SELECT r.ResidenceID, r.HouseWardenID 
            FROM residence r
            JOIN student s ON r.ResidenceID = s.ResidenceID
            WHERE s.UserID = ?
        ");
        $SQL_res->bind_param("s", $studentID);

        if ($SQL_res->execute()) {
            $SQL_res->bind_result($resID, $hallWardenID);
            $SQL_res->fetch();
            $SQL_res->close();
        } else {
            die('Execute failed: ' . htmlspecialchars($SQL_res->error));
        }


        $SQL = $conn->prepare("INSERT INTO ticket (TicketID, Status, Description, Severity, DateCreated, ResidenceID, StudentID, HouseWardenID, CategoryID)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if ($SQL === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        
        $SQL->bind_param("sssssssss", $ticketID, $status, $description, $severity,$date_created, $resID, $studentID, $hallWardenID, $categoryID);
        if ($SQL->execute()) {
                if (isset($comment)) {
                    $ticketCommentID = 'TC' . strval(time());
                    $datePosted = strval(date("Y-m-d H:i:s"));

                    $SQL_comment = $conn->prepare("
                        INSERT INTO ticketcomment (TicketCommentID, CommentText, DatePosted, TicketID, UserID)
                        VALUES (?, ?, ?, ?, ?)
                    ");

                    if ($SQL_comment === false) {
                        die('Prepare failed: ' . htmlspecialchars($conn->error));
                    }

                    $SQL_comment->bind_param("sssss", $ticketCommentID, $comment, $datePosted, $ticketID, $studentID);

                    if (!$SQL_comment->execute()) {
                        echo "<script>
                            alert('Comment insertion failed!');
                            </script>";
                        } 
                    $SQL_comment->close();
                }
            echo "<script>
                alert('Ticket inserted successfully');
                </script>";
        } else {
            echo "<script>
            alert('Ticket creation failed!');
            </script>";

            die('Execute failed: ' . htmlspecialchars($SQL->error));
        }

        unset($_POST['submit']);

        $SQL->close();
        $conn->close();

    }
?>

