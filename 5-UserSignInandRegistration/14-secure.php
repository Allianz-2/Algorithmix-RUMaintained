<?php
    session_start();
    if (!isset($_SESSION["access"])) {
        header("Location: 6-SignInPage.php");
        exit();
    }
?>
