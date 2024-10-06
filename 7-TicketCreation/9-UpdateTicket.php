<?php
    if(isset($_POST['submit-approve'])) {
        include '../8-PHPTests/connectionAzure.php';

        $ticketID = $_POST['ticketID'];
        $datePosted = strval(date("Y-m-d H:i:s"));
        $ticketCommentID = 'TC' . strval(time());

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
                $dateType = 'DateConfirmed';
                $dateUpdate = strval(date("Y-m-d H:i:s"));

            } elseif ($user == 'HS') {
                if ($status == 'Resolved') {
                    $newStatus = 'Closed';
                    $dateType = 'DateClosed';
                    $dateUpdate = strval(date("Y-m-d H:i:s"));

                } else {
                    $newStatus = 'Requisitioned';
                    $dateType = 'DateRequisitioned';
                    $dateUpdate = strval(date("Y-m-d H:i:s"));

                }
            } elseif ($user == 'MS') {
                $newStatus = 'Resolved';
                $dateType = 'DateResolved';
                $dateUpdate = strval(date("Y-m-d H:i:s"));

            } else {
                die('Unknown user type');
            }

            $stmt->close();

            // Prepare the SQL statement to update the ticket
            $sql = "UPDATE ticket SET Status = ?, SAccesses = 0, HWAccesses = 0, HSAccesses = 0, $dateType = ? WHERE TicketID = ?";
            $updateStmt = $conn->prepare($sql);
            if ($updateStmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }

            $updateStmt->bind_param('sss', $newStatus, $datePosted, $ticketID);
            $updateStmt->execute();

            if ($updateStmt->affected_rows === 0) {
                die('Update failed: ' . htmlspecialchars($conn->error));
            }

            if (isset($_POST['new-comment']) && !empty($_POST['new-comment'])) {
                $newComment = $_POST['new-comment'] . "\n" . $_SESSION['Firstname'] . ' ' . $_SESSION['Lastname'] . ' - ' . $_SESSION['userID'] . "\n\n";
                $commentStmt = $conn->prepare('INSERT INTO ticketcomment (TicketCommentID, CommentText, DatePosted, TicketID, UserID) VALUES (?, ?, ?, ?, ?)');
                if ($commentStmt === false) {
                    die('Prepare failed: ' . htmlspecialchars($conn->error));
                }

                $commentStmt->bind_param('sssss', $ticketCommentID, $newComment, $datePosted, $ticketID, $_SESSION['userID']);
                $commentStmt->execute();

                if ($commentStmt->affected_rows === 0) {
                    die('Insert failed: ' . htmlspecialchars($conn->error));
                }

                $commentStmt->close();
            }

            if (isset($_POST['severity']) && !empty($_POST['severity'])) {
                $newSeverity = $_POST['severity'];
                $updateSeverityStmt = $conn->prepare('UPDATE ticket SET Severity = ? WHERE TicketID = ?');
                if ($updateSeverityStmt === false) {
                    die('Prepare failed: ' . htmlspecialchars($conn->error));
                }

                $updateSeverityStmt->bind_param('ss', $newSeverity, $ticketID);
                $updateSeverityStmt->execute();

                if ($updateSeverityStmt->affected_rows === 0) {
                    die('Update failed: ' . htmlspecialchars($conn->error));
                }

                $updateSeverityStmt->close();
            }
            $updateStmt->close();
            $conn->close();

            if ($_SERVER['SERVER_NAME'] == 'localhost') {
                $_SESSION['alert'] = "Ticket updated successfully";
                header("Location: 10-SendEmailUpdate.php?ticketID=" . urlencode($ticketID));

            } else {
                $_SESSION['alert'] = "Ticket updated successfully";
                header('Location: 7-TicketStatus.php?ticketID=' . urlencode($ticketID));
            }


            exit();
            // echo '<script>alert("Ticket updated successfully");</script>';
            // header('Location: 7-TicketStatus.php?ticketID=' . urlencode($ticketID));
            // exit();
        } else {
            echo 'No ticket found with the given ID';
        }

    }










    else if (isset($_POST['submit-reject'])) {
        include '../8-PHPTests/connectionAzure.php';

        $ticketID = $_POST['ticketID'];
        $stmt = $conn->prepare('SELECT Status FROM ticket WHERE TicketID = ?');
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }


        $stmt->bind_param('s', $ticketID);
        $stmt->execute();
        $stmt->bind_result($status);
        $stmt->fetch();
        $stmt->close();

        if ($status !== null) {
            $newStatus = 'Rejected';

            // Prepare the SQL statement to update the ticket
            $updateStmt = $conn->prepare('UPDATE ticket SET Status = ?, SAccesses = 0, HWAccesses = 0, HSAccesses = 0, $dateType = ? WHERE TicketID = ?');
            if ($updateStmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
            }

            $updateStmt->bind_param('ss', $newStatus, $ticketID);
            $updateStmt->execute();

            if ($updateStmt->affected_rows === 0) {
            die('Update failed: ' . htmlspecialchars($conn->error));
            }

            include "10-SendEmailUpdate.php";
            echo '<script>alert("Ticket updated successfully");</script>';
            header('Location: 7-TicketStatus.php?ticketID=' . urlencode($ticketID));
            exit();
            $updateStmt->close();
        } else {
            echo 'No ticket found with the given ID';
        }

        $conn->close();

    }








?>
