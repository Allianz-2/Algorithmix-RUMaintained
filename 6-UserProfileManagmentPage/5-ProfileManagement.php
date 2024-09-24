<?php
    require '../8-PHPTests/config.php';

     // Initializes MySQLi
     $conn = mysqli_init();

     // Test if the CA certificate file can be read
     if (!file_exists($ca_cert_path)) {
         die("CA file not found: " . $ca_cert_path);
     }

     mysqli_ssl_set($conn, NULL, NULL, $ca_cert_path, NULL, NULL);

     // Establish the connection
     mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL);

     // If connection failed, show the error
     if (mysqli_connect_errno()) {
         die('Failed to connect to MySQL: ' . mysqli_connect_error());
     }

     $sql = "SELECT Firstname, Lastname, Email_Address, Role FROM user WHERE UserID = ?";
     $stmt = $conn->prepare($sql);
     $stmt->bind_param("s", $_SESSION['userID']);
     $stmt->execute();
     $stmt->bind_result($firstname, $lastname, $email, $role);
     $stmt->fetch();
     $stmt->close();

     if ($role == "HS") {
        $roleFull = "Hall Secretary";
         $sql = "SELECT HallID FROM hall_secretary WHERE UserID = ?";
         $stmt = $conn->prepare($sql);
         $stmt->bind_param("s", $_SESSION['userID']);
         $stmt->execute();
         $stmt->bind_result($hallID);
         $stmt->fetch();
         $stmt->close();
     }
     else if($role == "HW") {
        $roleFull = "House Warden";
         $sql = "SELECT ResidenceID FROM house_warden WHERE UserID = ?";
         $stmt = $conn->prepare($sql);
         $stmt->bind_param("s", $_SESSION['userID']);
         $stmt->execute();
         $stmt->bind_result($resID);
         $stmt->fetch();
         $stmt->close();

         $sql = "SELECT ResidenceName FROM residence WHERE ResidenceID = ?";
         $stmt = $conn->prepare($sql);
         $stmt->bind_param("s", $resID);
         $stmt->execute();
         $stmt->bind_result($resName);
         $stmt->fetch();
         $stmt->close();
     }
     else if($role == "S") {
            $roleFull = "Student";
            $sql = "SELECT ResidenceID FROM student WHERE UserID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $_SESSION['userID']);
            $stmt->execute();
            $stmt->bind_result($resID);
            $stmt->fetch();
            $stmt->close();
    
            $sql = "SELECT ResName FROM residence WHERE ResidenceID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $resID);
            $stmt->execute();
            $stmt->bind_result($resName);
            $stmt->fetch();
            $stmt->close();

     }

     if ($role =="S" || $role == "HW" || $role == "HS") {
        $sql = "SELECT HallName FROM residence WHERE ResidenceID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $resID);
        $stmt->execute();
        $stmt->bind_result($hallName);
        $stmt->fetch();
        $stmt->close();
     }

     $conn->close();
?>

