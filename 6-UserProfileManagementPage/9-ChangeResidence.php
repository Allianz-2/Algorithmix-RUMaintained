<?php

if (isset($_POST['residenceID'])) {
    include '../8-PHPTests/connectionAzure.php';


    if ($_SESSION['role'] === 'HW') {
        // Check if the target residence already has a house warden assigned
        $stmt = $conn->prepare("SELECT COUNT(*) FROM residence WHERE ResidenceID = ?");
        $stmt->bind_param("s", $_POST['residenceID']);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $_SESSION['alert'] = 'Another house warden is already assigned to this residence';
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        $stmt = $conn->prepare("UPDATE residence SET HouseWardenID = ? WHERE ResidenceID = ?");
        $stmt->bind_param("ss", $_SESSION['userID'], $_POST['residenceID']);

        if ($stmt->execute()) {
            $_SESSION['alert'] = 'Residence updated successfully';
        } else {
            $_SESSION['alert'] = 'Failed to update residence';
        }

    } 
    else {
            // Proceed with updating the residence
        $stmt = $conn->prepare("UPDATE student SET ResidenceID = ? WHERE UserID = ?");
        $stmt->bind_param("ss", $_POST['residenceID'], $_SESSION['userID']);

        if ($stmt->execute()) {
            $_SESSION['alert'] = 'Residence updated successfully';
        } else {
            $_SESSION['alert'] = 'Failed to update residence';
        }
    }

    $stmt->close();
    
    // Unset the residence in $_POST to prevent resubmission issues
    unset($_POST['residenceID']);
    $conn->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>