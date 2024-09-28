<?php

if (isset($_POST['hall-save-button'])) {
    require '../8-PHPTests/config.php';

    $conn = mysqli_init(); 
    if (!file_exists($ca_cert_path)) {
        die("CA file not found: " . $ca_cert_path);
    }
    mysqli_ssl_set($conn, NULL, NULL, $ca_cert_path, NULL, NULL);
    mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL);

    if (mysqli_connect_errno()) {
        die('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    if ($_SESSION['role'] === 'HS') {
        // Check if the target residence already has a hall secretary assigned
        $stmt = $conn->prepare("
            SELECT HallSecretaryID 
            FROM residence 
            WHERE ResidenceID LIKE CONCAT(?, '%');
        ");
        $stmt->bind_param("s", $_POST['hallChange']);
        $stmt->execute();
        $stmt->bind_result($hallSecretaryID);
        $stmt->fetch();
        $stmt->close();

        if (!empty($hallSecretaryID)) {
            $_SESSION['alert'] = 'Another hall secretary is already assigned to this Hall';
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        // Assign the hall secretary to the residence
        $stmt = $conn->prepare("UPDATE residence SET HallSecretaryID = ? WHERE ResidenceID LIKE CONCAT(?, '%')");
        $stmt->bind_param("ss", $_SESSION['userID'], $_POST['residenceID']);

        if ($stmt->execute()) {
            $_SESSION['alert'] = 'Hall updated successfully';
        } else {
            $_SESSION['alert'] = 'Failed to update Hall';
        }

    } 
    
    // Unset the residence in $_POST to prevent resubmission issues
    unset($_POST['changeHall']);
    $conn->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>