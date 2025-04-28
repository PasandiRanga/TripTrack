<?php
$rotatingTexts = [
    "Book your way with ease",
    "Colombo - Anuradhapura",
    "Colombo - Trincomalee",
    "Colombo - Galle",
    "Colombo - Kandy"
];
?>

<script>
    document.addEventListener('DOMContentLoaded', () => { 
        const fontLink = document.createElement('link');
        fontLink.href = 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap';
        fontLink.rel = 'stylesheet';
        document.head.appendChild(fontLink);

        document.body.classList.add('loaded');

        const rotatingTexts = <?php echo json_encode($rotatingTexts); ?>;

        let currentTextIndex = 0;

        function initStaticContent() {
            const textContainer = document.querySelector('.text-container');
            if (!textContainer) {
                console.error('Text container not found');
                return;
            }

            textContainer.innerHTML = ''; 

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

            textElement.classList.add('slide-out');

            setTimeout(() => {
                textElement.classList.remove('slide-out');

                textElement.textContent = rotatingTexts[currentTextIndex];
                currentTextIndex = (currentTextIndex + 1) % rotatingTexts.length;

                textElement.classList.add('slide-in');

                setTimeout(() => {
                    textElement.classList.remove('slide-in');
                }, 500); 
            }, 500); 
        }

        initStaticContent();

        setInterval(rotateText, 2000);
    });
</script>
