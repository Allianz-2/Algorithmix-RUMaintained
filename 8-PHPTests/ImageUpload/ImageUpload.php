<?php
// include 'ImageUpload2.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if a file has been uploaded
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        // Handle the file upload
        $file = $_FILES['photo'];
        $uploadDirectory = '../../Images/TicketPictures/';
        $uploadFile = $uploadDirectory . basename($file['name']);

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            echo "File is valid, and was successfully uploaded.\n";
        } else {
            echo "Possible file upload attack!\n";
        }
    } else {
        echo "No file uploaded or there was an upload error.\n";
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Ticket</title>
    <link rel="icon" href="../Images/General/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../7-TicketCreation/7-CSS/1-TicketCreation.css">
    <script src="4-TicketCreation.js"></script>
</head>
<body>
<form action="ImageUpload.php" method="post" enctype="multipart/form-data">
    <input type="hidden" id="status" name="status" value="Open">
    <div class="form-group">
        <label for="photo">Upload Photo:</label>
        <!-- Container for the custom upload area -->
        <div class="input-container">
            <!-- Custom upload area with the file input hidden -->
            <div class="upload-area" id="upload-area" name="uploaded-imae" onclick="document.getElementById('photo').click();">
                <i class="fas fa-image"></i>
                <p>Click or drag Photo to this area to upload</p>
                <!-- Actual file input, hidden from view -->
                <input type="file" name="photo" id="photo" style="display: none;" onchange="handleFileSelect(event)" accept="image/*">
            </div>
        </div>
        <script>
            function handleFileSelect(event) {
                var fileInput = event.target;
                var uploadArea = document.getElementById('upload-area');
                if (fileInput.files && fileInput.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        uploadArea.innerHTML += '<img src="' + e.target.result + '" alt="Uploaded Photo" style="max-width: 100%;">';
                    };
                    reader.readAsDataURL(fileInput.files[0]);
                }
            }
        </script>
    </div>
    <div class="button-group">
        <input type="submit" name="submit" value="Create Ticket">
        <!-- <input type="submit" name="SAVE" value="Save Ticket"> -->
    </div>
</form>
</body>
</html>