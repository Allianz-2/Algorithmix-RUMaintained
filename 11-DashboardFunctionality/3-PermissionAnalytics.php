<?php

    // Get the current script name
    $current_page = basename($_SERVER['PHP_SELF']);

    // Role-based access control using if statements
    if ($current_page === '3-StudentAnalytics.php') {
        if ($_SESSION['role'] !== 'S' && $_SESSION['role'] !== 'Super') {
            // Redirect to the error page if the user's role is not appropriate
                    header("Location: ../6-UserProfileManagementPage/10-ErrorPage.html");
            exit();
        }
    } else if ($current_page === '3-HWAnalytics.php') {
        if ($_SESSION['role'] !== 'HW' && $_SESSION['role'] !== 'Super') {
            // Redirect to the error page if the user's role is not appropriate
                    header("Location: ../6-UserProfileManagementPage/10-ErrorPage.html");
            exit();
        }
    } else if ($current_page === '3-HSAnalytics.php') {
        if ($_SESSION['role'] !== 'HS' && $_SESSION['role'] !== 'Super') {
            // Redirect to the error page if the user's role is not appropriate
                    header("Location: ../6-UserProfileManagementPage/10-ErrorPage.html");
            exit();
        }
    } else if ($current_page === '3-MSAnalytics.php') {
        if ($_SESSION['role'] !== 'MS' && $_SESSION['role'] !== 'Super') {
            // Redirect to the error page if the user's role is not appropriate
                    header("Location: ../6-UserProfileManagementPage/10-ErrorPage.html");
            exit();
        }
    } else {
        // If the page is not defined in the if statements, redirect to the error page
        header("Location: ../6-UserProfileManagementPage/10-ErrorPage.html");
        exit();
    }
?>