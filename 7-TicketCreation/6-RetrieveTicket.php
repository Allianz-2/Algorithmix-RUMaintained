<?php 
    require_once('../8-PHPTests/config.php');
    session_start();
    $conn = mysqli_init();
    if (!file_exists($ca_cert_path)) {
        die("CA file not found: " . $ca_cert_path);
    }
    mysqli_ssl_set($conn, NULL, NULL, $ca_cert_path, NULL, NULL);
    mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL);
    if (mysqli_connect_errno()) {
        die('Failed to connect to MySQL: ' . mysqli_connect_error());
    }
    
    $stmt1 = $conn->prepare("SELECT * FROM ticket WHERE userID = ? AND password = ?");

    // Check if the prepare statement failed
    if ($stmt1 === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    
    // Bind parameters
    $stmt1->bind_param("ss", $userID, $password);
    if ($stmt1->execute()) {
        $stmt1->store_result();
        if ($stmt1->num_rows == 1) {
            // A single result was returned
            $_SESSION['userID'] = $userID; // Set session variable
            // Retrieve role from the user table using the userID
            $stmt2 = $conn->prepare("SELECT role, Firstname, Lastname FROM user WHERE userID = ?");

            // Check if the prepare statement failed
            if ($stmt2 === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            
            // Bind parameters
            $stmt2->bind_param("s", $userID);
            if ($stmt2->execute()) {
                // Bind the result variables
                $stmt2->bind_result($role, $firstname, $lastname);
                $stmt2->fetch();
                
                // Set session variables
                $_SESSION['role'] = trim($role);
                $_SESSION['Firstname'] = $firstname;
                $_SESSION['Lastname'] = $lastname;
            } else {
                // Execution failed
                die('Execute failed: ' . htmlspecialchars($stmt2->error));
            }
            $stmt2->close();

            // Check if there's a redirect URL
    
            echo "<script>
