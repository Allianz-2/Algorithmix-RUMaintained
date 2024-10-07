document.addEventListener('DOMContentLoaded', function() {
    const chatbotContainer = document.getElementById('chatbot-container');
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotHeader = document.getElementById('chatbot-header'); // ensure this element exists

    // Ensure the chatbot container is hidden initially
    chatbotContainer.style.display = 'none';
    
    // Toggle chatbot visibility
    chatbotToggle.addEventListener('click', function(event) {
        event.stopPropagation(); // Prevent the event from bubbling up
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
});