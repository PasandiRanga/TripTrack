function createButton({label, variant, icon, onClick}) {
    const template = document.createElement("template");

    // Set up the basic button structure with a placeholder class
    template.innerHTML = `
        <button class="custom-button ${variant ? `custom-button-${variant}` : ''}">
            ${icon ? `<span class="material-symbols-outlined">${icon}</span>` : ''}
            <span class="button-custom">${label}</span>
        </button>
    `;
    const button = template.content.firstElementChild;

    // Apply event listener if onClick is provided
    if (onClick) {  
        button.addEventListener("click", onClick);
    }

    return button;
}

//create a button for cancelations

function createButton_cancel({label, variant, icon, onClick}) {
    const template = document.createElement("template");

    // Set up the basic button structure with a placeholder class
    template.innerHTML = `
        <button class="cancel-button ${variant ? `cancel-button-${variant}` : ''}">
            ${icon ? `<span class="material-symbols-outlined">${icon}</span>` : ''}
            <span class="button-custom">${label}</span>
        </button>
    `;
    const button = template.content.firstElementChild;

    // Apply event listener if onClick is provided
    if (onClick) {  
        button.addEventListener("click", onClick);
    }

    return button;
}
//----------------------------------------------------------------------------------------------------------

// Example usage

//apply this code to your js file

const myButton = createButton({
    label: "Log in",
    variant: "custom-button",  // Use 'custom-button' to match CSS class
    onClick: () => alert('Button clicked!'),
});

document.body.appendChild(myButton);

// Another example usage without an icon or variant
const myButton1 = createButton({
    label: "Log in"
});

// Append the button to a container in the document
document.body.appendChild(myButton1);

console.log("");

const button2 = createButton_cancel({ //apply the createbutton_cancel for the cancelations
    label: "Cancel",
    variant: "cancel-button"
});
document.body.appendChild(button2);

