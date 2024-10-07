<?php
    include 'Ollama.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot</title>
    <link rel="stylesheet" href="../OtherTests/chatbot.css">
</head>
<body>
    <form action="chatbotOllama.php" method="post" id="chatbot-form" class="chatbot-form">
        <button id="chatbot-toggle" class="chatbot-toggle" style="display: none;">Chat</button>
        <div class="chatbot-container" id="chatbot-container" style="display: flex;">
            <div class="chatbot-header" id="chatbot-header">
                <h3>Chatbot</h3>
            </div>
            <div class="chatbot-messages" id="chatbot-messages">
                <?php
                    if (isset($_POST['submit-message'])) {
                        // Assuming $completions is obtained from some function in Ollama.php
                        echo '<pre>' . htmlspecialchars($responseArray['response']) . '</pre>';
                    }
                ?>
            </div>
            <div class="chatbot-input">
                <input type="text" name="user-input" id="user-input" placeholder="How can we help you today?">
                <button type="submit" name="submit-message" class="submit-message">Send Message</button>
            </div>
        </div>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatbotContainer = document.getElementById('chatbot-container');
            const chatbotToggle = document.getElementById('chatbot-toggle');

            // Ensure the chatbot container is displayed by default
            chatbotContainer.style.display = 'flex';
            chatbotToggle.style.display = 'none';
        });
    </script>
</body>
</html>
