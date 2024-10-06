<?php
if (isset($_POST['submit'])) {
    unset($_POST['submit']);
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

    // Insert user into the user table
    $userID = strtoupper($_POST['userID']);
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $password = $_POST['password'];
    $email = strtoupper($_POST['email_address']);
    $resID = $_POST['residenceID'];
    $hallID = isset($_POST['hall']) ? $_POST['hall'] : '';
    $role = $_POST['role'];
    $specialisation = isset($_POST['specialisation']) ? $_POST['specialisation'] : '';

    if ($role == "HW") {
        $stmt = $conn->prepare("SELECT HouseWardenID FROM residence WHERE ResidenceID = ?");
        $stmt->bind_param("s", $resID);
        $stmt->execute();
        $stmt->bind_result($resAssigned);
        $stmt->fetch();


        if ($resAssigned !== null) {
            $stmt->close();
            echo "<script>
                alert('Registration failed: This residence already has a house warden assigned.');
                window.history.back(); // Redirect back to the form or previous page
                </script>";
            exit();
        }
    } else if ($role == "HS") {
        $stmt = $conn->prepare("SELECT HallSecretaryID FROM residence WHERE LEFT(ResidenceID, 2) = ?");
        $stmt->bind_param("s", $hallID);
        $stmt->execute();
        $stmt->store_result(); // Store the result to check the number of rows
        $stmt->bind_result($hallAssigned);
        $stmt->fetch();

        // Check if no rows are returned or HallSecretaryID is not null
        if ($stmt->num_rows == 0 || $hallAssigned !== null) {
            $stmt->close();
            echo "<script>
                alert('Registration failed: This hall does not exist or already has a hall secretary assigned.');
                window.history.back(); // Redirect back to the form or previous page
                </script>";
            exit();
        }
    }

    // Check if the user already exists and is inactive
    $stmt = $conn->prepare("SELECT status FROM user WHERE UserID = ?");
    if ($stmt === false) {
        echo "<script>
            alert('Prepare failed at line " . __LINE__ . ": " . htmlspecialchars($conn->error) . "');
            window.history.back(); // Redirect back to the form or previous page
        </script>";
        exit();
    }
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    $stmt->close();

    if ($status === "Inactive") {
        // Update the status to active
        $stmt = $conn->prepare("UPDATE user SET Status = 'Active', Firstname = ?, Lastname = ?, Password = ?, Email_Address = ?, Role = ? WHERE userID = ?");
        $stmt->bind_param("ssssss", $firstname, $lastname, $password, $email, $role, $userID);
        if (!$stmt->execute()) {
            die('Execute failed: ' . htmlspecialchars($stmt->error));
        }
        $stmt->close();

        // Update residence table if the user is a hall warden or secretary
        if ($role == "HW") {
            $stmt = $conn->prepare("UPDATE residence SET HouseWardenID = ? WHERE ResidenceID = ?");
            $stmt->bind_param("ss", $userID, $resID);
            if (!$stmt->execute()) {
                die('Execute failed: ' . htmlspecialchars($stmt->error));
            }
            $stmt->close();
        } else if ($role == "HS") {
            $stmt = $conn->prepare("UPDATE residence SET HallSecretaryID = ? WHERE LEFT(ResidenceID, 2) = ?");
            $stmt->bind_param("ss", $userID, $hallID);
            if (!$stmt->execute()) {
                die('Execute failed: ' . htmlspecialchars($stmt->error));
            }
            $stmt->close();
        }

        $registration_success = true;
        include '16-RegisterSuccess.php';

        echo "<script>
        alert('Registration successful! You will now be redirected to the login page.');
        window.location.href = '6-SignInPage.php'; // Redirect to login page
        </script>";
        
    } 
    else if ($status === "Active") {
        echo "<script>
        alert('User is already active. No changes were made.');
        window.history.back(); // Redirect back to the form or previous page
        </script>";
        return; // Stop further execution
    

    } else {
        // Prepare the SQL statement
        $stmt1 = $conn->prepare("INSERT INTO user (UserID, Firstname, Lastname, Password, Email_Address, Role) VALUES (?, ?, ?, ?, ?, ?)");

        // Check if the prepare statement failed
        if ($stmt1 === false) {
            die('Prepare failed at line ' . __LINE__ . ': ' . htmlspecialchars($conn->error));
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
                    die('Prepare failed at line ' . __LINE__ . ': ' . htmlspecialchars($conn->error));
                }
                $stmt2->bind_param("sss", $userID, $userID, $resID);
                if (!$stmt2->execute()) {
                    die('Execute failed: ' . htmlspecialchars($stmt2->error));
                }
            } else if ($role == "HW") {
                $stmt2 = $conn->prepare("INSERT INTO housewarden VALUES (?, ?)");
                $stmt3 = $conn->prepare("UPDATE residence SET HouseWardenID = ? WHERE ResidenceID = ?");

                if ($stmt2 === false) {
                    die('Prepare failed at line ' . __LINE__ . ': ' . htmlspecialchars($conn->error));
                }

                if ($stmt3 === false) {
                    die('Prepare failed at line ' . __LINE__ . ': ' . htmlspecialchars($conn->error));
                }

                $stmt2->bind_param("ss", $userID, $userID);
                $stmt3->bind_param("ss", $userID, $resID);

                if (!$stmt2->execute() || !$stmt3->execute()) {
                    die('Execute failed: ' . htmlspecialchars($stmt2->error));
                }

            } else if ($role == "HS") {
                $stmt2 = $conn->prepare("INSERT INTO hallsecretary VALUES (?, ?)");
                $stmt3 = $conn->prepare("UPDATE residence SET HallSecretaryID = ? WHERE LEFT(ResidenceID, 2) = ?");

                if ($stmt2 === false || $stmt3 === false) {
                    die('Prepare failed at line ' . __LINE__ . ': ' . htmlspecialchars($conn->error));
                }

                $stmt2->bind_param("ss", $userID, $userID);
                $stmt3->bind_param("ss", $userID, $hallID);
                if (!$stmt2->execute() || !$stmt3->execute()) {
                    die('Prepare failed at line ' . __LINE__ . ': ' . htmlspecialchars($conn->error));
                }

            } else if ($role == "MS") {
                $stmt2 = $conn->prepare("INSERT INTO maintenancestaff VALUES (?, ?, ?)");
                if ($stmt2 === false) {
                    die('Prepare failed at line ' . __LINE__ . ': ' . htmlspecialchars($conn->error));
                }
                $stmt2->bind_param("sss", $userID, $userID, $specialisation);
                if (!$stmt2->execute()) {
                    die('Execute failed: ' . htmlspecialchars($stmt2->error));
                }
            }

            // If all statements are successful
            $registration_success = true;
            include '16-RegisterSuccess.php';

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
        if (isset($stmt1)) {
            $stmt1->close();
        }
        if (isset($stmt2)) {
            $stmt2->close();
        }
        if (isset($stmt3)) {
            $stmt3->close();
        }
    }
    $conn->close();
}
?>
