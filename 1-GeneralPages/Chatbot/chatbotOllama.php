<?php
    include 'Ollama.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot</title>
    <!-- <link rel="stylesheet" href="chatbot.css"> -->
    <style>
        /* styles.css */
        .chatbot-container {
            width: 400px;
            height: 400px;
            font-family: Arial, sans-serif;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: none; /* Initially hidden */
            flex-direction: column;
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .chatbot-header {
            background-color: #81589a;
            color: #fff;
            padding: 10px;
            text-align: center;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            cursor: pointer;
        }

        .chatbot-messages {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            overflow-x: auto;
        }

        .chatbot-input {
            display: flex;
            border-top: 1px solid #ccc;
        }

        .chatbot-input input {
            flex: 1;
            padding: 10px;
            border: none;
            border-bottom-left-radius: 5px;
        }

        .chatbot-input button {
            padding: 10px;
            background-color: #81589a;
            color: #fff;
            border: none;
            cursor: pointer;
            border-bottom-right-radius: 5px;
        }

        .chatbot-input button:hover {
            background-color: #0056b3;
        }

        .chatbot-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #81589a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .chatbot-toggle:hover {
            background-color: #0056b3;
        }

        pre {
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <form action="1-Home.php" method="post" id="chatbot-form" class="chatbot-form">
        <button type="button" id="chatbot-toggle" class="chatbot-toggle">Chat</button>
        <div class="chatbot-container" id="chatbot-container" style="display: none;">
            <div class="chatbot-header" id="chatbot-header">
                <h3>Chatbot</h3>
            </div>
            <div class="chatbot-messages" id="chatbot-messages">
                <?php
                    if (isset($_SESSION['history']) && $newResponse) {
                        // Assuming $completions is obtained from some function in Ollama.php
                        $history = htmlspecialchars($_SESSION['history']);
                        $words = explode(' ', $history);
                        $formattedHistory = '';
                        foreach ($words as $index => $word) {
                            if (substr($word, -1) === ':') {
                                $formattedHistory .= '<strong>' . $word . '</strong> ';
                            } else {
                                $formattedHistory .= $word . ' ';
                            }
                            if (strpos($word, "\n") !== false) {
                                $formattedHistory .= '<br>';
                            } elseif (($index + 1) % 10 == 0) {
                                $formattedHistory .= '<br>';
                            }
                        }
                        echo '<pre>' . $formattedHistory . '</pre>';
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
    const chatbotHeader = document.getElementById('chatbot-header'); // ensure this element exists

    // Ensure the chatbot container is hidden initially
    chatbotContainer.style.display = 'none';
    
    // Toggle chatbot visibility
    chatbotToggle.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default form submission
        if (chatbotContainer.style.display === 'none' || chatbotContainer.style.display === '') {
            chatbotContainer.style.display = 'flex';
            chatbotToggle.style.display = 'none';
        }
    });

    // Add event listener to chatbot header for collapsing
    if (chatbotHeader) {  // add a check to ensure chatbotHeader exists
        chatbotHeader.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default form submission
            chatbotContainer.style.display = 'none';
            chatbotToggle.style.display = 'block';
        });
    }
});
</script>

</body>
</html>