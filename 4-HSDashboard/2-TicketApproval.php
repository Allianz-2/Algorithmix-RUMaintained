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
    <link rel="stylesheet" href="Dashboard.css">


</head>



<?php 
            require '../8-PHPTests/config.php';

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



            $sql = "SELECT ResidenceID FROM residence WHERE HallSecretaryID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $_SESSION['userID']);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            $residences = array();
            while ($row = $result->fetch_assoc()) {
                $residences[] = $row['ResidenceID'];
            }

            if (!empty($residences)) {
                $firstResidence = $residences[0];
                $hallID = substr($firstResidence, 0, 2) . '%';
                // echo $firstTwoCharacters;
            } else {
                echo "No residences found";
            }

            $sql = "SELECT * FROM ticket WHERE ResidenceID LIKE ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die('Failed to prepare statement: ' . $conn->error);
            }
            $stmt->bind_param("s", $hallID);
            if (!$stmt->execute()) {
                die('Failed to execute statement: ' . $stmt->error);
            }
            $result = $stmt->get_result();
            $stmt->close();

            ?>






    <nav id="sidebar" class="sidebar">
        <div class="logo">
            <span class="user-welcome">Welcome, <?php echo $_SESSION['Firstname']; ?></span> <!--  I THINK -->
            <a href="..\6-UserProfileManagementPage\3-ProfileHS.php"><i class="fas fa-user"></i></a>
        </div>
        <ul>
        <li>
            <a href="..\1-GeneralPages\1-Home.php"><i class="fas fa-home"></i> Home</a></li>
            <li class="active"><a href="4-HSDashboard\2-TicketApproval.php"><i class="fas fa-check-circle"></i> Ticket Approvals</a></li>
            <li><a href="..\4-HSDashboard\1-HSRequests.php"><i class="fas fa-tasks"></i> Requests</a></li>
            <li><a href="..\4-HSDashboard\3-HSAnalytics.php"><i class="fas fa-chart-bar"></i> Analytics</a></li>
            <li><a href="HSDSNotifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
            <li><a href="..\4-HSDashboard\5-HSHelp.php"><i class="fas fa-info-circle"></i> Help and Support</a></li>
        </ul>
        <div class="sidebar-footer">
            <p><a href="#"><i class="fas fa-cog"></i> Settings</a></p>
            <p><a href="..\5-UserSignInandRegistration\15-Logout.php" onclick="return confirmLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</a></p>
        </div>
    </nav>
    <main>
        <header>
            <div class="header-left">
                <div id="hamburger-icon" class="hamburger-icon"><i class="fas fa-bars"></i></div>
               <strong>Hall Secretary Dashboard</strong>
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

            <?php
            if ($result->num_rows > 0) {
                echo "<table class='requests-table'>
                        <thead>
                        <tr>
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
                            
                </tr>
                        </tr>
                        </thead>";      // Add comments handling logic here and status logic????? 

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['TicketID']}</td>";
                    echo "<td>{$row['DateCreated']}</td>";
                    echo "<td>{$row['StudentID']}</td>";
                    echo "<td>{$row['Status']}</td>";
                    echo "<td>{$row['Description']}</td>";
                    echo "<td>{$row['Severity']}</td>";
                    echo "<td><a href='#'>Approve</a></td>";
                    echo "<td><a href='#'>Deny</a></td>";
                    echo "<td><a href='#'>Details</a></td>";
                    echo "</tr>";
                }

                echo "</table>";
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
