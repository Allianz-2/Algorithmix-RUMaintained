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
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
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



.notification-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .filter-sort {
            display: flex;
            gap: 10px;
        }
        select, input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .notification-list {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .notification-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: flex-start;
        }
        .notification-item:last-child {
            border-bottom: none;
        }
        .notification-item.unread {
            background-color: #e1e0e0;
        }
        .notification-icon {
            font-size: 1.5em;
            margin-right: 15px;
            color: #81589a;
        }
        .notification-content {
            flex-grow: 1;
        }
        .notification-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .notification-message {
            margin-bottom: 5px;
        }
        .notification-time {
            font-size: 0.8em;
            color: #888;
        }
        .notification-actions {
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background-color 0.3s;
        }
        .btn-primary {
            background-color: #81589a;
            color: white;
        }
        .btn-secondary {
            background-color: #f0f0f0;
            color: #333;
        }
        .btn:hover {
            opacity: 0.8;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .page-link {
            padding: 5px 10px;
            margin: 0 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
            text-decoration: none;
            color: #333;
        }
        .page-link.active {
            background-color: #81589a;
            color: white;
        }
    </style>
</head>
<body>
    <nav id="sidebar" class="sidebar">
        <div class="logo">
            <span class="user-welcome">Welcome, User </span><!-- Add PHP code here for user name -->
            <a href="user page"><i class="fas fa-user"></i></a> 
        </div>
        <ul>
            <li><a href="StudentDBTicketHistory.php"><i class="fas fa-tools"></i>My Ticket History</a></li>
            <li><a href="StudentDBAnalytics.php"><i class="fas fa-chart-line"></i>Performance Analytics</a></li>
            <li class="active"><a href="StudentDBNotifications.php"><i class="fas fa-bell"></i>Notifications</a></li>
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

        <div class="content">
            <h2>Notifications</h2>
            <P> <strong></strong> </P> 

            <div class="container">
                <div class="notification-controls">
                    <div class="filter-sort">
                        <select id="filterType">
                            <option value="">All Notifications</option>
                            <option value="ticket">Ticket Updates</option>
                            <option value="system">System Alerts</option>
                        </select>
                        <select id="sortOrder">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                        </select>
                    </div>
                    <button class="btn btn-secondary" onclick="markAllAsRead()">Mark All as Read</button>
                </div>
        
                <div class="notification-list">
                    <!-- PHP would be implemented here to fetch notifications dynamically from the database -->
                    <div class="notification-item unread">
                        <div class="notification-icon"><i class="fas fa-ticket-alt"></i></div>
                        <div class="notification-content">
                            <div class="notification-title">Ticket Update</div>
                            <div class="notification-message">Ticket #76549 has been updated with a new comment.</div>
                            <div class="notification-time">45 minutes ago</div>
                        </div>
                        <div class="notification-actions">
                            <button class="btn btn-primary" onclick="viewTicket(76549)">View Ticket</button>
                            <button class="btn btn-secondary" onclick="markAsRead(this)">Mark as Read</button>
                        </div>
                    </div>
                    <div class="notification-item unread">
                        <div class="notification-icon"><i class="fas fa-exclamation-triangle"></i></div>
                        <div class="notification-content">
                            <div class="notification-title">System Alert</div>
                            <div class="notification-message">Scheduled maintenance will occur tonight at 11 PM.</div>
                            <div class="notification-time">1 hour ago</div>
                        </div>
                        <div class="notification-actions">
                            <button class="btn btn-primary" onclick="viewAlert()">View Details</button>
                            <button class="btn btn-secondary" onclick="markAsRead(this)">Mark as Read</button>
                        </div>
                    </div>
                        <div class="notification-item unread">
                            <div class="notification-icon"><i class="fas fa-ticket-alt"></i></div>
                            <div class="notification-content">
                                <div class="notification-title">Ticket Update</div>
                                <div class="notification-message">Ticket #23654 has been resolved.</div>
                                <div class="notification-time">2 days ago</div>
                            </div>
                            <div class="notification-actions">
                                <button class="btn btn-primary" onclick="viewTicket(23654)">View Ticket</button>
                                <button class="btn btn-secondary" onclick="markAsRead(this)">Mark as Read</button>
                            </div>
                    
            
                    </div>
                    
                </div>
        
                <div class="pagination">
                    <a href="#" class="page-link active">1</a>
                    <a href="#" class="page-link">2</a>
                    <a href="#" class="page-link">Next</a>
                </div>
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

                function markAllAsRead() {
                    var items = document.getElementsByClassName('notification-item');
                    for (var i = 0; i < items.length; i++) {
                        items[i].classList.remove('unread');
                    }
                }
        
                function markAsRead(button) {
                    button.closest('.notification-item').classList.remove('unread');
                }
        
                function viewTicket(ticketId) {
                    alert('Viewing Ticket #' + ticketId);
                    // In a real app, this would navigate to the ticket details page
                }
        
                function viewAlert() {
                    alert('Viewing System Alert Details');
                    // In a real app, this would show more information about the alert
                }
        
        
                document.getElementById('filterType').addEventListener('change', function() {
                    console.log('Filtering notifications by:', this.value);
                    // Implement filtering logic here
                });
        
                document.getElementById('sortOrder').addEventListener('change', function() {
                    console.log('Sorting notifications by:', this.value);
                    // Implement sorting logic here
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
        };

    </script>
</body>
</html>