// searchBar.js

document.addEventListener('DOMContentLoaded', () => {
    function loadSearchBar() {
        const container = document.getElementById('search-bar-container');
        if (container) {
            fetch('./../../Component/SearchBar/searchBar.html')
                .then(response => response.text())
                .then(html => {
                    container.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error loading search bar:', error);
                });
        } else {
            console.error('Search bar container not found');
        }
    }

    // Load the search bar when the page loads
    loadSearchBar();

    // Add event listener to search button
    document.addEventListener('click', (event) => {
        if (event.target && event.target.matches('.search-button')) {
            const from = document.querySelector('input[placeholder="From"]').value;
            const to = document.querySelector('input[placeholder="To"]').value;
            const travelDate = document.querySelector('input[type="date"]').value;
            const time = document.querySelector('input[type="time"]').value;
            
            console.log("From:", from);
            console.log("To:", to);
            console.log("Travel Date:", travelDate);
            console.log("Time:", time);
        }
    });
});
