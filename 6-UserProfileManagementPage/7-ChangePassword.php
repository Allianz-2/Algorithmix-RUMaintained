<?php
    if (isset($_POST['submit-password'])) {
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

        $userID = $_SESSION['userID'];
        $oldPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        if ($newPassword != $confirmPassword) {
            $_SESSION['alert'] = 'Passwords do not match. Please try again.';
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        $sql = "SELECT * FROM user WHERE userID = ?";
        $stmt = mysqli_stmt_init($conn);
        

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $_SESSION['alert'] = 'SQL error occurred. Please try again.';
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } 
        
        else {
            mysqli_stmt_bind_param($stmt, "s", $userID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                if ($oldPassword == $row['Password']) {
                    $sql = "UPDATE user SET password = ? WHERE userID = ?";
                    $stmt = mysqli_stmt_init($conn);

                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        $_SESSION['alert'] = 'SQL error occurred. Please try again.';
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                        
                    } else {
                        mysqli_stmt_bind_param($stmt, "ss", $newPassword, $userID);
                        mysqli_stmt_execute($stmt);
                        $_SESSION['alert'] = 'Password changed successfully.';
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    }
                
                } else {
                    $_SESSION['alert'] = 'Incorrect current password. Please try again.';
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                }
                
            } else {
                $_SESSION['alert'] = 'User not found';
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        }
        unset($_POST['submit-password']);
        $conn->close();

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
?>
