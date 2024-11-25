<?php

if (isset($_POST['delete-account'])) {
    include '../8-PHPTests/connectionAzure.php';


    $email = trim($_POST['email']);
    $confirmDelete = $_POST['confirm-delete'];

    if ($confirmDelete === 'DELETE') {
        // Check if the email matches the email assigned to the userID
        $stmt = $conn->prepare("SELECT Email_Address FROM user WHERE UserID = ?");
        $stmt->bind_param("s", $_SESSION['userID']);
        $stmt->execute();
        $stmt->bind_result($db_email);
        $stmt->fetch();
        $stmt->close();

        if ($db_email !== $email) {
            $_SESSION['alert'] = 'Email does not match the email assigned to the userID.';
            unset($_POST['delete-account']);

            header("Location: 14-UserSecurity.php");
            exit();
        }

        // Set the user status to 'Inactive'
        $stmt = $conn->prepare("UPDATE user SET status = 'Inactive' WHERE UserID = ?");
        $stmt->bind_param("s", $_SESSION['userID']);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Check if the user is a house warden or secretary
            $role = $_SESSION['role'];

            if ($role === 'HW' || $role === 'HS') {
            // Set HallSecretaryID and HallWardenID to NULL in the residence table
            if ($role === 'HW') {
                $stmt = $conn->prepare("UPDATE residence SET HouseWardenID = NULL WHERE HouseWardenID = ?");
            } else {
                $stmt = $conn->prepare("UPDATE residence SET HallSecretaryID = NULL WHERE HallSecretaryID = ?");
            }
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("s", $_SESSION['userID']);
            $stmt->execute();
            $stmt->close();
            }

            $_SESSION['alert'] = 'Your account has been deactivated.';
        } else {
            $_SESSION['alert'] = 'Failed to deactivate account for userID: ' . $_SESSION['userID'];
            unset($_POST['delete-account']);
            header("Location: 14-UserSecurity.php");
            exit();
        
        }

        $conn->close();

        header("Location: ../5-UserSignInandRegistration/15-Logout.php");
        exit();
    } else {
        $_SESSION['alert'] = 'Confirmation text does not match.';
        unset($_POST['delete-account']);
        header("Location: 14-UserSecurity.php");
        exit();
    }
}
?>