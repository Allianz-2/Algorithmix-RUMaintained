<?php
    if (isset($_POST['specialisation'])) {
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

        $sql = "UPDATE maintenancestaff SET Specialisation = ? WHERE UserID = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("ss", $_POST['specialisation'], $_SESSION['userID']);
        if (!$stmt->execute()) {
            die('Execute failed: ' . htmlspecialchars($stmt->error));
        }
        $stmt->close();
        $conn->close();

        unset($_POST['residence']);

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
?>