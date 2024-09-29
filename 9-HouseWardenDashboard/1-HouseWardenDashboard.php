<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RHouseWarden Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
</head>
<body>
<style> 
:root {
    --sidebar-width: 220px; /* Reduced sidebar width */
}

body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    display: flex; /* Use flexbox for layout */
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
    padding: 10px; /* Reduced padding */
    text-align: right;
}

.sidebar .logo a {
    color: white; /* Change user icon to white */
}

.sidebar ul {
    list-style-type: none;
    padding: 0;
}

.sidebar li {
    padding: 8px 12px; /* Smaller padding */
    cursor: pointer;
    transition: background-color 0.3s;
}

.sidebar li:hover, .sidebar li.active {
    background-color: rgba(255, 255, 255, 0.1);
}

.sidebar .badge {
    background-color: purple;
    color: white;
    padding: 2px 4px; /* Smaller badge */
    border-radius: 50%;
    font-size: 0.7em; /* Smaller font */
    margin-left: 5px;
}

.sidebar-footer {
    position: absolute;
    bottom: 0;
    width: 100%;
    padding: 10px; /* Reduced padding */
    font-size: 14px; /* Smaller footer font */
}

.sidebar li i, .sidebar-footer button i {
    margin-right: 5px; /* Reduced margin */
    width: 20px;
    text-align: center;
}

main {
    margin-left: var(--sidebar-width);
    padding: 10px; /* Reduced padding */
    flex-grow: 1; /* Allow main to grow */
}

h1 {
    text-align: center;
    color: #81589a;
    font-size: 1.3em; /* Smaller heading */
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px; /* Reduced margin */
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    overflow: hidden;
}

th, td {
    padding: 6px 8px; /* Smaller padding */
    text-align: left;
    border-bottom: 1px solid #ddd;
    font-size: 0.8em; /* Smaller text */
}

th {
    background-color: #81589a;
    color: #fff;
}
tr:nth-child(even) {
        background-color: #eaeaea; /* Light gray for even rows */
    }

    tr:hover {
        background-color: #d1c4e9; /* Light purple hover effect */
    }

    th {
        background-color: #ab8cc6; /* Light purple header */
        color: white; /* White text for contrast */
        font-weight: bold;
    }

    /* Add responsive design */
    @media (max-width: 768px) {
        th, td {
            font-size: 14px; /* Smaller text on mobile */
        }

        h1 {
            font-size: 24px; /* Smaller title on mobile */
        }
    }
.filters {
    display: flex;
    justify-content: space-around;
    margin-top: 10px; /* Reduced margin */
    flex-wrap: wrap; /* Allow items to wrap */
}

.stats {
    display: flex;
    justify-content: space-between; /* Align items in one line */
    margin-top: 10px; /* Reduced margin */
    flex-wrap: nowrap; /* Prevent wrapping */
}

.stat-box {
    background-color: #f1eaf5;
    color: #81589a; 
    border-radius: 8px; /* Smaller border radius */
    padding: 10px; /* Smaller padding */
    box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.15); /* Reduced shadow */
    text-align: center;
    flex: 1; /* Each box takes equal space */
    min-width: 120px; /* Smaller minimum width */
    margin: 5px; /* Reduced margin */
    transition: transform 0.2s;
}

