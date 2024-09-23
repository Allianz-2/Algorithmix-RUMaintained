<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RU Maintained Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="Dashboard.css">
        
</head>
<body>
    <nav id="sidebar" class="sidebar">
        <div class="logo">
            <span class="user-welcome">Welcome, </span><!-- Add PHP code here for user name -->
            <a href="user page"><i class="fas fa-user"></i></a> 
        </div>
        <ul>
            <li class="active"><a href="#"><i class="fas fa-tools"></i>Maintenance Requests</a></li>
            <li><a href="TaskAssignment.html"><i class="fas fa-clipboard-list"></i>Task Assignment</a></li>
            <li><a href="inventorymanagement.html"><i class="fas fa-wrench"></i>Inventory Management</a></li>
            <li><a href="performance analytics.html"><i class="fas fa-chart-line"></i>Performance Analytics</a></li>
            <li><a href="MaintenanceNotifications.html"><i class="fas fa-bell"></i>Notifications</a></li>
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
        
        <?php 
require_once('config.php');

// Fetch all maintenance requests
$query = "SELECT t.TicketID, t.Description, t.Status, t.Severity, t.DateCreated, u.First_name, u.Lastname 
          FROM ticket t
          JOIN user u ON t.StudentID = u.UserID";

$conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');
$results = mysqli_query($conn, $query);
?>
        <head>
    <title>Maintenance Requests</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="content">
<h3>Maintenance Requests</h3>
<div class="filters">
                <div class="filter-group">
                    <label for="date-filter">Date Range</label>
                    <select id="date-filter">
                        <option>Last 7 Days</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="today">Today</option>
                        <option value="2 weeks">Last 2 weeks</option>
                        <option value="Month">Last Month</option>
                        <option value="3 months">Last 3 Months</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="residence-filter">Residence</label> 
                    <select id="residence-filter">
                        <option>All Residences</option>
                        <option>Chris Hani House</option>
                        <!-- Add more residences here -->
                    </select>
                </div>
                <div class="filter-group">
                    <label for="severity-filter">Severity</label>
                    <select id="severity-filter">
                        <option value="High">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                        <option value="emergency">Emergency</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="category-filter">Category</label>
                    <select id="category-filter">
                        <option>Any</option>
                        <option value="Plumbing">Plumbing</option>
                        <option value="Electrical">Electrical</option>
                        <option value="Roofing">Roofing</option>
                        <option value="Repairs">Repairs and Breakages</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="status-filter">Status</label>
                    <select id="status-filter">
                        <option>Any</option>
                        <option value="active">Open</option>
                        <option value="Pending">Pending</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
            </div>

            <div class="charts">
                <!-- Placeholder for charts -->
                <canvas id="maintenanceRequestChart"></canvas>
                <canvas id="residenceTaskChart"></canvas>
            </div>

            <div class="stats">
                <div class="stat-box">
                    <h4>Total Tasks</h4>
                    <p id="total-tasks"> <!-- PHP for total tasks --></p>
                </div>
                <div class="stat-box">
                    <h4>Pending Tasks</h4>
                    <p id="pending-tasks"> <!-- PHP for pending tasks --></p>
                </div>
                <div class="stat-box">
                    <h4>Completed Tasks</h4>
                    <p id="completed-tasks"> <!-- PHP for completed tasks --></p>
                </div>
                <div class="stat-box">
                    <h4>Emergency Tasks</h4>
                    <p id="emergency-tasks"> <!-- PHP for emergency tasks --></p>
                </div>
            </div>

    <h1>Maintenance Requests</h1>

    <table>
        <tr>
            <th>Ticket ID</th>
            <th>Description</th>
            <th>Status</th>
            <th>Severity</th>
            <th>Date Created</th>
            <th>Student</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($results)): ?>
        <tr>
            <td><?php echo $row['TicketID']; ?></td>
            <td><?php echo $row['Description']; ?></td>
            <td><?php echo $row['Status']; ?></td>
            <td><?php echo $row['Severity']; ?></td>
            <td><?php echo $row['DateCreated']; ?></td>
            <td><?php echo $row['First_name'] . ' ' . $row['Lastname']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php mysqli_close($conn); ?>

        
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
    <style> body { font-family: Arial, sans-serif; background-color: #f9f9f9; /* Light background for contrast */ margin: 0; padding: 20px; } h1 { text-align: center; color: #5c4b8a; /* Darker shade of light purple */ } table { width: 100%; border-collapse: collapse; margin-top: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 5px; /* Rounded corners */ overflow: hidden; /* Clip child elements */ } th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; } tr:nth-child(even) { background-color: #eaeaea; /* Light gray for even rows */ } tr:hover { background-color: #d1c4e9; /* Light purple hover effect */ } th { background-color: #ab8cc6; /* Light purple header */ color: white; /* White text for contrast */ font-weight: bold; } /* Add responsive design */ @media (max-width: 768px) { th, td { font-size: 14px; /* Smaller text on mobile */ } h1 { font-size: 24px; /* Smaller title on mobile */ } } </style>

</body>
</html>