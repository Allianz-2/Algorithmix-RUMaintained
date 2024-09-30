<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); 
    include '../11-DashboardFunctionality/8-NotificationUpdate.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall Secretary Dashboard</title>
    <link rel="icon" href="../Images\General\favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="6-Notifications.css">

</head>
<body>
    <nav id="sidebar" class="sidebar">
        <div class="logo">
        <span class="user-welcome">Welcome, <?php echo $_SESSION['Firstname']; ?></span> <!--  Session not added in yet -->
        <a href="..\6-UserProfileManagementPage\3-ProfileHS.php"><i class="fas fa-user"></i></a>
        </div>
        <ul>
        <li>
            <a href="..\1-GeneralPages\1-Home.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="..\4-HSDashboard\2-TicketApproval.php"><i class="fas fa-check-circle"></i> Ticket Approvals</a></li>
            <li><a href="#"><i class="fas fa-tasks"></i> Requests</a></li>
            <li><a href="..\4-HSDashboard\3-HSAnalytics.php"><i class="fas fa-chart-bar"></i> Analytics</a></li>
            <li class="active"><a href="HSDSNotifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
            <li><a href="..\4-HSDashboard\5-HSHelp.php"><i class="fas fa-info-circle"></i> Help and Support</a></li>
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
            <div class="logo">
                <img src="../Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="rumaintained logo">
            </div>
        </header>

        <div class="content">
            <h2>Notifications</h2>
            <P> <strong></strong> </P> 

            <div class="container">
                <div class="notification-controls">
                    <!-- <div class="filter-sort">
                        <select id="filterType">
                            <option value="">All Notifications</option>
                            <option value="ticket">Ticket Updates</option>
                            <option value="system">System Alerts</option>
                        </select>
                        <select id="sortOrder">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                        </select>
                    </div> -->
                    <form method="POST" action="4-StudentNotifications.php">
                        <button type="submit" name="mark_all_as_read" class="btn btn-primary">Mark All as Read</button>
                    </form>
                </div>
        
                <div class="notification-list">
                    <?php include '../11-DashboardFunctionality/7-NotificationsFetch.php'; ?>
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