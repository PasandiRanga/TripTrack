document.addEventListener('DOMContentLoaded', () => {
    document.body.classList.add('loaded');
    const rotatingTexts = [
        { text: "Book your way with ease" },
        { text: "Anuradhapura" },
        { text: "Trincomalee" },
        { text: "Galle" },
        { text: "Mahanuwara" }
    ];

    let currentTextIndex = 0;

    function initStaticContent() {
        const textContainer = document.querySelector('.text-container');
        if (!textContainer) {
            console.error('Text container not found');
            return;
        }

        // Clear existing content before appending static content
        textContainer.innerHTML = ''; 

        // Create rotating text container
        const rotateTextDiv = document.createElement('div');
        rotateTextDiv.className = 'rotate-text';
        textContainer.appendChild(rotateTextDiv);
    }

    function rotateText() {
        const textElement = document.querySelector('.rotate-text');
        if (!textElement) {
            console.error('Rotate text container not found');
            return;
        }

        // Add slide-out animation
        textElement.classList.add('slide-out');

        // Wait for the slide-out animation to finish before changing the text
        setTimeout(() => {
            // Remove the slide-out class
            textElement.classList.remove('slide-out');

            // Change the text
            textElement.textContent = rotatingTexts[currentTextIndex].text;
            currentTextIndex = (currentTextIndex + 1) % rotatingTexts.length;

            // Add slide-in animation
            textElement.classList.add('slide-in');

            // Remove the slide-in class after the animation completes
            setTimeout(() => {
                textElement.classList.remove('slide-in');
            }, 500); // Match this time with the animation duration
        }, 500); // Match this time with the animation duration
    }

    // Initialize static content
    initStaticContent();

    // Rotate text every 2 seconds (2000ms)
    setInterval(rotateText, 2000);
});
