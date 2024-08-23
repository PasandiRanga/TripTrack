// Function to load the busCard.html content
function loadBusCard() {
    fetch('./../../Component/BusCard/busCard.html') // Adjust the path based on the location of busCard.html
        .then(response => response.text())
        .then(data => {
            document.getElementById('bus-card-container').innerHTML = data;
        })
        .catch(error => console.error('Error loading busCard.html:', error));
}

// Call the function to load the bus card
loadBusCard();
