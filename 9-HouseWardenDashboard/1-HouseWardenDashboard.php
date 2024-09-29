<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RU House Warden Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="dashboard.css">
    
</head>
<?php
        require_once('config.php');

        // Fetch all maintenance requests
        $query = "SELECT t.TicketID, t.Description, t.Status, t.Severity, t.DateCreated, u.First_name, u.Lastname, m.StaffName 
                  FROM ticket t
                  JOIN user u ON t.StudentID = u.UserID
                  JOIN maintenance_staff m ON t.StaffID = m.StaffID";  // Joining with maintenance_staff table
        $conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');
        $results = mysqli_query($conn, $query);
        
        // Fetch task statistics
        $totalRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as TotalRequests FROM ticket"))['TotalRequests'];
        $pendingRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as PendingRequests FROM ticket WHERE Status = 'Open'"))['PendingRequests'];
        $viewedRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as ViewedRequests FROM ticket WHERE Status = 'Viewed'"))['ViewedRequests']; 
        $completedRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as CompletedRequests FROM ticket WHERE Status = 'Resolved'"))['CompletedRequests'];
?>
<body>
    <nav id="sidebar" class="sidebar">
        <div class="logo">
            <span class="user-welcome">Welcome, </span><!-- Add PHP code for user name -->
            <a href="user-page"><i class="fas fa-user"></i></a>
        </div>
        <ul>
            <li><a href="2-HouseWardenAnalytics .php"><i class="fas fa-chart-pie"></i>Analytics</a></li>
            <li><a href="3-TicketProgress.php"><i class="fas fa-tasks"></i>Ticket Progress</a></li>
            <li><a href="notifications.html"><i class="fas fa-bell"></i>Notifications</a></li>
            <li><a href="lodge-ticket.html"><i class="fas fa-plus-circle"></i>Lodge Ticket</a></li>
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
