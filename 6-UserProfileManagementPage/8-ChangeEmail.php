<?php

    if (isset($_POST['email'])) {
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

        $email = strtoupper($_POST['email']);
        // Check if the new email already exists
        $check_sql = "SELECT COUNT(*) FROM user WHERE Email_Address = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        if ($count > 0) {
            $_SESSION['alert']= 'Email already exists';
        } else {
            $sql = "UPDATE user SET Email_Address = ? WHERE UserID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $_POST['email'], $_SESSION['userID']);
            
            if ($stmt->execute()) {
                $_SESSION['alert'] = 'Email updated successfully';
            } else {
                $_SESSION['alert'] = 'Failed to update email';
            }

            $stmt->close();
        }
        // Unset the email in $_POST to prevent resubmission issues
        unset($_POST['email']);
        $conn->close();

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
?>