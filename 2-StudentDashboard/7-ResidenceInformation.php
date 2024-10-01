<?php
    include '../8-PHPTests/connectionAzure.php';

    if (isset($_SESSION['userID'])) {
        $userID = $_SESSION['userID'];

        $query = "SELECT r.ResName 
                  FROM residence r 
                  JOIN student s ON r.ResidenceID = s.ResidenceID 
                  WHERE s.StudentID = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $userID);
        $stmt->bind_result($residenceName);
        $stmt->execute();
        $stmt->fetch();
        
        if (empty($residenceName)) {
            $residenceName = "No residence information found.";
        }

        $stmt->close();
        $conn->close();

    }

?>