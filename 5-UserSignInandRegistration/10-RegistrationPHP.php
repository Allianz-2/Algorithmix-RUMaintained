<?php
    if (isset($_POST['submit'])) {
        include '../8-PHPTests/config.php';

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

        // Insert user into the user table
        $userID = $_POST['userID'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $password = $_POST['password'];
        $email = $_POST['email_address'];
        $resID = $_POST['residenceID'];
        $role = $_POST['role'];
        $specialisation = $_POST['specialisation'];
        
        if ($role == "HW") {
            $stmt = $conn->prepare("SELECT HouseWardenID FROM residence WHERE ResidenceID = ?");
            $stmt->bind_param("s", $resID);
            $stmt->execute();
            $stmt->bind_result($resAssigned);
            $stmt->fetch();
            $stmt->close();

            if ($resAssigned !== null) {
            echo "<script>
                alert('Registration failed: This residence already has a house warden assigned.');
                window.history.back(); // Redirect back to the form or previous page
                </script>";
            exit();
            } 
        }

        if ($role == "HS") {
            $stmt = $conn->prepare("SELECT HouseWardenID FROM residence WHERE ResidenceID = ?");
            $stmt->bind_param("s", $resID);
            $stmt->execute();
            $stmt->bind_result($resAssigned);
            $stmt->fetch();
            $stmt->close();

            if ($resAssigned !== null) {
            echo "<script>
                alert('Registration failed: This residence already has a house warden assigned.');
                window.history.back(); // Redirect back to the form or previous page
                </script>";
            exit();
            } 
        }

        }

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
            $registration_success = true;
        
            // Prepare the second SQL statement based on the role
            if ($role == "S") {
                $stmt2 = $conn->prepare("INSERT INTO student VALUES (?, ?, ?)");
                if ($stmt2 === false) {
                    die('Prepare failed: ' . htmlspecialchars($conn->error));
                }
                $stmt2->bind_param("sss", $userID, $userID, $resID);
                if (!$stmt2->execute()) {
                    die('Execute failed: ' . htmlspecialchars($stmt2->error));
                }
            } else if ($role == "HW") {
                $stmt2 = $conn->prepare("INSERT INTO housewarden VALUES (?, ?)");
                $stmt3 = $conn->prepare("UPDATE residence SET HouseWardenID = ? WHERE ResidenceID = ?");

                if ($stmt2 === false) {
                    die('Prepare failed: ' . htmlspecialchars($conn->error));
                }

                if ($stmt2 === false) {
                    die('Prepare failed: ' . htmlspecialchars($conn->error));
                }

                $stmt2->bind_param("ss", $userID, $userID);
                $stmt3->bind_param("ss", $userID, $resID);

                if (!$stmt2->execute() || !$stmt3->execute()) {
                    die('Execute failed: ' . htmlspecialchars($stmt2->error));
                }

            } else if ($role == "HS") {
                $stmt2 = $conn->prepare("INSERT INTO hallsecretary VALUES (?, ?)");
                if ($stmt2 === false) {
                    die('Prepare failed: ' . htmlspecialchars($conn->error));
                }
                $stmt2->bind_param("ss", $userID, $userID);
                if (!$stmt2->execute()) {
                    die('Execute failed: ' . htmlspecialchars($stmt2->error));
                }
            } else if ($role == "MS") {
                $stmt2 = $conn->prepare("INSERT INTO maintenancestaff VALUES (?, ?, ?)");
                if ($stmt2 === false) {
                    die('Prepare failed: ' . htmlspecialchars($conn->error));
                }
                $stmt2->bind_param("sss", $userID, $userID, $specialisation);
                if (!$stmt2->execute()) {
                    die('Execute failed: ' . htmlspecialchars($stmt2->error));
                }
            }
        
            // If all statements are successful
            echo "<script>
                alert('Registration successful! You will now be redirected to the login page.');
                window.location.href = '6-SignInPage.php'; // Redirect to login page
                </script>";
        
        } else {
            // If there is an error, set the error message
            $error_message = htmlspecialchars($stmt1->error);
            echo "<script>
                alert('Registration failed: $error_message');
                window.history.back(); // Redirect back to the form or previous page
                </script>";
        }
        $stmt1->close();
        $stmt2->close();
        $conn->close();
    }
    ?>



