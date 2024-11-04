// script.js

import { createButtonComponent } from "./../../../../Component/Button/Button.js";

// Fetch the contact.html content and inject it into the page
fetch('./../../../../pages/Guest user/Components/ContactForm/contactForm.html')  
    .then(response => response.text())
    .then(html => {
        document.getElementById('contact-container').innerHTML = html;

        // After the form is loaded, create and insert the "Send" button
        const buttonComponent = createButtonComponent();
        const sendButton = buttonComponent.setLabel(1, 'Send'); // Create the button with the "Send" label
        sendButton.type = 'submit'; // Set the button type to submit
        document.querySelector('.contact-container form').appendChild(sendButton); // Append the button to the form
    })
    .catch(error => console.error('Error loading contact form:', error));