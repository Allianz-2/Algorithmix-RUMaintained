<?php
    if (isset($_POST['save-button'])) {

        include '../8-PHPTests/config.php';
        $conn = mysqli_init();
        if (!file_exists($ca_cert_path)) {
            die("CA file not found: " . $ca_cert_path);
        }
        mysqli_ssl_set($conn, NULL, NULL, $ca_cert_path, NULL, NULL);
        mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL);
        if (mysqli_connect_errno()) {
            die('Failed to connect to MySQL: ' . mysqli_connect_error());
        }
        $sql = "UPDATE user SET Email_Address = ? WHERE UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $_POST['email'], $_SESSION['userID']);
        if ($stmt->execute()) {
            echo "<script>alert('Email changed successfully');</script>";
            $email = $_POST['email'];
        } else {
            echo "<script>alert('Failed to change email');</script>";
        }
        $stmt->close();
        $conn->close();
    }
?>