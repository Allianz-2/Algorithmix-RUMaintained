document.addEventListener('DOMContentLoaded', function() {
    const sendButton = document.getElementById('send-button');
    const userInput = document.getElementById('user-input');
    const chatbotMessages = document.getElementById('chatbot-messages');
    const chatbotContainer = document.getElementById('chatbot-container');
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotHeader = document.getElementById('chatbot-header'); // ensure this element exists

    // Ensure the chatbot container is hidden initially
    chatbotContainer.style.display = 'none';
    
    // Toggle chatbot visibility
    chatbotToggle.addEventListener('click', function() {
        if (chatbotContainer.style.display === 'none' || chatbotContainer.style.display === '') {
            chatbotContainer.style.display = 'flex';
            chatbotToggle.style.display = 'none';
        }
    });

    // Add event listener to chatbot header for collapsing
    if (chatbotHeader) {  // add a check to ensure chatbotHeader exists
        chatbotHeader.addEventListener('click', function() {
            chatbotContainer.style.display = 'none';
            chatbotToggle.style.display = 'block';
        });
    }

    // Handle sending message when the send button is clicked
    sendButton.addEventListener('click', function() {
        const userMessage = userInput.value.trim();
        if (userMessage) {
            // Add the message to the chat
            addMessage('<strong>User</strong>', userMessage);
            
            // Process the user's message (assuming this function is defined below)
            processUserMessage(userMessage);
            
            // Clear the input field after message is sent
            userInput.value = '';
        }
    });

    // Send message on Enter key press
    userInput.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            sendButton.click();
        }
    });

    // Placeholder function to add a message to the chat
    function addMessage(sender, message) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('message');
        messageElement.innerHTML = `${sender}: ${message}`; // Ensure proper message format
        chatbotMessages.appendChild(messageElement);
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;  // Scroll to latest message
    }

    // Placeholder function to handle the processing of a user message
    function processUserMessage(message) {
            let response = '';
    
            // Basic keyword matching for common queries
            if (message.toLowerCase().includes('home')) {
                response = 'You can go to the home page by clicking <a href="/1-GeneralPages/1-Home.php">here</a>.';
            } else if (message.toLowerCase().includes('contact')) {
                response = 'You can go to the contact page by clicking <a href="/1-GeneralPages/2-ContactUs.php">here</a>.';
            } else if (message.toLowerCase().includes('about')) {
                response = 'You can go to the about page by clicking <a href="/1-Generalpages/3-AboutUs.php">here</a>.';
            } else if (message.toLowerCase().includes('ticket')) {
                response = 'You can go to the ticket page by clicking <a href="ticket.php">here</a>.';
            } else if (message.toLowerCase().includes('login')) {
                response = 'You can go to the login page by clicking <a href="/5-UserSignInandRegistration/6-SignInpage.php">here</a>.';
            } else if (message.toLowerCase().includes('register')) {
                response = 'You can go to the register page by clicking <a href="/5-UserSignInandRegistration/5-RegistrationStep1.html">here</a>.';
            } else {
                response = 'I am not sure how to help with that. Please try asking about "home", "contact", or "about".';
            }
        addMessage('<strong>Chatbot</strong>', response);
    }
});
