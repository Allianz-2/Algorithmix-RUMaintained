<?php
require_once('config.php');
$conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database'); // Ensure you have your DB connection

$message = ''; // Initialize message variable

if (isset($_POST['ticketid']) && isset($_POST['action'])) {
    $ticketid = $_POST['ticketid'];  // The ticket ID being approved or rejected
    $action = $_POST['action'];  // The action, either 'approve' or 'reject'

    // Determine the new status based on the action
    if ($action == 'approve') {
        $new_status = 'confirmed';  // Approved tickets go from 'open' to 'confirmed'
        $message = "Ticket approved successfully.";
    } elseif ($action == 'reject') {
        $new_status = 'closed';  // Rejected tickets are marked as 'closed'
        $message = "Ticket rejected successfully.";
    }

    // Update ticket status in the database
    $query = "UPDATE ticket SET status = '$new_status' WHERE ticketid = '$ticketid' AND status = 'open'";

    if (mysqli_query($conn, $query)) {
        // Set a success message (this will be echoed in the approval page)
        $message = $message ?: "Ticket processed successfully.";
    } else {
        // Set error message
        $message = "Error updating ticket: " . mysqli_error($conn);
    }
} else {
    $message = "Invalid request.";
}

// Echo the message for display on the approval page
echo "<div class='message'>" . htmlspecialchars($message) . "</div>";
?>
