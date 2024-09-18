document.addEventListener('DOMContentLoaded', function() {
    const userIDInput = document.getElementById('userID');
    const lastnameInput = document.getElementById('lastname');
    const emailInput = document.getElementById('email_address');
    const form = document.getElementById('registration-form');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const errorMessageDiv = document.getElementById('error-message');
    const roleInput = document.getElementById('role'); // Ensure this element exists in your form

    // Check if the form exists to ensure this script runs only on the registration pages
    if (form) {
        lastnameInput.addEventListener('input', function() {
            const lastnameValue = lastnameInput.value;
            let userIDValue = '';

            // Check if the form is for Student Registration
            if (form.getAttribute('data-role') === 'student') {
                if (lastnameValue.length > 0) {
                    userIDValue = 'G';
                }
            } else {
                const role = roleInput.value; // Use the value of the role input
                if (lastnameValue.length > 0) {
                    userIDValue = 'S' + role + lastnameValue.charAt(0).toUpperCase();
                }
            }

            // Set the userID value
            userIDInput.value = userIDValue;
        });

        // Add event listener to update email when userID changes
        userIDInput.addEventListener('input', function() {
            const userIDValue = userIDInput.value;

            // Update email based on userID
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