<?php
    require_once '../../../vendor/autoload.php';

    use ArdaGnsrn\Ollama\Ollama;
    use ArdaGnsrn\Ollama\Resources\Blobs;
    use ArdaGnsrn\Ollama\Resources\Chat;
    use ArdaGnsrn\Ollama\Resources\Completions;
    use ArdaGnsrn\Ollama\Resources\Embed;
    use ArdaGnsrn\Ollama\Resources\Models;

if (isset($_POST['submit-message'])) {
    $client = \ArdaGnsrn\Ollama\Ollama::client('http://localhost:11434');

    $message = $_POST['user-input'];


    $context = "Context:Welcome to RUMaintained (a Residence Maintenance System) at Rhodes University, your trusted platform for 
                maintaining the comfort and safety of your home away from home. 
                Our system is designed to streamline the reporting and tracking of maintenance issues within university residences, 
                ensuring that every student enjoys a well-maintained living environment."; // Define the context

    $completions = $client->completions()->create([
        'model' => 'llama3.2',
        'prompt' => $context . $message,
    ]);

    $responseArray = $completions->toArray(); // Convert the response to an array
}

?>



