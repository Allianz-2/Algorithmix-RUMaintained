<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if a file has been uploaded
    echo "<script>alert('Uploaded file: " . $_FILES['photo']['name'] . "');</script>\n";

    if (isset($_FILES['photo'])) {
        var_dump($_FILES['photo']);
    } else {
        echo "No file uploaded.";
    }
    



    // if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
    //     // Handle the file upload
    //     $file = $_FILES['photo'];
    //     $uploadDirectory = '../../Images/TicketPictures/';
    //     $uploadFile = $uploadDirectory . basename($file['name']);

    //     // Move the uploaded file to the desired directory
    //     if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
    //         echo "<script>alert('File is valid, and was successfully uploaded.');</script>\n";
    //     } else {
    //         echo "Possible file upload attack!\n";
    //     }
    // } else {
    //     echo "<script>alert('No file uploaded or there was an upload error.');</script>\n";
    // }

}
?>