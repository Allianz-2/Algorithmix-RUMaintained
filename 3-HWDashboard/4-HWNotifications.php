<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); 




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RU Maintained Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 20px; }
        h1 { text-align: center; color: #5c4b8a; }
        h2 { color: #343a40; }
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
        .stats {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }
        .stat-box {
            background-color: #E6E6FA; /* Light purple background */
            color: #5c4b8a; /* Dark purple text */
            border-radius: 10px; /* Smooth corners */
            padding: 20px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15); /* Drop shadow */
            text-align: center; /* Centered text */
            flex: 1; /* Equal spacing */
            margin: 10px; /* Margin around boxes */
        }
        /* Additional CSS styles can be added here */
    </style>
</head>
<body>
    <nav id="sidebar" class="sidebar">
        <div class="logo">
            <span class="user-welcome">Welcome, <!-- Add PHP code here for user name --></span>
            <a href="user page"><i class="fas fa-user"></i></a> 
        </div>
        <ul>
            <li><a href="1-MD_MaintenanceRequests.php"><i class="fas fa-tools"></i>Maintenance Requests</a></li>
            <li><a href="2-MD_TaskAssignment.php"><i class="fas fa-clipboard-list"></i>Task Assignment</a></li>
            <li><a href="3-MD_PerformanceAnalytics.php"><i class="fas fa-chart-line"></i>Performance Analytics</a></li>
         <li class="active"><a href="4-MD_Notifications.php"><i class="fas fa-bell"></i>Notifications</a></li>
        </ul>
        <div class="sidebar-footer">
            <p><a href="#"><i class="fas fa-cog"></i> Settings</a></p>
            <p><a href="#" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</a></p>
        </div>
    </nav>

    <main>
        <header>
            <div class="header-left">
                <div id="hamburger-icon" class="hamburger-icon"><i class="fas fa-bars"></i></div>
                <strong>Maintenance Staff Dashboard</strong>
            </div>
            <div class="logo">
                <img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="rumaintained logo">
            </div>
        </header>
        
        <div class="container mt-5">
            <h1>Notifications</h1>


<section>
    <h2><i class="fas fa-ticket-alt"></i> New Tickets</h2>
    <?php
                include '7-NotificationUpdate.php';

            ?>
</section>
            <!-- Recent Comments Section -->
            <section>
                <h2><i class="fas fa-comments"></i> Recent Comments on Tickets</h2>
                <?php
                // Fetch recent comments on tickets (within the last day)
                $sql_comments = "SELECT tc.CommentText, tc.DatePosted, t.TicketID, u.First_name, u.Lastname
                                 FROM ticketcomment tc
                                 JOIN ticket t ON tc.TicketID = t.TicketID
                                 JOIN user u ON tc.UserID = u.UserID
                                 WHERE tc.DatePosted > DATE_SUB(NOW(), INTERVAL 1 DAY)";

                $comments_stmt = $conn->prepare($sql_comments);
                $comments_stmt->execute();
                $comments_result = $comments_stmt->get_result();

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
                // Fetch new assignments for maintenance staff
                $sql_assignments = "SELECT a.AssignmentID, t.TicketID, u.First_name, u.Lastname
                                    FROM assignment a
                                    JOIN ticket t ON a.TicketID = t.TicketID
                                    JOIN maintenancestaff ms ON a.MaintenanceStaffID = ms.MaintenanceStaffID
                                    JOIN user u ON ms.UserID = u.UserID";

                $assignments_stmt = $conn->prepare($sql_assignments);
                $assignments_stmt->execute();
                $assignments_result = $assignments_stmt->get_result();

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

            <script>            document.addEventListener('DOMContentLoaded', function() {
                const hamburgerIcon = document.getElementById('hamburger-icon');
                const sidebar = document.getElementById('sidebar');
                const main = document.querySelector('main');

                hamburgerIcon.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    main.classList.toggle('sidebar-collapsed');
                });
            });</script>

 
</body>
</html>

















