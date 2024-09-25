
<?php
// Include the database connection file
include 'db_connection.php';

// Fetch new tickets that are not closed with a limit of 10
$sql_tickets = "SELECT t.TicketID, t.Description, t.Status, t.DateCreated, u.First_name, u.Lastname, r.ResName
                FROM ticket t
                JOIN user u ON t.StudentID = u.UserID
                JOIN residence r ON t.ResidenceID = r.ResidenceID
                WHERE t.Status != 'Closed'
                ORDER BY t.DateCreated DESC
                LIMIT 10";

$tickets_stmt = $conn->prepare($sql_tickets);
$tickets_stmt->execute();
$tickets_result = $tickets_stmt->get_result();

// Fetch recent comments on tickets (within the last day)
$sql_comments = "SELECT tc.CommentText, tc.DatePosted, t.TicketID, u.First_name, u.Lastname
                 FROM ticketcomment tc
                 JOIN ticket t ON tc.TicketID = t.TicketID
                 JOIN user u ON tc.UserID = u.UserID
                 WHERE tc.DatePosted > DATE_SUB(NOW(), INTERVAL 1 DAY)";

$comments_stmt = $conn->prepare($sql_comments);
$comments_stmt->execute();
$comments_result = $comments_stmt->get_result();

// Fetch new assignments for maintenance staff
$sql_assignments = "SELECT a.AssignmentID, t.TicketID, u.First_name, u.Lastname
                    FROM assignment a
                    JOIN ticket t ON a.TicketID = t.TicketID
                    JOIN maintenancestaff ms ON a.MaintenanceStaffID = ms.MaintenanceStaffID
                    JOIN user u ON ms.UserID = u.UserID";

$assignments_stmt = $conn->prepare($sql_assignments);
$assignments_stmt->execute();
$assignments_result = $assignments_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Dashboard Notifications</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .notification {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .notification.high-priority {
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        h1, h2 {
            color: #343a40;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Maintenance Dashboard Notifications</h1>

        <!-- New Tickets Section -->
        <section>
            <h2><i class="fas fa-ticket-alt"></i> New Tickets</h2>
            <?php
            if ($tickets_result->num_rows > 0) {
                while ($ticket = $tickets_result->fetch_assoc()) {
                    // Highlight if the ticket is high priority
                    echo "<div class='notification'>";
                    echo "<p><strong>Ticket ID:</strong> " . $ticket['TicketID'] . "</p>";
                    echo "<p><strong>Description:</strong> " . $ticket['Description'] . "</p>";
                    echo "<p><strong>Status:</strong> " . $ticket['Status'] . "</p>";
                    echo "<p><strong>Reported by:</strong> " . $ticket['First_name'] . " " . $ticket['Lastname'] . "</p>";
                    echo "<p><strong>Residence:</strong> " . $ticket['ResName'] . "</p>";
                    echo "<p><strong>Date Created:</strong> " . date('F j, Y, g:i a', strtotime($ticket['DateCreated'])) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No new tickets available.</p>";
            }
            ?>
        </section>

        <!-- Recent Comments Section -->
        <section>
            <h2><i class="fas fa-comments"></i> Recent Comments on Tickets</h2>
            <?php
            if ($comments_result->num_rows > 0) {
                while ($comment = $comments_result->fetch_assoc()) {
                    echo "<div class='notification'>";
                    echo "<p><strong>Ticket ID:</strong> " . $comment['TicketID'] . "</p>";
                    echo "<p><strong>Comment:</strong> " . $comment['CommentText'] . "</p>";
                    echo "<p><strong>Posted by:</strong> " . $comment['First_name'] . " " . $comment['Lastname'] . "</p>";
                    echo "<p><strong>Date Posted:</strong> " . date('F j, Y, g:i a', strtotime($comment['DatePosted'])) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No recent comments available.</p>";
            }
            ?>
        </section>

        <!-- New Assignments Section -->
        <section>
            <h2><i class="fas fa-tasks"></i> New Assignments for Maintenance Staff</h2>
            <?php
            if ($assignments_result->num_rows > 0) {
                while ($assignment = $assignments_result->fetch_assoc()) {
                    echo "<div class='notification'>";
                    echo "<p><strong>Assignment ID:</strong> " . $assignment['AssignmentID'] . "</p>";
                    echo "<p><strong>Ticket ID:</strong> " . $assignment['TicketID'] . "</p>";
                    echo "<p><strong>Assigned to:</strong> " . $assignment['First_name'] . " " . $assignment['Lastname'] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No new assignments available.</p>";
            }
            ?>
        </section>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
