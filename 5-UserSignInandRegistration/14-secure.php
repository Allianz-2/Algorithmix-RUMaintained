<?php
    session_start();

    if (!isset($_SESSION['userID'])) {
        $current_url = urlencode($_SERVER['REQUEST_URI']);
        header("Location: ../5-UserSignInandRegistration/6-SignInPage.php?redirect=$current_url");
        exit();
    }
?>