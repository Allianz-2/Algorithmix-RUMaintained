<?php
    // Load Composer's autoloader
    require_once '../../vendor/autoload.php';

    // Import Classess
    use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
    use Symfony\Component\Mailer\Transport;
    use Symfony\Component\Mailer\Mailer;
    use Symfony\Component\Mime\Email;


if ($registration_success) {
    $mailAdmin= "ru.maintained@gmail.com";
    $message = 'Dear ' . $firstname . ' ' . $lastname . ',<br><br>' . 
                'Thank you for registering with RUMaintained. 
                Your account has been successfully created. You can now log in with your 
                username and password here: "http://localhost/Algorithmix-RUMaintained/1-GeneralPages/1-Home.php".
                <br><br>Regards,<br>RUMaintained Team';

    // Create a Transport object
    $transport = Transport::fromDsn('smtp://ru.maintained@gmail.com:xdoikqmzweesqarm@smtp.gmail.com:587');

    // Create a Mailer object
    $mailer = new Mailer($transport);

    // Create an Email object
    $emailSend = (new Email());

    // Set the "From address"
    $emailSend->from($mailAdmin);

    // Set the "To address"
    $emailSend->addTo($email);
    // ->addTo($mailAdmin);

    // Set "CC"
    # $email->cc('cc@example.com');
    // Set "BCC"
    $emailSend->bcc($mailAdmin);
    // Set "Reply To"
    # $email->replyTo('fabien@example.com');
    // Set "Priority"
    # $email->priority(Email::PRIORITY_HIGH);

    // Set a "subject"
    $emailSend->subject('RUMaintained: Registration Successful!');

    // Set the plain-text "Body"
    $emailSend->text($message);

    // Set HTML "Body"
    $emailSend->html('
        <p>Dear ' . $firstname . ' ' . $lastname . ',<br><br>
        Thank you for registering with RUMaintained. Your account has been successfully created. 
        You can now log in with your username and password here: 
        <a href="http://localhost/Algorithmix-RUMaintained/5-UserSignInandRegistration/6-SignInPage.php">Login</a>.
        </p>

        <br>

        <p>Regards,<br>RUMaintained Team</p>
    ');


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
        // $_SESSION['alert'] = "Email sent successfully!";
    } catch (TransportExceptionInterface $e) {
        // Display custom error message
        die('<style>* { font-size: 100px; color: #fff; background-color: #ff4e4e; }</style><pre><h1>&#128544;Error!</h1></pre><pre>' . $e->getMessage() . '</pre>');

        // Display real errors
        # echo '<pre style="color: red;">', print_r($e, TRUE), '</pre>';
    }
}
?>



<!-- $userID = strtoupper($_POST['userID']);
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $password = $_POST['password'];
    $email = strtoupper($_POST['email_address']);
    $resID = $_POST['residenceID'];
    $hallID = isset($_POST['hall']) ? $_POST['hall'] : '';
    $role = $_POST['role'];
    $specialisation = isset($_POST['specialisation']) ? $_POST['specialisation'] : ''; -->