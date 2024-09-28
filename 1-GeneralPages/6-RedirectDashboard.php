<?php
    if (isset($_SESSION['userID'])) {
        if ($_SESSION['role'] == 'S') {
            header('Location: 4-StudentDashboard.php');
        } else if ($_SESSION['role'] == 'HW') {
            header('Location: 5-HouseWardenDashboard.php');
        } else if ($_SESSION['role'] == 'HS') {
            header('Location: 6-HallSecretaryDashboard.php');
        } else if ($_SESSION['role'] == 'MS') {
            header('Location: 7-MaintenanceDashboard.php');
        }
    } else {
        header('Location: 6-SignInPage.php');
    }
?>