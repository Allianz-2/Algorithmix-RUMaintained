<?php
require_once("../5-UserSignInandRegistration/14-secure.php"); 
require_once('../8-PHPTests/config.php');
// include '..\11-DashboardFunctionality\2-PermissionApproval.php';

include '../8-PHPTests/connectionAzure.php';


// Handle form submissions for approving and denying tickets
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ticketID = $_POST['ticketID'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        $stmt = $conn->prepare("UPDATE ticket SET Status = 'confirmed' WHERE TicketID = ? AND HouseWardenID = ?");
        $stmt->bind_param("ss", $ticketID, $_SESSION['userID']);
        $stmt->execute();
        $stmt->close();
    } elseif ($action === 'deny') {
        $stmt = $conn->prepare("UPDATE ticket SET Status = 'closed' WHERE TicketID = ? AND HouseWardenID = ?");
        $stmt->bind_param("ss", $ticketID, $_SESSION['userID']);
        $stmt->execute();
        $stmt->close();
    }

    // Redirect to the same page to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch tickets
$sql = "SELECT * FROM ticket WHERE HouseWardenID = ? ORDER BY DateCreated DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['userID']);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Warden Dashboard</title>
    <link rel="icon" href="../Images\General\favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="Dashboard.css">
    <style>

       
    </style>
</head>

<body>
    <nav id="sidebar" class="sidebar">
        <div class="logo">
        <span class="user-welcome">Welcome, <?php echo $_SESSION['Firstname']; ?></span>
            <a href="../6-UserProfileManagementPage\2-ProfileHW.php"><i class="fas fa-user"></i></a> 
        </div>
        <ul>
            <li><a href="../1-GeneralPages\1-Home.php"><i class="fas fa-home"></i>Home</a></li>
            <li><a href="../7-TicketCreation\1-TicketCreation.php"><i class="fas fa-ticket"></i>Create Ticket</a></li>
            <li><a href="../3-HWDashboard\1-HWRequests.php"><i class="fas fa-tools"></i>Ticket Requests</a></li>
            <li class="active"><a href="../3-HWDashboard\2-TicketApproval.php"><i class="fas fa-check-circle"></i>Ticket Approvals</a></li>
            <li><a href="../3-HWDashboard\3-HWAnalytics.php"><i class="fas fa-chart-line"></i>Analytics</a></li>
            <li><a href="../3-HWDashboard\4-HWNotifications.php"><i class="fas fa-bell"></i>Notifications</a></li>
            <li><a href="../3-HWDashboard\5-HWHelp.php"><i class="fas fa-info-circle"></i>Help and Support</a></li>
        </ul>
        <div class="sidebar-footer">
            <p><a href="../6-UserProfileManagementPage\2-ProfileHW.php"><i class="fas fa-cog"></i> Settings</a></p>
            <p><a href="../5-UserSignInandRegistration/15-Logout.php" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</a></p>
        </div>
    </nav>
    <main>
        <header>
            <div class="header-left">
                <div id="hamburger-icon" class="hamburger-icon"><i class="fas fa-bars"></i></div>
                <strong>House Warden Dashboard</strong>
            </div>
            <a href="../1-GeneralPages/1-Home.php">
            <div class="logo"><img src="..\Images\General\93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="rumaintained logo"></div>
            </a>
        </header>

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
                        <div class="step active">2</div>
                        <div class="step-label">House Warden Approval</div>
                    </div>
                    <div class="step-wrapper">
                        <div class="step">3</div>
                        <div class="step-label">Hall Secretary Approval</div>
                    </div>
                </div>
            </div>

            <h3>Recent Requests</h3>

            <?php
            if ($result->num_rows > 0) {
                echo "<table class='requests-table'>
                        <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>Date Created</th>
                            <th>Student Number</th>
                            <th>Status</th>                           
                            <th>Fault Description</th>
                            <th>Severity</th>

                            <th>Details</th>
                        </tr>
                        </thead>
                        <tbody>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['TicketID']}</td>";
                    echo "<td>{$row['DateCreated']}</td>";
                    echo "<td>{$row['StudentID']}</td>";
                    echo "<td>{$row['Status']}</td>";
                    echo "<td>{$row['Description']}</td>";
                    echo "<td>{$row['Severity']}</td>";

                    echo "<td><a href='../7-TicketCreation/7-TicketStatus.php?ticketID=" . urlencode($row['TicketID']) . "'>View</a></td>";
                    echo "</tr>";
                }

                echo "</tbody></table>";
            } else {
                echo "<p>No tickets found</p>";
            }
            ?>

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
</body>
</html>
