document.addEventListener('DOMContentLoaded', () => {
    // Get the footer container
    const footerContainer = document.getElementById('footer-container');
    
    // Load footer.html content and inject it into the footer container
    fetch('./../../Component/Footer/footer.html')
        .then(response => response.text())
        .then(html => {
            footerContainer.innerHTML = html;

            // Optionally, execute further logic here, such as loading dynamic data from topRoutes.js
            const busRoutesList = document.getElementById('bus-routes-list');
            if (busRoutesList) {
                // Use the global variable defined in topRoutes.js
                window.topBusRoutes.forEach(route => {
                    const routeDiv = document.createElement('div');
                    routeDiv.textContent = route;
                    busRoutesList.appendChild(routeDiv);
                });
            }
        })
        .catch(error => {
            console.error('Error loading footer.html:', error);
        });
});
