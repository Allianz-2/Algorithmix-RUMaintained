<?php
    if (isset($_POST['submit'])) {
        session_start();
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


// Fetch all maintenance requests
$query = "select t.TicketID, t.Description, t.DateCreated, u.First_name, u.Lastname
          FROM ticket t
          JOIN user u ON t.StudentID = u.UserID
          WHERE t.Status = 'Confirmed'"; 
$conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');
$results = mysqli_query($conn, $query);

// Function to fetch comments for a ticket
function getTicketComments($conn, $ticketId) {
    $query = "SELECT c.ticketCommentID, c.CommentText, c.DatePosted, u.First_name, u.Lastname, u.role
              FROM ticketcomment c
              JOIN user u ON c.UserID = u.UserID
              order by c.DatePosted DESC
                WHERE c.TicketID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $ticketId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
    }
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hall Secretary Dashboard - Ticket Approval</title>
    <link rel="stylesheet" href="Dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
   
</head>
<body>
    <header>
        <div class="logo">
            <span class="user-welcome">Welcome, <?php echo $_SESSION['user_name']; ?></span>
            <a href="user_profile.php"></a>
        </div>
        <nav>
            <ul>
                <li class="active"><a href="HSDSTicketApproval.php"><i class="fas fa-check-circle"></i> Ticket Approvals</a></li>
                <li><a href="HSDSAnalytics.php"><i class="fas fa-chart-bar"></i> Analytics</a></li>
                <li><a href="#"><i class="fas fa-tasks"></i> Requests</a></li>
                <li><a href="#"><i class="fas fa-building"></i> Residences</a></li>
                <li><a href="HSDSNotifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <a href="#"><i class="fas fa-cog"></i> Settings</a>
            <a href="#" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>
    </header>

    <main role="main">
        <div>
            <div class="header-left">
                <div id="hamburger-icon" class="hamburger-icon">
                    <i class="fas fa-bars"></i>
                </div>
                <h2>Hall Secretary Dashboard</h2>
            </div>
            <div class="logo">
                <img src="/path/to/rumaintained-logo.jpg" alt="rumaintained logo">
            </div>
        </div>


        <div class="content">
            <h2>Ticket Approval</h2>

            <div class="progress-container">
                <div class="progress-bar">
                    <div id="progress" class="progress"></div>
                    <div class="step-wrapper">
                        <div class="step">1</div>
                        <div class="step-label">Create Ticket</div>
                    </div>
                    <div class="step-wrapper">
                        <div class="step">2</div>
                        <div class="step-label">House Warden Approval</div>
                    </div>
                    <div class="step-wrapper">
                        <div class="step active">3</div>
                        <div class="step-label">Hall Secretary Approval</div>
                    </div>
                </div>
            </div>
         
            <h3>Recent Requests</h3>
            <table>
                <tr>
                    <th>Ticket #</th>
                    <th>Date Created</th>
                    <th>Student Number</th>
                    <th>Fault Description</th>
                    <th>Approve</th>
                    <th>Deny</th>
                    <th>Comments</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($results)): ?>
                <tr>
                    <td><a href="#" class="ticket-link"><?php echo $row['TicketID']; ?></a></td>
                    <td><?php echo $row['DateCreated']; ?></td>
                    <td><?php echo $row['StudentID']; ?></td>
                    <td><?php echo $row['Description']; ?></td>
                    <td><a href="#">Approve</a></td>
                    <td><a href="#">Deny</a></td>
                    <td><a href="#" onclick="toggleComments(<?php echo $row['TicketID']; ?>)">View/Add Comments</a></td>
                </tr>
                <tr id="comments-<?php echo $row['TicketID']; ?>" style="display: none;">
                    <td colspan="7">
                        <div class="comments-section">
                            <h4>Comments for Ticket #<?php echo $row['TicketID']; ?></h4>
                            <table class="comments-table">
                                <tr>
                                    <th>Date</th>
                                    <th>User</th>
                                    <th>Comment</th>
                                </tr>
                                <?php 
                                $comments = getComments($conn, $row['TicketID']);
                                foreach ($comments as $comment):
                                ?>
                                <tr>
                                    <td><?php echo $comment['DateCreated']; ?></td>
                                    <td><?php echo $comment['First_name'] . ' ' . $comment['Lastname'] . ' (' . $comment['Role'] . ')'; ?></td>
                                    <td><?php echo $comment['Comment']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                            <form action="add_comment.php" method="post">
                                <input type="hidden" name="ticket_id" value="<?php echo $row['TicketID']; ?>">
                                <textarea name="comment" placeholder="Add a comment" required></textarea>
                                <button type="submit">Add Comment</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerIcon = document.getElementById('hamburger-icon');
            const sidebar = document.getElementById('sidebar');
            const main = document.querySelector('main');

            hamburgerIcon.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                main.classList.toggle('sidebar-collapsed');
            });
        });
        function confirmLogout() {
            return confirm("Are you sure you want to log out?");
        }
    </script>

    <script>
        function confirmLogout() {
            return confirm("Are you sure you want to log out?");
        }

        function toggleComments(ticketId) {
            var commentsRow = document.getElementById('comments-' + ticketId);
            if (commentsRow.style.display === 'none') {
                commentsRow.style.display = 'table-row';
            } else {
                commentsRow.style.display = 'none';
            }
        }
    </script>
</body>
</html>
<?php mysqli_close($conn); ?>