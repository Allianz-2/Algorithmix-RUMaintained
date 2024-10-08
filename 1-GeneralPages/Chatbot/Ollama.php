<?php
    require_once '../../vendor/autoload.php';
    $newResponse = false;

    use ArdaGnsrn\Ollama\Ollama;
    use ArdaGnsrn\Ollama\Resources\Blobs;
    use ArdaGnsrn\Ollama\Resources\Chat;
    use ArdaGnsrn\Ollama\Resources\Completions;
    use ArdaGnsrn\Ollama\Resources\Embed;
    use ArdaGnsrn\Ollama\Resources\Models;

if (isset($_POST['submit-message'])) {
    unset($_POST['submit-message']);
    $client = \ArdaGnsrn\Ollama\Ollama::client('http://localhost:11434');

    if (!isset($_SESSION['Firstname'])) {
        $userName = 'User'; // Initialize the user's name
    } else {
        $userName = $_SESSION['Firstname']; // Get the user's name and make it bold
    }

    $message = $userName . ": " . $_POST['user-input'] . " \n"; // Get the user's message 
    $context = "Context:Limit all responses to a maximum of 50 words"; // Define the context


    if (!isset($_SESSION['history'])) {
        $_SESSION['history'] = $message ; // Initialize the chat history
    } else {
        $_SESSION['history'] .= $message; // Append the message to the chat history
    }

    $completions = $client->completions()->create([
        'model' => 'llama3.2',
        'prompt' => $context . "History:" . $_SESSION['history'],
    ]);

    $responseArray = $completions->toArray(); // Convert the response to an array
    $response = 'Chatbot: ' . $responseArray['response'] . "\n"; 
    $_SESSION['history'] .= $response; // Append the message and response to the chat history 
    $newResponse = true;
}

?>
