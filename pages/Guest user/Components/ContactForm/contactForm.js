// script.js

// Fetch the contact.html content and inject it into the page
fetch('./../../../Guest user/Components/ContactForm/contact.html')  
    .then(response => response.text())
    .then(html => {
        document.getElementById('contact-container').innerHTML = html;
    })
    .catch(error => console.error('Error loading contact form:', error));
