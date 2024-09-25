<?php
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ticketId = $_POST['ticket_id'];
    $comment = $_POST['comment'];
    $userId = $_SESSION['user_id']; // Assume user ID is stored in session

    $conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE);
    
    $query = "INSERT INTO comments (TicketID, UserID, Comment, DateCreated) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iis", $ticketId, $userId, $comment);
    
    if (mysqli_stmt_execute($stmt)) {
        // Comment added successfully
        header("Location: HSDSTicketApproval.php");
    } else {
        // Error handling
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>