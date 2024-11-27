<?php
    // Check if the form has been submitted by looking for the 'submit' button press
    if (isset($_POST['submit'])) {
        include '../8-PHPTests/connectionAzure.php';


        $ticketID = 'T' . strval(time());
        $status = $_POST['status'];
        $description =  $_POST['description'];
        $severity = $_POST['severity'];
        $date_created = strval(date("Y-m-d H:i:s"));
        // $hallWardenID = "SHWS6666";
        // $categoryID = "CE049";
        $studentID = $_SESSION["userID"];
        $categoryID = $_POST['category'];
        $comment = $_POST['comments'] . "\n" . $_SESSION['Firstname'] . ' ' . $_SESSION['Lastname'] . ' - ' . $_SESSION['userID'] . "\n\n";

        $SQL_res = $conn->prepare("
            SELECT r.ResidenceID, r.HouseWardenID 
            FROM residence r
            JOIN student s ON r.ResidenceID = s.ResidenceID
            WHERE s.UserID = ?
        ");
        $SQL_res->bind_param("s", $studentID);

        if ($SQL_res->execute()) {
            $SQL_res->bind_result($resID, $hallWardenID);
            $SQL_res->fetch();
            $SQL_res->close();
        } else {
            die('Execute failed: ' . htmlspecialchars($SQL_res->error));
        }

        $SQL = $conn->prepare("INSERT INTO ticket (TicketID, Status, Description, Severity, DateCreated, ResidenceID, StudentID, HouseWardenID, CategoryID)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if ($SQL === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        
        $SQL->bind_param("sssssssss", $ticketID, $status, $description, $severity,$date_created, $resID, $studentID, $hallWardenID, $categoryID);
        if ($SQL->execute()) {
                if (isset($comment)) {
                    $ticketCommentID = 'TC' . strval(time());
                    $datePosted = strval(date("Y-m-d H:i:s"));

                    $SQL_comment = $conn->prepare("
                        INSERT INTO ticketcomment (TicketCommentID, CommentText, DatePosted, TicketID, UserID)
                        VALUES (?, ?, ?, ?, ?)
                    ");

                    if ($SQL_comment === false) {
                        die('Prepare failed: ' . htmlspecialchars($conn->error));
                    }

                    $SQL_comment->bind_param("sssss", $ticketCommentID, $comment, $datePosted, $ticketID, $studentID);

                    if (!$SQL_comment->execute()) {
                        echo "<script>
                            alert('Comment insertion failed!');
                            </script>";
                        } 
                    $SQL_comment->close();
                }
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                    $photo = $_FILES['photo'];
                    
                    // Correct the name reference
                    $photoName = "P" . strval(time()) . "." . pathinfo($photo['name'], PATHINFO_EXTENSION);
                    $photoPath = "../Images/TicketPictures/" . $photoName;
                    $dateUploaded = date("Y-m-d H:i:s");
                
                    // Ensure the target directory exists
                    if (!is_dir("../Images/TicketPictures/")) {
                        if (!mkdir("../Images/TicketPictures/", 0777, true)) {
                            die('Failed to create directories...');
                        }
                    }
                    // Move the uploaded file to the desired directory
                    if (move_uploaded_file($photo['tmp_name'], $photoPath)) {
                        $photoID = 'P' . uniqid();
                
                        // Prepare the SQL statement to insert the photo information into the photo table
                        $SQL_photo = $conn->prepare("
                            INSERT INTO photo (PhotoID, PhotoPath, DateUploaded, TicketID)
                            VALUES (?, ?, ?, ?)
                        ");
                
                        if ($SQL_photo === false) {
                            die('Prepare failed: ' . htmlspecialchars($conn->error));
                        }
                
                        // Bind the parameters
                        $SQL_photo->bind_param("ssss", $photoName, $photoPath, $dateUploaded, $ticketID);
                
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
                }


            // echo "<script>
            //       alert('Ticket insertion successful!');
            //         window.location.href = '10-SendEmailUpdate.php?ticketID=" . urlencode($ticketID) . "'; 
            //     </script>";


                if ($_SERVER['SERVER_NAME'] == 'localhost') {
                    $_SESSION['alert'] = "Ticket created successfully";
                    header("Location: 10-SendEmailUpdate.php?ticketID=" . urlencode($ticketID));
    
                } else {
                    $_SESSION['alert'] = "Ticket created successfully";
                    header('Location: 7-TicketStatus.php?ticketID=' . urlencode($ticketID));
                }

        } else {
            echo "<script>
            alert('Ticket creation failed!');
            </script>";

            die('Execute failed: ' . htmlspecialchars($SQL->error));
        }

        unset($_POST['submit']);

        $SQL->close();
        $conn->close();

    }
?>

