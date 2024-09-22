<?php
    session_start();
    if (!isset($_SESSION["access"])) {
        header("Location: ../5-UserSignInandRegistration/6-SignInPage.php");
        exit();
    }
?>
