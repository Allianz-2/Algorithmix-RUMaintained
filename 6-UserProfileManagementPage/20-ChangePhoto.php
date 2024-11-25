<?php

    if (isset($_FILES['profilePhoto'])) {
        include '../8-PHPTests/connectionAzure.php';

        $photo = $_FILES['profilePhoto'];
            
        // Correct the name reference
        $photoName = "U" . strval(time()) . "." . pathinfo($photo['name'], PATHINFO_EXTENSION);
        $photoPath = "../Images/ProfilePhotos/" . $photoName;

        if (!is_dir("../Images/ProfilePhotos/")) {
            if (!mkdir("../Images/ProfilePhotos/", 0777, true)) {
                die('Failed to create directories...');
            }
        }

        if (move_uploaded_file($photo['tmp_name'], $photoPath)) {
            $photoID = 'P' . uniqid();
    
            // Prepare the SQL statement to insert the photo information into the photo table
            $SQL_photo = $conn->prepare("
                UPDATE user 
                SET ProfilePhoto = ? 
                WHERE UserID = ?;
            ");
    
            if ($SQL_photo === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
    
            // Bind the parameters
            $SQL_photo->bind_param("ss", $photoPath, $_SESSION['userID']);
    
            // Execute the statement and check for success
            if (!$SQL_photo->execute()) {
                echo "<script>
                alert('Photo insertion failed: " . htmlspecialchars($SQL_photo->error) . "');
                </script>";
            } 
            $SQL_photo->close();
        } else {
            echo "<script>
                alert('Failed to move uploaded file!');
                </script>";
        }
        unset($_POST['profilePhoto']);
        $conn->close();
    } 

?>