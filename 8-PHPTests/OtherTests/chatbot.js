document.addEventListener('DOMContentLoaded', function() {
    const sendButton = document.getElementById('send-button');
    const userInput = document.getElementById('user-input');
    const chatbotMessages = document.getElementById('chatbot-messages');
    const chatbotContainer = document.getElementById('chatbot-container');
    const chatbotToggle = document.getElementById('chatbot-toggle');

      // Ensure the chatbot container is hidden initially
      chatbotContainer.style.display = 'none';

    sendButton.addEventListener('click', function() {
        const userMessage = userInput.value.trim();
        if (userMessage) {
            addMessage('<strong>User</strong>', userMessage);
            processUserMessage(userMessage);
            userInput.value = '';
        }
    });

    userInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            sendButton.click();
        }
    });

    chatbotToggle.addEventListener('click', function() {
        if (chatbotContainer.style.display === 'none' || chatbotContainer.style.display === '') {
            chatbotContainer.style.display = 'flex';
            chatbotToggle.style.display = 'none';
        }
    });

    function addMessage(sender, message) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('message');
        messageElement.classList.add(sender.toLowerCase());
        messageElement.innerHTML = `${sender}: ${message}`; // Use innerHTML to allow HTML content
        chatbotMessages.appendChild(messageElement);
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    function processUserMessage(message) {
        let response = '';

        if (message.toLowerCase().includes('home')) {
            response = 'You can go to the home page by clicking <a href="/1-GeneralPages/1-Home.php">here</a>.';
        } else if (message.toLowerCase().includes('contact')) {
            response = 'You can go to the contact page by clicking <a href="contact.php">here</a>.';
        } else if (message.toLowerCase().includes('about')) {
            response = 'You can go to the about page by clicking <a href="about.php">here</a>.';
        } else if (message.toLowerCase().includes('ticket')) {
            response = 'You can go to the ticket page by clicking <a href="ticket.php">here</a>.';
        } else if (message.toLowerCase().includes('login')) {
            response = 'You can go to the login page by clicking <a href="/5-UserSignInandRegistration/6-SignInpage.php">here</a>.';
        } else if (message.toLowerCase().includes('register')) {
            response = 'You can go to the register page by clicking <a href="/5-UserSignInandRegistration/5-RegistrationStep1.html">here</a>.';
        }  else {
            response = 'I am not sure how to help with that. Please try asking about "home", "contact", or "about".';
        }
        addMessage('<strong>Chatbot</strong>', response);
    }
});