<?php
    if (isset($_POST['submit'])) {
        session_start();
        require_once('../8-PHPTests/config.php');

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

        $userID = $_POST['userID'];
        $password = $_POST['password'];

        $stmt1 = $conn->prepare("SELECT * FROM user WHERE userID = ? AND password = ?");

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
                echo "Login successful!";
                $_SESSION["access"] = true;
            } else {
                // No result or more than one result was returned
                echo "<script>
                    alert('Incorrect password or username. Please try again.');
                    window.location.href = '6-SignInPage.php'; // Redirect to login page
                    </script>";
            }

            echo "<script>
                alert('Login successful!');
                window.location.href = '../1-GeneralPages/1-Home.html'; // Redirect to login page
                </script>";
        } else {
            // Execution failed
            die('Execute failed: ' . htmlspecialchars($stmt1->error));
        }

        $stmt1->close();
        $conn->close();
    }
?>


