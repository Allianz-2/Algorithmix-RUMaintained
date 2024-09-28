<?php
require 'config.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $confirmDelete = $_POST['confirm_delete'];

    if ($confirmDelete === 'DELETE') {
        // Start a transaction
        $conn->begin_transaction();

        try {
            // Delete related records
            $stmt = $conn->prepare("DELETE FROM related_table WHERE user_id = (SELECT id FROM users WHERE email = ?)");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            // Delete the user
            $stmt = $conn->prepare("DELETE FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            // Commit the transaction
            $conn->commit();

            echo "Your account has been deleted.";
        } catch (Exception $e) {
            // Rollback the transaction if something goes wrong
            $conn->rollback();
            echo "Failed to delete account: " . $e->getMessage();
        }
    } else {
        echo "Confirmation text does not match.";
    }
}
?>