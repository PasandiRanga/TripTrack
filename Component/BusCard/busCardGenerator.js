// Function to create a bus card HTML structure
function createBusCard(bus) {
    return `
        <div class="bus-card" onclick="navigateToBusLayout('${bus.id}')">
            <div class="bus-card-header">
                <div class="route-info">
                    <h2>${bus.route}</h2>
                    <span class="service-type">${bus.service}</span>
                </div>
                ${bus.cheapest ? '<div class="cheapest-badge">CHEAPEST</div>' : '<div class="expensive-badge">EXPENSIVE</div>'}
            </div>
            <div class="bus-card-timing">
                <div class="timing-info">
                    <div class="departure-time">
                        <span>${bus.departure}</span>
                    </div>
                    <div class="arrival-time">
                        <span>${bus.arrival}</span>
                    </div>
                </div>
                <div class="duration">
                    <span>${bus.duration}</span>
                </div>
                <div class="route-stops">
                    ${bus.stops.map((stop, index) => `
                        <span>${stop}</span>
                        ${index < bus.stops.length - 1 ? '<div class="route-line"></div>' : ''}
                    `).join('')}
                </div>
            </div>
            <div class="bus-card-footer">
                <div class="rating">
                    <i class="fas fa-star"></i>
                    <span>${bus.rating}</span>
                </div>
                <div class="passenger-count">
                    <i class="fas fa-user"></i>
                    <span>${bus.passengers}</span>
                </div>
                <div class="price">
                    <span>${bus.price}</span>
                </div>
            </div>
        </div>
    `;
}

// Function to navigate to the bus layout page
function navigateToBusLayout(busId) {
    window.location.href = `/Pages/Registered User/Bus_layout.html?busId=${busId}`;
}

// Function to load and display bus cards
function loadBusCards() {
    const busCardContainer = document.getElementById('bus-card-container');
    const cardsHtml = busDetails.map(createBusCard).join('');
    busCardContainer.innerHTML = cardsHtml;
}

// Ensure the script runs after the DOM is fully loaded
window.addEventListener('DOMContentLoaded', loadBusCards);
