<?php
    session_start();
    if (isset($_SESSION['alert'])) {
        echo "<script>alert('" . $_SESSION['alert'] . "');</script>";
        unset($_SESSION['alert']); // Clear the alert message from the session
    }

    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page or home page
    header("Location: ../1-GeneralPages/1-Home.php");
    exit();
?>