<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 0px;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            color: #333333;
            background-color: #f2f2f2;
        }

        .sidebar {
            width: var(--sidebar-width);
            background-color: #81589a;
            color: white;
            height: 100vh;
            position: fixed;
            transition: all 0.3s;
            left: 0;
    
        }

        .sidebar a {
    color: white;
    text-decoration: none;
}

        .sidebar.collapsed {
            left: calc(-1 * var(--sidebar-width));
            display: none;
        }

        .sidebar .logo {
            padding: 20px;
            text-align: right;
        }

        .sidebar .logo a {
            color: white;  /* Change user icon to white */
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar li {
            padding: 15px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .sidebar li:hover, .sidebar li.active {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar .badge {
            background-color: purple;
            color: white;
            padding: 2px 6px;
            border-radius: 50%;
            font-size: 0.8em;
            margin-left: 5px;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 20px;
            font-size: 16px;
        }

        .sidebar li i, .sidebar-footer button i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        main {
            flex-grow: 1;
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: margin-left 0.3s;
        }

        main.sidebar-collapsed {
            margin-left: 0;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background-color: white;  /* Change header to a banner style */
            color: #333333;
            padding: 10px 20px;
            /* Adjust for padding */
        }

        .hamburger-icon {
            font-size: 24px;
            cursor: pointer;
            display: inline-block;
            padding: 10px;
        }

        .filters {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-group select {
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #d1d5db;
            background-color: white;
            margin-right: 10px;

        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .charts {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-box {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            flex: 1;
            text-align: center;
        }

        table {
            text-align: center;
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: center;
            border: 1px solid #ddd;
            padding: 12px;
            text-align: middle;
        }

        th {
            background-color: white;
        }


.logo img {
    max-height: 60px;  /* Adjust this value as needed */
    width: auto;
    object-fit: contain;
}
.user-welcome {
    align-items: center;
    text-align: left;
    margin-right: 20px;
}   

.ticketButton {
    padding: 10px 20px; 
    font-size: 1rem; 
    font-weight: bold;
    border: none;
    border-radius: 5px;
    background-color: #81589a;
    color: #fff;
    cursor: pointer;
}

button {
    padding: 5px 10px; 
    font-size: 1rem; 
    font-weight: bold;
    border: none;
    border-radius: 5px;
    background-color: #81589a;
    color: #ffffff;
    cursor: pointer;
}
    </style>
</head>
<body>

<?php
    require '../8-PHPTests/config.php';

            
    $conn = mysqli_init(); 
    if (!file_exists($ca_cert_path)) {
        die("CA file not found: " . $ca_cert_path);
    }
    mysqli_ssl_set($conn, NULL, NULL, $ca_cert_path, NULL, NULL);
    mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL);

    if (mysqli_connect_errno()) {
        die('Failed to connect to MySQL: ' . mysqli_connect_error());
    }



    $sql = "SELECT * FROM ticket WHERE StudentID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['userID']);
    $stmt->execute();
    $result = $stmt->get_result(); // Fetch results using get_result()
    $stmt->close(); 



?>

    <nav id="sidebar" class="sidebar">
        <div class="logo">
        <span class="user-welcome"><?php echo 'Welcome ' .  '!'; ?></span>
            <a href="user page"><i class="fas fa-user"></i></a> 
        </div>
        <ul>
            <li><a href="../1-GeneralPages\1-Home.php"><i class="fas fa-home"></i>Home</a></li>
            <li class="active"><a href="StudentDBTicketHistory.php"><i class="fas fa-tools"></i>Ticket History</a></li>
            <li><a href="StudentDBAnalytics.php"><i class="fas fa-chart-line"></i>Performance Analytics</a></li>
            <li><a href="StudentDBNotifications.php"><i class="fas fa-bell"></i>Notifications</a></li>
            <li><a href="StudentDBHelp.php"><i class="fas fa-info-circle"></i>Help and Support</a></li>
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
                <strong>Student Dashboard</strong>
            </div>
            <div class="logo">
                <img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="rumaintained logo">
            </div>
        </header>
    <body>
    <div>
                    <a href="../7-TicketCreation\1-TicketCreation.php"><button class="ticketButton" type ="submit">Create New Ticket</button></a>
            </div> 
<div>
    <h3>My Tickets</h3>

    <?php


    

                 echo "<table class='requests-table'>
                        <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>Date Created</th>
                            <th>Status</th>
                            <th>Fault Description</th>
                            <th>Severity</th>
                            <th>View More</th>
                        </tr>
                        </thead>";
                        
                        while ($row = $result->fetch_assoc()) {
                    
                       
                        echo "<tr>";
                        echo "<td>{$row['TicketID']}</td>";
                        echo "<td>{$row['DateCreated']}</td>";
                        echo "<td>{$row['Status']}</td>";
                        echo "<td>{$row['Description']}</td>";
                        echo "<td>{$row['Severity']}</td>";
                        echo "<td><button>Details</a></td>";       
                        echo "</tr>";
                    
                        }
                echo "</table>";
                        ?>

            </div>
        
            <div>
    <h3>My Residence Tickets</h3>

    <?php


    $sqlRes = "SELECT * FROM ticket WHERE ResidenceID = ?";
    $stmt = $conn->prepare($sqlRes);
    $stmt->bind_param("s", $_SESSION['userID']);
    $stmt->execute();
    $resultRes = $stmt->get_result(); // Fetch results using get_result()
    $stmt->close(); 
    


    




                        echo "<table class='requests-table'>
                        <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>Date Created</th>
                            <th>Status</th>
                            <th>Fault Description</th>
                            <th>Severity</th>
                            <th>View More</th>
                        </tr>
                        </thead>";
                        
                        while ($row = $resultRes->fetch_assoc()) {
                    
                       
                        echo "<tr>";
                        echo "<td>{$row['TicketID']}</td>";
                        echo "<td>{$row['DateCreated']}</td>";
                        echo "<td>{$row['Status']}</td>";
                        echo "<td>{$row['Description']}</td>";
                        echo "<td>{$row['Severity']}</td>";
                        echo "<td><button>Details</a></td>";       
                        echo "</tr>";
                    
                        }
                echo "</table>";
                        ?>

            </div>
          
            



            </body>

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

<div id="piechart" style="width: 900px; height: 500px;"></div>
</body>
</html>