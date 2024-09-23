<?php
    if (isset($_POST['submit-password'])) {
        require '../8-PHPTests/config.php';
        session_start();
        $userID = $_SESSION['userID'];
        $oldPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        if ($newPassword != $confirmPassword) {
            echo "<script>alert('Passwords do not match. Please try again.'); window.location.href='1-ProfileStudent.php';</script>";
            exit();
        }

        $sql = "SELECT * FROM users WHERE userID = ?";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "<script>alert('SQL error occurred. Please try again.'); window.location.href='1-ProfileStudent.php';</script>";
            exit();
        } 
        else {
            mysqli_stmt_bind_param($stmt, "s", $userID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                $passwordCheck = password_verify($oldPassword, $row['Password']);
                if ($passwordCheck == false) {
                    echo "<script>alert('Incorrect current password. Please try again.'); window.location.href='1-ProfileStudent.php';</script>";
                    exit();
                } 
                else if ($passwordCheck == true) {
                    $sql = "UPDATE user SET password = ? WHERE userID = ?";
                    $stmt = mysqli_stmt_init($conn);

                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo "<script>alert('SQL error occurred. Please try again.'); window.location.href='1-ProfileStudent.php';</script>";
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, "ss", $newPassword, $userID);
                        mysqli_stmt_execute($stmt);
                        echo "<script>alert('Password changed successfully.'); window.location.href='1-ProfileStudent.php';</script>";
                        exit();
                    }
                } else {
                    echo "<script>alert('Incorrect current password. Please try again.'); window.location.href='7-ChangePassword.php';</script>";
                    exit();
                }
            } else {
                echo "<script>alert('User not found. Please try again.'); window.location.href='7-ChangePassword.php';</script>";
                exit();
            }
        }
    } 
?>
    
