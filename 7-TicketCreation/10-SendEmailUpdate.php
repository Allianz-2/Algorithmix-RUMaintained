<?php
    // Load Composer's autoloader
    require_once '../../vendor/autoload.php';

    // Import Classess
    use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
    use Symfony\Component\Mailer\Transport;
    use Symfony\Component\Mailer\Mailer;
    use Symfony\Component\Mime\Email;

    include '../8-PHPTests/connectionAzure.php';

    $ticketID = $_GET['ticketID'];

    if (!isset($ticketID)) {
        $ticketID = 'T000001';
        }

    // Prepare the SQL statement to retrieve ticket information
    $stmt = $conn->prepare('SELECT * FROM ticket WHERE TicketID = ?');
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param('s', $ticketID);
    $stmt->execute();
    $result = $stmt->get_result();
    $ticketInfo = $result->fetch_assoc();
    $stmt->close();

    if ($ticketInfo) {
        $Status = $ticketInfo['Status'];
        $Description = $ticketInfo['Description'];
        $Severity = $ticketInfo['Severity'];
        $DateCreated = $ticketInfo['DateCreated'];
        $DateConfirmed = $ticketInfo['DateConfirmed'];
        $DateRequisitioned = $ticketInfo['DateRequisitioned'];  
        $DateResolved = $ticketInfo['DateResolved'];
        $DateClosed = $ticketInfo['DateClosed'];
        $ResidenceID = $ticketInfo['ResidenceID'];
        $StudentID = $ticketInfo['StudentID'];
        $HouseWardenID = $ticketInfo['HouseWardenID'];
        $CategoryID = $ticketInfo['CategoryID'];
        $SAccesses = $ticketInfo['SAccesses'];
        $HSAccesses = $ticketInfo['HSAccesses'];
        $HWAccesses = $ticketInfo['HWAccesses'];
    }

    if (!is_null($StudentID)) {
        // Retrieve email address using studentID
        $sql = "SELECT Email_Address FROM user WHERE UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $StudentID);
        $stmt->execute();
        $stmt->bind_result($emailSendTo_S);
        
        if ($stmt->fetch()) {
            // Email address is now stored in $emailSender
        } else {
            die("No user found with ID: " . $studentID);
            exit();
        }
    
        $stmt->close();
    }

    if (!is_null($HouseWardenID)) {
        // Retrieve email address using studentID
        $sql = "SELECT Email_Address FROM user WHERE UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $HouseWardenID);
        $stmt->execute();
        $stmt->bind_result($emailSendTo_HW);
        
        if ($stmt->fetch()) {
            // Email address is now stored in $emailSender
        } else {
            die("No user found with ID: " . $studentID);
            exit();
        }
    
        $stmt->close();
    }

    
    if (!is_null($ResidenceID)) {
        // Retrieve HallSecretaryID using HouseWardenID
        $sql = "SELECT HallSecretaryID FROM residence WHERE ResidenceID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $ResidenceID);
        $stmt->execute();
        $stmt->bind_result($HallSecretaryID);
        
        if ($stmt->fetch()) {
            // HallSecretaryID is now stored in $HallSecretaryID
        } else {
            die("No residence found with ResidenceID: " . $ResidenceID);
            exit();
        }
        
        $stmt->close();
    }

    if (!is_null($HallSecretaryID)) {
        // Retrieve email address using HallSecretaryID
        $sql = "SELECT Email_Address FROM user WHERE UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $HallSecretaryID);
        $stmt->execute();
        $stmt->bind_result($emailSendTo_HS);
        
        if ($stmt->fetch()) {
            // Email address is now stored in $emailSendTo_HS
        } else {
            die("No user found with ID: " . $HallSecretaryID);
            exit();
        }
        
        $stmt->close();
    }





    $mailAdmin= "ru.maintained@gmail.com";

    // Create a Transport object
    $transport = Transport::fromDsn('smtp://ru.maintained@gmail.com:xdoikqmzweesqarm@smtp.gmail.com:587');

    // Create a Mailer object
    $mailer = new Mailer($transport);

    // Create an Email object
    $emailSend = (new Email());

    // Set the "From address"
    $emailSend->from($mailAdmin);

    // Set the "To address"
    if (!is_null($emailSendTo_S)) {
        $emailSend->addTo($emailSendTo_S);
    }
    
    if (!is_null($emailSendTo_HW)) {
        $emailSend->cc($emailSendTo_HW);
    }

    if (!is_null($emailSendTo_HS) && $Status === 'Confirmed') {
        $emailSend->cc($emailSendTo_HS);
    }

    // Set "CC"
    // $emailSend->cc('cc@example.com');
    // Set "BCC"
    $emailSend->bcc($mailAdmin);
    // Set "Reply To"
    # $email->replyTo('fabien@example.com');
    // Set "Priority"
    # $email->priority(Email::PRIORITY_HIGH);

    // Set a "subject"
    $emailSend->subject('Ticket ' . $ticketID . ' Updated: ' . $Status);

    // Set the plain-text "Body"
    $message = "Ticket " . $ticketID . " has been updated to " . $status . ".\n\n";
    $emailSend->text($message);

    // Set HTML "Body"
    // Set HTML "Body"
    $htmlContent = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Ticket Update</title>
            <style>
                /* General styles for email compatibility */
                body {
                    font-family: Arial, sans-serif;
                    color: #333333;
                    background-color: #f9f9f9;
                    margin: 0;
                    padding: 0;
                }
                .email-container {
                    max-width: 600px;
                    margin: 20px auto;
                    background-color: #ffffff;
                    padding: 20px;
                    border: 1px solid #dddddd;
                    border-radius: 8px;
                }
                .header {
                    background-color: #81589a;
                    color: white;
                    padding: 15px;
                    text-align: center;
                    font-size: 24px;
                    border-radius: 8px 8px 0 0;
                }
                .content div {
                    margin: 10px 0;
                }
                .label {
                    font-weight: bold;
                    color: #81589a;
                }
                .footer {
                    text-align: center;
                    color: #777777;
                    font-size: 12px;
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <!-- Header -->
                <div class="header">Maintenance Ticket Update</div>

                <!-- Email Content -->
                <div class="content">
                    <!-- Ticket ID -->
                    <div>
                        <span class="label">Ticket ID:</span> ' . htmlspecialchars($ticketID) . '
                    </div>

                    <!-- Description of Fault -->
                    <div>
                        <span class="label">Description of Fault:</span> ' . htmlspecialchars($Description) . '
                    </div>

                    <!-- Severity of Fault -->
                    <div>
                        <span class="label">Severity of Fault:</span> ' . htmlspecialchars($Severity) . '
                    </div>

                    <!-- Dates -->
                    ' . (!empty($DateCreated) ? '<div><span class="label">Date Created:</span> ' . htmlspecialchars($DateCreated) . '</div>' : '') . '
                    ' . (!empty($DateConfirmed) ? '<div><span class="label">Date Confirmed:</span> ' . htmlspecialchars($DateConfirmed) . '</div>' : '') . '
                    ' . (!empty($DateRequisitioned) ? '<div><span class="label">Date Requisitioned:</span> ' . htmlspecialchars($DateRequisitioned) . '</div>' : '') . '
                    ' . (!empty($DateResolved) ? '<div><span class="label">Date Resolved:</span> ' . htmlspecialchars($DateResolved) . '</div>' : '') . '
                    ' . (!empty($DateClosed) ? '<div><span class="label">Date Closed:</span> ' . htmlspecialchars($DateClosed) . '</div>' : '') . '
                </div>

                <!-- Footer -->
                <div class="footer">
                    For more information please visit the <a href="http://localhost/Algorithmix-RUMaintained/7-TicketCreation/7-TicketStatus.php?ticketID=' . urlencode($ticketID) . '">Ticket Status Page</a>.
                </div>
            </div>
        </body>
        </html>
        ';
    

    // Set the HTML body
    $emailSend->html($htmlContent);

    // Add an "Attachment"
    // $email->attachFromPath('example_1.txt');
    // $email->attachFromPath('example_2.txt');

    // // Add an "Image"
    // $email->embed(fopen('image_1.png', 'r'), 'Image_Name_1');
    // $email->embed(fopen('image_2.jpg', 'r'), 'Image_Name_2');

    // Sending email with status
    try {
        // Send email
        $mailer->send($emailSend);

        // Display custom successful message
        $_SESSION['alert'] = "Email sent successfully!";
        echo '<script type="text/javascript">
            window.location.href = "7-TicketStatus.php?ticketID=' . urlencode($ticketID) . '";
            </script>';
        exit();
    } catch (TransportExceptionInterface $e) {
        // Display custom error message
        die('<style>* { font-size: 100px; color: #fff; background-color: #ff4e4e; }</style><pre><h1>&#128544;Error!</h1></pre><pre>' . $e->getMessage() . '</pre>');

        // Display real errors
        # echo '<pre style="color: red;">', print_r($e, TRUE), '</pre>';
    
    }
    $conn->close();
?>
