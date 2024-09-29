<?php

    if (isset($_POST['delete-account'])) {
        require '../8-PHPTests/config.php';

        $conn = mysqli_init(); 
        if (!file_exists($ca_cert_path)) {
            die("CA file not found: " . $ca_cert_path);
        }
        mysqli_ssl_set($conn, NULL, NULL, $ca_cert_path, NULL, NULL);
        mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL);

        if (mysqli_connect_errno()) {
            die('Failed to connect to MySQL: ' . mysqli_connect_error());
        }

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
                $_SESSION['alert'] = 'Your account has been deactivated.';
            } else {
                $_SESSION['alert'] = 'Failed to deactivate account.';
                unset($_POST['delete-account']);
                header("Location: 14-UserSecurity.php");
                exit();
            }

            $stmt->close();
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