<?php
    include '../8-PHPTests/connectionAzure.php';

     $sql = "SELECT Firstname, Lastname, Email_Address, Role, ProfilePhoto FROM user WHERE UserID = ?";
     $stmt = $conn->prepare($sql);
     $stmt->bind_param("s", $_SESSION['userID']);
     $stmt->execute();
     $stmt->bind_result($firstname, $lastname, $email, $role, $photoPath);
     $stmt->fetch();
     $stmt->close();

     if ($role == "HS") {
        $roleFull = "Hall Secretary";
         $sql = "SELECT HallName FROM residence WHERE HallSecretaryID = ?";
         $stmt = $conn->prepare($sql);
         $stmt->bind_param("s", $_SESSION['userID']);
         $stmt->execute();
         $stmt->bind_result($hallName);
         $stmt->fetch();
         $stmt->close();
     }
     else if($role == "HW") {
        $roleFull = "House Warden";
         $sql = "SELECT ResidenceID, ResName FROM residence WHERE HouseWardenID = ?";
         $stmt = $conn->prepare($sql);
         $stmt->bind_param("s", $_SESSION['userID']);
         $stmt->execute();
         $stmt->bind_result($resID, $resName);
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
     else if ($role == "MS" ){
        $roleFull = "Maintenance Staff";
        $sql = "SELECT Specialisation FROM maintenancestaff WHERE UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_SESSION['userID']);
        $stmt->execute();
        $stmt->bind_result($specialisation);
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

