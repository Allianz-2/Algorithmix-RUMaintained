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
            <span class="user-welcome">Welcome, <?php echo $fullName; ?></span>
            <a href="user_page"><i class="fas fa-user"></i></a> 
        </div>
        <ul>
            <li><a href="HS_DS_Ticket_Approval.php"><i class="fas fa-tasks"></i>Ticket Approvals</a></li>
            <li><a href="HS_DS_Analytics.php"><i class="fas fa-chart-bar"></i>Analytics</a></li>
            <li class="active"><a href="#" ><i class="fas fa-clipboard-list"></i>Requests</a></li>
            <li><a href="#"><i class="fas fa-home"></i>Residences</a></li>
            <li><a href="HS_DS_Notifications.php"><i class="fas fa-bell"></i>Notifications</a></li>
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
                <strong>Hall Secretary Dashboard</strong>
            </div>
            <div class="logo"><img src="logo.png" alt="RU Maintained Logo"></div>
        </header>
        
        <div class="content">
            <h2>Requests</h2>
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
                        <option>Chris Hani House</option>
                        <!-- Add more options dynamically if necessary -->
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
                        <option value="Repairs">Repairs</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="status-filter">Status</label>
                    <select id="status-filter">
                        <option>Any</option>
                        <option value="Active">Active</option>
                        <option value="Pending">Pending</option>
                        <option value="Closed">Closed</option>
                    </select>
                </div>
            </div>

            <div class="charts">
                <canvas id="requestChart"></canvas>
                <canvas id="residenceChart"></canvas>
            </div>
            
            <div class="stats">
                <div class="stat-box">
                    <h4>Total Requests</h4>
                    <p id="total-requests"><?php echo $totalRequests; ?></p>
                </div>
                <div class="stat-box">
                    <h4>Pending Requests</h4>
                    <p id="pending-requests"><?php echo $pendingRequests; ?></p>
                </div>
                <div class="stat-box">
                    <h4>Viewed Requests</h4>
                    <p id="viewed-requests"><?php echo $viewedRequests; ?></p>
                </div>
                <div class="stat-box">
                    <h4>Completed Requests</h4>
                    <p id="completed-requests"><?php echo $completedRequests; ?></p>
                </div>
                <div class="stat-box">
                    <h4>Online Users</h4>
                    <p id="online-users"><?php echo $onlineUsers; ?></p>
                </div>
            </div>

            <div class="recent-requests">
                <h3>Most Recent Requests</h3>
                <table id="requests-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>Category</th>
                            <th>Photo</th>
                            <th>Residence</th>
                            <th>Status</th>
                            <th>Submission Date</th>
                            <th>Severity</th>
                            <th>Work Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                    
                        // Database connection
                       require_once("config.php");
                        
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        
                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        
                        // Fetch recent requests
                        $sql = "SELECT * FROM tickets ORDER BY DateCreated";
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><input type='checkbox'></td>";
                                echo "<td>" . $row['category'] . "</td>";
                                echo "<td><img src='" . $row['photo_url'] . "' alt='Photo' width='50'></td>";
                                echo "<td>" . $row['residence'] . "</td>";
                                echo "<td><span class='status " . strtolower($row['status']) . "'>" . ucfirst($row['status']) . "</span></td>";
                                echo "<td>" . $row['submission_date'] . "</td>";
                                echo "<td>" . ucfirst($row['severity']) . "</td>";
                                echo "<td>" . $row['work_order'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No recent requests found</td></tr>";
                        }
                        
                        $conn->close();
                        ?>
                        
                    
                    </tbody>
                </table>
            </div>
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

        // You can add JavaScript logic here to populate the charts dynamically
    </script>
</body>
</html>
