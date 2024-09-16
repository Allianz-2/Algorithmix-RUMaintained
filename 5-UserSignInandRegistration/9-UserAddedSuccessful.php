<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            background-color: purple;
        }

        .notification {
            width: 300px;
            height: 50px;
            background-color: white;
            color: black;
            text-align: center;
            margin: 20px auto;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="notification">
        <!-- Your PHP code to display the notification message goes here -->
        <?php
            // Include the configuration file
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

            // Retrieve the resID from the residence table
            $residence = $_POST['residence'];
            $resQuery = "SELECT resID FROM residence WHERE ResName = ?";
            $stmt = $conn->prepare($resQuery);
            $stmt->bind_param("s", $residence);
            $stmt->execute();
            $stmt->bind_result($resID);
            $stmt->fetch();
            $stmt->close();

            if (!$resID) {
                die('Residence not found.');
            }

            // Retrieve all users from the database
            $sql = "INSERT INTO user VALUES ('{$_POST['userID']}', '{$_POST['firstname']}', '{$_POST['lastname']}', '{$_POST['password']}', '{$_POST['email']}', 'S')
            INSERT INTO student VALUES ('{$_POST['userID']}', '{$_POST['userID']}', $resID)";
            $result = $conn->query($sql);

// Check if the query was successful
if ($result === false) {
    die('Error executing query: ' . $conn->error);
}
else {
    echo "User added successfully!";
}




        ?>
    </div>
</body>
</html>