<?php
    session_start();
    if (isset($_SESSION['userID'])) {
        if ($_SESSION['role'] == 'S') {
            header('Location: ../6-UserProfileManagementPage/1-ProfileStudent.php');
        } else if ($_SESSION['role'] == 'HW') {
            header('Location: ../6-UserProfileManagementPage/2-ProfileHW.php');
        } else if ($_SESSION['role'] == 'HS') {
            header('Location: ../6-UserProfileManagementPage/3-ProfileHS.php');
        } else if ($_SESSION['role'] == 'MS') {
            header('Location: ../6-UserProfileManagementPage/4-ProfileMS.php');
        }
    } else {
        header('Location: 6-SignInPage.php');
    }
?>

