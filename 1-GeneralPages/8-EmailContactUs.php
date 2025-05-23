<?php
    // Load Composer's autoloader
    require_once '../../vendor/autoload.php';

    // Import Classess
    use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
    use Symfony\Component\Mailer\Transport;
    use Symfony\Component\Mailer\Mailer;
    use Symfony\Component\Mime\Email;


if (isset($_POST['submit'])) {
    unset($_POST['submit']);
    $name = $_POST['name'];
    $number = $_POST['phone'];
    $role = $_POST['role'];
    $emailSender = $_POST['email'];
    $message = $_POST['message'];

    $mailAdmin= "ru.maintained@gmail.com";



    // Create a Transport object
    $transport = Transport::fromDsn('smtp://ru.maintained@gmail.com:xdoikqmzweesqarm@smtp.gmail.com:587');

    // Create a Mailer object
    $mailer = new Mailer($transport);

    // Create an Email object
    $email = (new Email());

    // Set the "From address"
    $email->from($mailAdmin);

    // Set the "To address"
    $email  ->addTo($mailAdmin)
            ->addTo($emailSender);

    // Set "CC"
    # $email->cc('cc@example.com');
    // Set "BCC"
    # $email->bcc('bcc@example.com');
    // Set "Reply To"
    # $email->replyTo('fabien@example.com');
    // Set "Priority"
    # $email->priority(Email::PRIORITY_HIGH);

    // Set a "subject"
    $email->subject('Get in touch with: ' . $name . ' - ' . $emailFrom);

    // Set the plain-text "Body"
    $email->text($message);

    // Set HTML "Body"


    // WORK WITHIN "html()" !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


    // $email->html('
    //     <h1 style="color: #fff300; background-color: #0073ff; width: 500px; padding: 16px 0; text-align: center; border-radius: 50px;">
    //     The HTML version of the message.
    //     </h1>
    //     <img src="cid:Image_Name_1" style="width: 600px; border-radius: 50px">
    //     <br>
    //     <img src="cid:Image_Name_2" style="width: 600px; border-radius: 50px">
    //     <h1 style="color: #ff0000; background-color: #5bff9c; width: 500px; padding: 16px 0; text-align: center; border-radius: 50px;">
    //     The End!
    //     </h1>
    // ');

    // Add an "Attachment"
    // $email->attachFromPath('example_1.txt');
    // $email->attachFromPath('example_2.txt');

    // // Add an "Image"
    // $email->embed(fopen('image_1.png', 'r'), 'Image_Name_1');
    // $email->embed(fopen('image_2.jpg', 'r'), 'Image_Name_2');

    // Sending email with status
    try {
        // Send email
        $mailer->send($email);

        // Display custom successful message
        $_SESSION['alert'] = "Email sent successfully!";
        exit(header("Location: 2-ContactUs.php"));
    } catch (TransportExceptionInterface $e) {
        // Display custom error message
        die('<style>* { font-size: 100px; color: #fff; background-color: #ff4e4e; }</style><pre><h1>&#128544;Error!</h1></pre><pre>' . $e->getMessage() . '</pre>');

        // Display real errors
        # echo '<pre style="color: red;">', print_r($e, TRUE), '</pre>';
    }
}
?>