<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Warden Registration - RUMaintained</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f6f8fa;
            background-image: url('../Images/General/purple\ gradient\ background.jpeg');
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            display: flex;
            width: 1000px;
            height: 700px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .left-panel {
            flex: 1;
            background-color: #0d1117;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
            overflow: hidden;
        }
        .wallpaper {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        .wallpaper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        .right-panel {
            flex: 1;
            padding: 20px 80px;
            overflow-y: auto;
        }
        h1 {
            font-size: 24px;
            font-weight: 100;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            
        }
        input[type="text"], input[type="password"], input[type="email"], input[type="tel"], select {
            width: 100%;
            padding: 8px;
            border: 1px solid #d0d7de;
            border-radius: 4px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #81589a;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #aaa;
        }
        .error-message {
            color: #d73a49;
            margin-top: 10px;
        }

        .form-group label {
    display: inline;
    margin-left: 5px;
    font-size: 12px;
    color: #333;
}
.form-group input[type="checkbox"] {
    vertical-align: middle;
}
.form-group a {
    color: #81589a;
    text-decoration: underline;
}
.form-group a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="wallpaper">
               <img src="../Images/General/rhodes image.jpeg" alt="wallpaper">
            </div>
        </div>
        <div class="right-panel">
            <h1>House Warden Registration</h1>
            <form action="3-HouseWardenRegister.php" action="post" id="registration-form">
               
                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" required>
                    <label for="lastname">Lastname</label>
                    <input type="text" id="lastname" name="lastname" required>
                </div>
                <div class="form-group">
                    <label for="userID">Staff Number</label>
                    <input type="text" id="userID" name="userID" pattern="^SHW[A-Z]{1}[0-9]{4}$" placeholder="SHWX0000"required>
                </div>
                <div class="form-group">
                    <label for="email_address">Email Address</label>
                    <input type="email" id="email_address" name="email_address" placeholder="Please use your university email" required>
                </div>
            
            
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" pattern=".{8,}" title="Password must be at least 8 characters long" required>                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>

                <div class="form-group">
                    <label for="address">Select Residence:</label>
                    <select name="residence" id="residence">
                      <option value="Select Residence"></option>
                      <optgroup label="Allen Webb Hall">
                        <option value="AWCN">Canterbury House</option>
                        <option value="AWCA">Canterbury Annex House</option>
                        <option value="AWSL">Salisbury House</option>
                        <option value="AWTR">Truro House</option>
                        <option value="AWWC">Winchester House</option>
                      </optgroup>
                      <optgroup label="Courtenay-Latimer Hall">
                        <option value="CHBE">Beit House</option>
                        <option value="CHJM">Jameson House</option>
                        <option value="CHOR">Oriel House</option>
                      </optgroup>
                      <optgroup label="Desmond Tutu Hall">
                        <option value="DTEK">Ellen Kuzwayo House</option>
                        <option value="DTAC">Amina Cachalia House</option>
                        <option value="HMCT">Calata House</option>
                        <option value="DTMS">Margaret Smith House</option>
                        <option value="HMH7">Hilltop 7 House</option>
                        <option value="HMH8">Hilltop 8 House</option>
                      </optgroup>
                      <optgroup label="Drostdy Hall">
                        <option value="DHAG">Allan Gray House</option>
                        <option value="DHCE">Celeste House</option>
                        <option value="DHGH">Graham House</option>
                        <option value="DHPA">Prince Alfred House</option>
                      </optgroup>
                      <optgroup label="Founders Hall">
                        <option value="FHBT">Botha House</option>
                        <option value="FHCL">College House</option>
                        <option value="FHCR">Cory House</option>
                        <option value="FHM">Matthews House</option>
                      </optgroup>
                      <optgroup label="Hobson Hall">
                        <option value="HHDH">Dingemans House</option>
                        <option value="HHHH">Hobson House</option>
                        <option value="HHLH">Livingstone House</option>
                        <option value="HHMH">Milner House</option>
                      </optgroup>
                      <optgroup label="Jan Smuts Hall">
                        <option value="JSAD">Adamson House</option>
                        <option value="JSAS">Atherstone House</option>
                        <option value="JSRS">Jan Smuts House</option>
                        <option value="JSNW">New House</option>
                      </optgroup>
                      <optgroup label="Miriam Makeba Hall">
                        <option value="MMCH">Chris Hani House</option>
                        <option value="MMPR">Piet Retief House</option>
                        <option value="MMTP">Thomas Pringle House</option>
                        <option value="MMWH">Walker House</option>
                      </optgroup>
                      <optgroup label="Kimberley Hall">
                        <option value="KHCB">Cullen Bowles House</option>
                        <option value="KHDB">De Beers House</option>
                        <option value="KHRP">Rosa Parks House</option>
                        <option value="KHGF">Goldfields House</option>
                      </optgroup>
                      <optgroup label="Lilian Ngoyi Hall">
                        <option value="LNCY">Centenary House</option>
                        <option value="LNRF">Ruth First House</option>
                        <option value="LNJS">Joe Slovo House</option>
                        <option value="LNVM">Victoria Mxenge House</option>
                      </optgroup>
                      <optgroup label="Nelson Mandela Hall">
                        <option value="NMSK">Stanley Kidd House</option>
                        <option value="NMAT">Adelaide Tambo House</option>
                        <option value="NMGB">Guy Butler House</option>
                        <option value="NMHJ">Helen Joseph House</option>
                      </optgroup>
                      <optgroup label="St Mary Hall">
                        <option value="SMJK">John Kotze House</option>
                        <option value="SMLB">Lilian Britten House</option>
                        <option value="SMOS">Olive Schreiner House</option>
                        <option value="SMPH">Phelps House</option>
                      </optgroup>
                    </select>
                  </div>

                <input type="hidden" name="role" value="HouseWarden">

                <div class="form-group">
                    <input type="checkbox" id="termsAndConditions" name="termsAndConditions" required>
                    <label for="termsAndConditions">
                        I accept the <a href="8-TermsAndPolicy.html" id="termsLink">Terms and Conditions</a>
                    </label>
                </div>

                <button type="submit" name="submit" class="btn">Register</button>
            </form>

            
            <div id="error-message" class="error-message"></div>
        </div>
    </div>
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
            $error_message = json_encode($stmt1->error);
            echo "<script>
                alert('Registration failed: ' + $error_message);
                window.history.back(); // Redirect back to the form or previous page
            </script>";
        }
        // Prepare the second SQL statement
        $stmt2 = $conn->prepare("INSERT INTO housewarden VALUES (?, ?)");
        
        // Check if the prepare statement failed
        if ($stmt2 === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        
        // Bind parameters for the second statement
        $stmt2->bind_param("sss", $userID, $userID);
        
        // Execute the second statement
        if (!$stmt2->execute()) {
            die("<p>Student Registration Failed: " . htmlspecialchars($stmt2->error) . "</p>");
        }
        
        // Close the statements and connection
        $stmt1->close();
        $stmt2->close();
        $conn->close();
    }
    ?>
</body>
</html>