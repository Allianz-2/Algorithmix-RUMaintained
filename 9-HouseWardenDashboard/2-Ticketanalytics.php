<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
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
       
        // Corrected SQL query
        $sql = "SELECT CategoryID, COUNT(TicketID) as TicketCount FROM ticket GROUP BY CategoryID";
        $result = $conn->query($sql);

        echo "var data = google.visualization.arrayToDataTable([";
        echo   "['Category', 'Count'],";

        while ($row = $result->fetch_assoc()) {
            echo    "['{$row['CategoryID']}', {$row['TicketCount']}],"; // Fixed the echo statement
        }

        echo "]);";
        ?>

        var options = {
          title: 'Ticket Categories',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
    </script>
</head>
<body>
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
            align-self: center;
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            align-items: center;
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

<nav id="sidebar" class="sidebar">
    <div class="logo">
        <span class="user-welcome">Welcome, User</span>
        <a href="user page"><i class="fas fa-user"></i></a> 
    </div>
    <ul>
        <li><a href="StudentDBTicketHistory.php"><i class="fas fa-tools"></i>My Ticket History</a></li>
        <li class="active"><a href="StudentDBAnalytics.php"><i class="fas fa-chart-line"></i>Performance Analytics</a></li>
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
            <strong>Housewarden Dashboard</strong>
        </div>
        <div class="logo">
            <img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="rumaintained logo">
        </div>
    </header>

    <div class="content">
        <h2>Analytics</h2>
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
                <label for="severity-filter">Severity</label>
                <select id="severity-filter">
                    <option value="High">High</option>
                    <option value="Medium">Medium</option>
                    <option value="Low">Low</option>
                    <option value="Emergency">Emergency</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="category-filter">Category</label>
                <select id="category-filter">
                    <option>Any</option>
                    <option value="Plumbing">Plumbing</option>
                    <option value="Electrical">Electrical</option>
                    <option value="Roofing">Roofing</option>
                    <option value="Repairs and Breakage">Repairs and Breakage</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="status-filter">Status</label>
                <select id="status-filter">
                    <option>Any</option>
                    <option value="active">Active</option>
                    <option value="Pending">Pending</option>
                    <option value="Closed">Closed</option>
                </select>
            </div>
        </div>
    
        <div id="piechart_3d" style="width: 900px; height: 500px;"></div>
    </div>

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
