<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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