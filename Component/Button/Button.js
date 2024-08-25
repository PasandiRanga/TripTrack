function createButtonComponent() {
    // Create three buttons with specific classes
    const button1 = document.createElement("button");
    button1.classList.add('button-style-1');

    const button2 = document.createElement("button");
    button2.classList.add('button-style-2');

    const button3 = document.createElement("button");
    button3.classList.add('button-style-3');

    // Function to set the label for a specific button
    function setLabel(buttonNumber, label) {
        let button;
        switch(buttonNumber) {
            case 1:
                button1.textContent = label;
                button = button1;
                break;
            case 2:
                button2.textContent = label;
                button = button2;
                break;
            case 3:
                button3.textContent = label;
                button = button3;
                break;
            default:
                console.error("Invalid button number. Please use 1, 2, or 3.");
                return null;
        }
        return button; // Return the button with the specified label
    }

    // Return the function to set labels and get the specific button
    return {
        setLabel,
    };
}

// Export the function so it can be imported in another file
export { createButtonComponent };
