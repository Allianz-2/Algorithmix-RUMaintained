document.addEventListener('DOMContentLoaded', function() {
    const userIDInput = document.getElementById('userID');
    const emailInput = document.getElementById('email_address');
    const form = document.getElementById('registration-form');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const errorMessageDiv = document.getElementById('error-message');

    // Check if the form exists to ensure this script runs only on the registration pages
    if (form) {
        userIDInput.addEventListener('input', function() {
            const userIDValue = userIDInput.value;
            // Check if the form is for Student Registration
            if (form.getAttribute('data-role') === 'student') {
                emailInput.value = userIDValue + '@campus.ru.ac.za';
            } else {
                emailInput.value = userIDValue + '@ru.ac.za';
            }
        });

        form.addEventListener('submit', function(event) {
            // Clear previous error message
            errorMessageDiv.textContent = '';

            // Check if passwords match
            if (passwordInput.value !== confirmPasswordInput.value) {
                event.preventDefault(); // Prevent form submission
                errorMessageDiv.textContent = 'Passwords do not match.';
                errorMessageDiv.style.color = 'red';
            }
        });
    }
});