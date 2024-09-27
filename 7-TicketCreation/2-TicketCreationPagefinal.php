<?php
    // Check if the form has been submitted by looking for the 'submit' button press
    if (isset($_POST['submit'])) {
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

        $ticketID = 'T' . strval(time());
        $status = $_POST['status'];
        $description =  $_POST['description'];
        $severity = $_POST['severity'];
        $date_created = strval(date("Y-m-d H:i:s"));
        $hallWardenID = "SHWS6666";
        $categoryID = "CE049";
        $studentID = "G21G6825";


        $SQL_res = $conn->prepare("SELECT ResidenceID from residence WHERE HouseWardenID = ?");
        $SQL_res->bind_param("s", $hallWardenID);


        if ($SQL_res->execute()) {
            $SQL_res->bind_result($resID);
            $SQL_res->fetch();
            $SQL_res->close();
        }



        $SQL = $conn->prepare("INSERT INTO ticket (TicketID, Status, Description, Severity, DateCreated, ResidenceID, StudentID, HouseWardenID, CategoryID)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if ($SQL === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        
        $SQL->bind_param("sssssssss", $ticketID, $status, $description, $severity,$date_created, $resID, $studentID, $hallWardenID, $categoryID);
        if ($SQL->execute()) {
            echo "<script>
                alert('Ticket inserted successfully');
                </script>";
        } else {
             echo "<script>
             alert('Ticket creation failed!');
             </script>";

            die('Execute failed: ' . htmlspecialchars($SQL->error));
        }

        unset($_POST['submit']);

        $SQL->close();
        $conn->close();

/////////////////////////////////////////////////////////////////////////////////////////////












        // // Function to generate the next ticket ID by fetching the last ticket from the 'ticket' table
        //     // Query to select the last inserted ticket ID
        //     $query = "SELECT ticketid FROM ticket ORDER BY ticketid DESC LIMIT 1";
        //     $result = $conn->query($query); 
            
        //     // If the query fails, display an error message and stop the script
        //     if ($result === false) {
        //         die("<p class=\"error\">ERROR: Query failed to execute: " . $conn->error . "</p>");
        //     }
    
        //     // If there are rows returned, extract the ticket ID and generate the next one
        //     if ($result->num_rows > 0) {
        //         $row = $result->fetch_assoc(); // Fetch the last ticket ID
        //         $lastTicketId = $row['ticketid']; // Get the ticket ID (e.g., 't000001')
        //         $numericPart = intval(substr($lastTicketId, 1)); // Remove the 't' and convert the rest to an integer
        //         $newTicketNumber = $numericPart + 1; // Increment the ticket number by 1
        //         return 't' . str_pad($newTicketNumber, 6, '0', STR_PAD_LEFT); // Return the new ticket ID in the format 't000002'
        //     } else {
        //         // If there are no existing tickets, return the initial ticket ID 't000001'
        //         return 't000001';
        //     }
        // }
    

        // echo "<p>Form submitted!</p>";

        // // Check if required fields 'description' and 'severity' are empty, if so, show an error and stop the script
        // if (empty($_REQUEST['description']) || empty($_REQUEST['severity'])) {
        //     die("<p class=\"error\">ERROR: Description and Severity are required fields!</p>");
        // }
        
        // // Retrieve the form data: 'description', 'category', 'severity', and others from the form submission
        // $description = $_REQUEST['description'];
        // $category = $_REQUEST['category'];
        // $severity = $_REQUEST['severity'];
        // // $affectedItems = $_REQUEST['affected-items'];
        // // $comments = $_REQUEST['comments'];
        
        // echo "<p>Form Data - Description: $description, Severity: $severity, Category: $category, Affected Items: $affectedItems, Comments: $comments</p>";

        // // Check if a file has been uploaded. If not, display an error message and stop the script
        // if (!isset($_FILES['photo'])) {
        //     die("<p class=\"error\">ERROR: No file was uploaded!</p>");
        // }

        // // Create a unique name for the uploaded file by appending the current timestamp to the original file name
        // $picture = time() . "_" . $_FILES['photo']['name'];
        // $destination = "images/" . $picture; // Define the destination where the file will be stored

        // // Move the uploaded file to the 'images/' directory. If it fails, show an error and stop the script
        // if (!move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
        //     die("<p class=\"error\">ERROR: File upload failed. Please check file permissions or try again.</p>");
        // }
        // echo "<p>File uploaded successfully: $picture</p>";

        // // Include the database configuration file to get database credentials 
        // require_once('config.php');

        // // Create a new connection to the database using the credentials from the config file
        // $conn = new mysqli(SERVERNAME, Username, Password, Database);

        // // If the database connection fails, show an error and stop the script
        // if ($conn->connect_error) {
        //     die("<p class=\"error\">ERROR: Unable to connect to the database! " . $conn->connect_error . "</p>");
        // }
        // echo "<p>Database connection successful!</p>";

        // // Generate the next available ticket ID using the getNextTicketId function
        // $newTicketId = getNextTicketId($conn);
        // echo "<p>New Ticket ID generated: $newTicketId</p>";

        // // Create the SQL query to insert the new ticket into the 'ticket' table with the generated ticket ID, description, severity, and uploaded file name
        // $SQL = "INSERT INTO ticket (ticketid, Description, Severity, Photo, Category, AffectedItems, Comments)
        //         VALUES ('$newTicketId', '$description', '$severity', '$picture', '$category', '$affectedItems', '$comments')";

        // // Execute the query. If it is successful, show a success message; otherwise, display an error message
        // if ($conn->query($SQL) === true) {
        //     echo "<strong class=\"success\">The information was correctly captured! Ticket ID: $newTicketId</strong>";
        // } else {
        //     // If there was an error executing the SQL query, display the error and stop the script
        //     die("<p class=\"error\">ERROR: Unable to execute the query. " . $conn->error . "</p>");
        // }

        // // Close the database connection. If it fails, show an error (though PHP will usually close the connection automatically at the end of the script)
        // if (!$conn->close()) {
        //     die("<p class=\"error\">ERROR: Unable to close the database connection!</p>");
        // }

        // echo "done@"; // Final confirmation message (optional)
    }
    ?>

