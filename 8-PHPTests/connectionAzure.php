<?php
        require_once('../8-PHPTests/config.php');
        
        // Initializes MySQLi
        $conn = mysqli_init();
        
        // Test if the CA certificate file can be read
        if (!file_exists($ca_cert_path)) {
            die("CA file not found: " . $ca_cert_path);
        }
        
        // mysqli_ssl_set($conn, NULL, NULL, $ca_cert_path, NULL, NULL);

        // Establish the connection
        // mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL);
        mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, NULL);

        // If connection failed, show the error
        if (mysqli_connect_errno()) {
            die('Failed to connect to MySQL: ' . mysqli_connect_error());
        }
?>