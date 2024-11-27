<?php
    if (isset($_POST['submit'])) {
        session_start();
        include '../8-PHPTests/connectionAzure.php';


        $userID = $_POST['userID'];
        $password = $_POST['password'];
        $current_url = isset($_POST['current_url']) ? urldecode($_POST['current_url']) : '../1-GeneralPages/6-RedirectDashboard.php';

        $stmt1 = $conn->prepare("SELECT * FROM user WHERE userID = ? AND password = ? AND Status = 'Active'");

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
                $stmt2 = $conn->prepare("SELECT role, Firstname, Lastname, ProfilePhoto FROM user WHERE userID = ? ");

                // Check if the prepare statement failed
                if ($stmt2 === false) {
                    die('Prepare failed: ' . htmlspecialchars($conn->error));
                }
                
                // Bind parameters
                $stmt2->bind_param("s", $userID);
                if ($stmt2->execute()) {
                    // Bind the result variables
                    $stmt2->bind_result($role, $firstname, $lastname, $photoPath);
                    $stmt2->fetch();
                    
                    // Set session variables
                    $_SESSION['role'] = trim($role);
                    $_SESSION['Firstname'] = $firstname;
                    $_SESSION['Lastname'] = $lastname;
                    $_SESSION['ProfilePath'] = $photoPath;
                } else {
                    // Execution failed
                    die('Execute failed: ' . htmlspecialchars($stmt2->error));
                }
                $stmt2->close();

                // Check if there's a redirect URL
        
                echo "<script>
                    alert('Login successful!');
                    window.location.href = '{$current_url}'; // Redirect to the original page or home page
                </script>";

            } else {
                // No result or more than one result was returned
                echo "<script>
                    alert('Incorrect password or username. Please try again.');
                    window.location.href = '6-SignInPage.php'; // Redirect to login page
                    </script>";
            }
        } else {
            // Execution failed
            die('Execute failed: ' . htmlspecialchars($stmt1->error));
        }
        $stmt1->close();
        $conn->close();
    }
?>
