<?php
    session_start();
    include 'Ollama.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot</title>
    <link rel="stylesheet" href="..\..\8-PHPTests\OtherTests\chatbot.css">
    <style>
        pre {
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <form action="chatbotOllama.php" method="post" id="chatbot-form" class="chatbot-form">
        <button id="chatbot-toggle" class="chatbot-toggle">Chat</button>
        <div class="chatbot-container" id="chatbot-container" style="display: none;">
            <div class="chatbot-header" id="chatbot-header">
                <h3>Chatbot</h3>
            </div>
            <div class="chatbot-messages" id="chatbot-messages">
                <?php
                    if (isset($_SESSION['history'])) {
                        // Assuming $completions is obtained from some function in Ollama.php
                        echo '<pre>' . htmlspecialchars($_SESSION['history']) . '</pre>';
                    }
                ?>
            </div>
            <div class="chatbot-input">
                <input type="text" name="user-input" id="user-input" placeholder="How can we help you today?">
                <button type="submit" name="submit-message" class="submit-message">Send</button>
            </div>
        </div>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatbotContainer = document.getElementById('chatbot-container');
            const chatbotToggle = document.getElementById('chatbot-toggle');

            // Check localStorage for the state of the chatbot container
            if (localStorage.getItem('chatbotOpen') === 'true') {
                chatbotContainer.style.display = 'flex';
                chatbotToggle.textContent = 'Close';
            }

            chatbotToggle.addEventListener('click', function(event) {
                event.preventDefault();
                if (chatbotContainer.style.display === 'none' || chatbotContainer.style.display === '') {
                    chatbotContainer.style.display = 'flex';
                    chatbotToggle.textContent = 'Close';
                    localStorage.setItem('chatbotOpen', 'true');
                } else {
                    chatbotContainer.style.display = 'none';
                    chatbotToggle.textContent = 'Chat';
                    localStorage.setItem('chatbotOpen', 'false');
                }
            });
        });
    </script>
</body>
</html>
