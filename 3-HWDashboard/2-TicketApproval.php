<?php
require_once("../5-UserSignInandRegistration/14-secure.php"); 
require_once('../8-PHPTests/config.php');

// Initialize MySQLi
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
$sql = "SELECT * FROM ticket WHERE Status= 'Open' AND HouseWardenID = ?";
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
    <title>RU Maintained Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="Dashboard.css">
    <style>

        h1 {
            text-align: center;
            color: #81589a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #81589a;
            color: #fff;
        }
    </style>
</head>

<body>
    <nav id="sidebar" class="sidebar">
        <div class="logo">
            <span class="user-welcome">Welcome, </span>
            <a href="user page"><i class="fas fa-user"></i></a> 
        </div>
        <ul>
            <li><a href="../1-GeneralPages/1-Home.php"><i class="fas fa-home"></i>Home</a></li>
            <li class="active"><a href="#"><i class="fas fa-tasks"></i>Ticket Approvals</a></li>
            <li><a href="HSAnalyticsFinal.php"><i class="fas fa-chart-bar"></i>Analytics</a></li>
            <li><a href="HSDSRequests.php"><i class="fas fa-clipboard-list"></i>Requests</a></li>
            <li><a href="#"><i class="fas fa-bell"></i>Notifications</a></li>
            <li><a href="1-GeneralPages/1-Home.php"><i class="fas fa-home"></i>Home</a></li>
        </ul>
        <div class="sidebar-footer">
            <p><a href="#"><i class="fas fa-cog"></i> Settings</a></p>
            <p><a href="../5-UserSignInandRegistration/15-Logout.php" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</a></p>
        </div>
    </nav>
    <main>
        <header>
            <div class="header-left">
                <div id="hamburger-icon" class="hamburger-icon"><i class="fas fa-bars"></i></div>
                <strong>House Warden Dashboard</strong>
            </div>
            <div class="logo"><img src="..\Images\General\93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="rumaintained logo"></div>
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
                            <th>Approve</th>
                            <th>Deny</th>
                            <th>Details</th>
                            <th>Comments</th>
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
                    echo "<td>
                            <form method='POST'>
                                <input type='hidden' name='ticketID' value='{$row['TicketID']}'>
                                <input type='hidden' name='action' value='approve'>
                                <button type='submit'>Approve</button>
                            </form>
                          </td>";
                    echo "<td>
                            <form method='POST'>
                                <input type='hidden' name='ticketID' value='{$row['TicketID']}'>
                                <input type='hidden' name='action' value='deny'>
                                <button type='submit'>Deny</button>
                            </form>
                          </td>";
                    echo "<td><a href='#'>Details</a></td>";
                    echo "<td></td>"; // Placeholder for comments
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
