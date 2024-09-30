<?php
    session_start();
    if (isset($_SESSION['userID'])) {
        if ($_SESSION['role'] == 'S') {
            header('Location: ../2-StudentDashboard/1-StudentRequests.php');
        } else if ($_SESSION['role'] == 'HW') {
            header('Location: ../3-HWDashboard/1-HWRequests.php');
        } else if ($_SESSION['role'] == 'HS') {
            header('Location: ../4-HSDashboard/1-HSRequests.php');
        } else if ($_SESSION['role'] == 'MS') {
            header('Location: ../10-MSDashboard/1-MSRequests.php');
        }
    } else {
        header('Location: 6-SignInPage.php');
    }
?>