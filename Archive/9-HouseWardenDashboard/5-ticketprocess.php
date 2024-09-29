<?php
// Include database connection
require_once('config.php');
$conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ticketId = isset($_POST['ticketid']) ? intval($_POST['ticketid']) : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    // Validate the action
    if ($ticketId > 0 && in_array($action, ['approve', 'reject'])) {
        if ($action == 'approve') {
            // Approve the ticket
            $query = "UPDATE ticket SET Status = 'Resolved' WHERE TicketID = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $ticketId);
        } else {
            // Reject the ticket
            $query = "UPDATE ticket SET Status = 'Rejected' WHERE TicketID = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $ticketId);
        }

        if ($stmt->execute()) {
            $message = "Ticket successfully " . ($action == 'approve' ? 'approved' : 'rejected') . ".";
        } else {
            $message = "Error updating ticket: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Invalid ticket ID or action.";
    }
} else {
    $message = "No action taken.";
}

// Redirect back to the approval page
header("Location: 4-Ticketapproval.php?message=" . urlencode($message));
exit;
?>
