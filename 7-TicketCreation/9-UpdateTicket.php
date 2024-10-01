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

            if (isset($_POST['new-comment']) && !empty($_POST['new-comment'])) {
                $newComment = $_POST['new-comment'];
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














            echo 'Ticket updated successfully';
            header('Location: 7-TicketStatus.php?ticketID=' . urlencode($ticketID));
            exit();
            $updateStmt->close();
        } else {
            echo 'No ticket found with the given ID';
        }

        $conn->close();
    }

    if (isset($_POST['submit-reject'])) {
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