.stat-box:hover {
    transform: translateY(-2px); /* Smaller hover effect */
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

.stat-box h4 {
    margin: 5px 0; /* Reduced margin */
    font-size: 1em; /* Smaller heading */
}

.stat-box p {
    font-size: 1.2em; /* Smaller text */
    font-weight: bold;
}

.logo img {
    max-height: 50px; /* Adjusted size */
    width: auto;
    object-fit: contain;
}

.user-welcome {
    align-items: center;
    text-align: left;
    margin-right: 10px; /* Reduced margin */
}

.ticketButton {
    padding: 6px 12px; /* Smaller button */
    font-size: 0.8rem; /* Smaller font */
    font-weight: bold;
    border: none;
    border-radius: 5px;
    background-color: #81589a;
    color: #fff;
    cursor: pointer;
}

button {
    padding: 4px 8px; /* Smaller button */
    font-size: 0.8rem; /* Smaller font */
    font-weight: bold;
    border: none;
    border-radius: 5px;
    background-color: #81589a;
    color: #ffffff;
    cursor: pointer;
}
.charts {
    display: flex; /* Use flexbox to align charts */
    justify-content: space-between; /* Space between charts */
    margin-top: 2px; /* Add some space above */
}

.charts canvas {
    max-width: 2024px; /* Set max width for the charts */
    width: 100%; /* Ensure it takes full width of its container */
    height: 5px; /* Maintain aspect ratio */
}

</style>


</head>
<?php
        // Database connection
        require_once('config.php');
        $conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');
        
        // Fetch all maintenance requests
        $query = "SELECT t.TicketID, t.Description, t.Status, t.Severity, t.DateCreated, u.First_name, u.Lastname, m.StaffName 
                  FROM ticket t
                  JOIN user u ON t.StudentID = u.UserID
                  JOIN maintenance_staff m ON t.StaffID = m.StaffID";
        $results = mysqli_query($conn, $query);
        
        // Fetch task statistics
        $totalRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as TotalRequests FROM ticket"))['TotalRequests'];
        $pendingRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as PendingRequests FROM ticket WHERE Status = 'Open'"))['PendingRequests'];
        $viewedRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as ViewedRequests FROM ticket WHERE Status = 'Viewed'"))['ViewedRequests']; 
        $completedRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as CompletedRequests FROM ticket WHERE Status = 'Resolved'"))['CompletedRequests'];
        ?>
        
    <nav id="sidebar" class="sidebar">
        <div class="logo">
        <span class="user-welcome">Welcome!</span>
            <a href="user-page"><i class="fas fa-user"></i></a>
        </div>
        <ul>
            <li><a href="2-Ticketanalytics.php"><i class="fas fa-chart-pie"></i>Analytics</a></li>
            <li><a href="3-TicketProgress.php"><i class="fas fa-tasks"></i>Ticket Progress</a></li>
            <li><a href="#"><i class="fas fa-bell"></i>Notifications</a></li>
            <li><a href="../7-TicketCreation/1-TicketCreation.php"><i class="fas fa-plus-circle"></i>Lodge Ticket</a></li>
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
                <strong>House Warden Dashboard</strong>
            </div>
            <div class="logo">
                <img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="rumaintained logo">
            </div>
        </header>

        <div class="content">
            <h3>Ticket Requests</h3>

            <div class="charts">
                <canvas id="maintenanceRequestChart"></canvas>
                <canvas id="residenceTaskChart"></canvas>
            </div>

<div class="stats">
    <div class="stat-box">
        <i class="fas fa-tasks icon"></i>
        <h4>Total Requests</h4>
        <p id="total-requests"><?php echo $totalRequests; ?></p>
    </div>
    <div class="stat-box">
        <i class="fas fa-clock icon"></i>
        <h4>Pending Requests</h4>
        <p id="pending-requests"><?php echo $pendingRequests; ?></p> 
    </div>
    <div class="stat-box">
        <i class="fas fa-eye icon"></i>
        <h4>Viewed Requests</h4>
        <p id="viewed-requests"><?php echo $viewedRequests; ?></p>
    </div>
    <div class="stat-box">
        <i class="fas fa-check-circle icon"></i>
        <h4>Completed Requests</h4>
        <p id="completed-requests"><?php echo $completedRequests; ?></p>
    </div>
</div>

            <h1>Ticket Requests</h1>

            <table>
                <tr>
                    <th>Ticket ID</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Severity</th>
                    <th>Date Created</th>
                    <th>Submitted By</th>
                </tr>
                <!-- Sample PHP Code to Fetch and Display Tickets -->
                <?php 
                        require_once('config.php');
                        $query = "SELECT t.TicketID, t.Description, t.Status, t.Severity, t.DateCreated, u.First_name, u.Lastname 
                        FROM ticket t
                        JOIN user u ON t.StudentID = u.UserID
                        WHERE t.Status != 'open'
                        ORDER BY t.DateCreated DESC
                        LIMIT 10"; // Adjust the limit as needed
              
              $conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');
              $results = mysqli_query($conn, $query);
              
              while ($row = mysqli_fetch_assoc($results)): ?>
                  <tr>
                      <td><?php echo $row['TicketID']; ?></td>
                      <td><?php echo $row['Description']; ?></td>
                      <td><?php echo $row['Status']; ?></td>
                      <td><?php echo $row['Severity']; ?></td>
                      <td><?php echo $row['DateCreated']; ?></td>
                      <td><?php echo $row['First_name'] . " " . $row['Lastname']; ?></td>
                  </tr>
              <?php endwhile; ?>
              
            </table>
        </div>
    </main>

    <script>
        const ctx = document.getElementById('maintenanceRequestChart').getContext('2d');
        const maintenanceRequestChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Requests', 'Pending Requests', 'Viewed Requests', 'Completed Requests'],
                datasets: [{
                    label: 'Ticket Requests',
                    data: [<?php echo $totalRequests; ?>, <?php echo $pendingRequests; ?>, <?php echo $viewedRequests; ?>, <?php echo $completedRequests; ?>],
                    backgroundColor: [
                        'rgba(130, 118, 159, 0.5)',
                        'rgba(130, 118, 159, 0.7)',
                        'rgba(130, 118, 159, 0.9)',
                        'rgba(130, 118, 159, 1)'
                    ],
                    borderColor: [
                        'rgba(130, 118, 159, 1)',
                        'rgba(130, 118, 159, 1)',
                        'rgba(130, 118, 159, 1)',
                        'rgba(130, 118, 159, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

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
