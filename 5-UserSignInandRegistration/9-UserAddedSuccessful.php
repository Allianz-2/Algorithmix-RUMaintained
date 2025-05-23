<?php
    if (isset($_POST['submit'])) {
        include '../8-PHPTests/connectionAzure.php';

        // Insert user into the user table
        $userID = $_POST['userID'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $resID = $_POST['residenceID'];
        $role = $_POST['role'];

        // Prepare the SQL statement
        $stmt1 = $conn->prepare("INSERT INTO user VALUES (?, ?, ?, ?, ?, ?)");

        // Check if the prepare statement failed
        if ($stmt1 === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        // Bind parameters

        $stmt1->bind_param("ssssss", $userID, $firstname, $lastname, $password, $email, $role);

        if ($stmt1->execute()) {
            // If user insertion is successful
            echo "<script>
                alert('Registration successful! You will now be redirected to the login page.');
                window.location.href = '6-SignInPage.html'; // Redirect to login page
            </script>";
        } else {
            // If there is an error, display the error message in an alert
            $error_message = htmlspecialchars($stmt1->error);
            echo "<script>
                alert('Registration failed: $error_message');
                window.history.back(); // Redirect back to the form or previous page
            </script>";
        }
        // Prepare the second SQL statement
        $stmt2 = $conn->prepare("INSERT INTO student VALUES (?, ?, ?)");

        // Check if the prepare statement failed
        if ($stmt2 === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        // Bind parameters for the second statement
        $stmt2->bind_param("sss", $userID, $userID, $resID);

        // Execute the second statement
        if (!$stmt2->execute()) {
            die('Execute failed: ' . htmlspecialchars($stmt2->error));
        }

        // Close the statements and connection
        $stmt1->close();
        $stmt2->close();
        $conn->close();
    }
?>