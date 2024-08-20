document.addEventListener("DOMContentLoaded", function() {
    // Assuming the user role is stored in localStorage (or sessionStorage)
    const userRole = localStorage.getItem('userRole') || "GuestUser"; // Default to GuestUser if not found

    // Get the current page URL
    const currentPage = window.location.pathname.split("/").pop();

    // Fetch the header.html content
    // Fetch the navbar.html content
    fetch("../../Component/NavBar/navbar.html")
        .then(response => response.text())
        .then(data => {
            // Inject the navbar HTML into the page
            document.getElementById("navbar-container").innerHTML = data;

            // Get the navbar items container
            const navbarItems = document.getElementById("navbar-items");

            // Customize the navbar based on the user role
            if (userRole === "GuestUser") {
                navbarItems.innerHTML = `
                    <a href="./home.html" class="navbar-item ${currentPage === 'home.html' ? 'selected' : ''}">
                        <i class="fa fa-bus"></i>
                        <span class="text">Search Buses</span>
                    </a>
                    <a href="./aboutus.html" class="navbar-item ${currentPage === 'aboutus.html' ? 'selected' : ''}">
                        <i class="fa fa-users"></i>
                        <span class="text">About Us</span>
                    </a>
                    <a href="./contactus.html" class="navbar-item ${currentPage === 'contactus.html' ? 'selected' : ''}">
                        <i class="fa fa-phone"></i>
                        <span class="text">Contact Us</span>
                    </a>
                `;
            } else if (userRole === "admin") {
                navbarItems.innerHTML = `
                    <a href="./dashboard.html" class="navbar-item ${currentPage === 'dashboard.html' ? 'selected' : ''}">
                        <i class="fa fa-tachometer-alt"></i>
                        <span class="text">Dashboard</span>
                    </a>
                    <a href="./users.html" class="navbar-item ${currentPage === 'users.html' ? 'selected' : ''}">
                        <i class="fa fa-users"></i>
                        <span class="text">Manage Users</span>
                    </a>
                    <a href="./settings.html" class="navbar-item ${currentPage === 'settings.html' ? 'selected' : ''}">
                        <i class="fa fa-cog"></i>
                        <span class="text">Settings</span>
                    </a>
                `;
            } else if(userRole == "RegisteredUser") {
                navbarItems.innerHTML = `
                <a href="./home.html" class="navbar-item ${currentPage === 'home.html' ? 'selected' : ''}">
                        <i class="fa fa-home"></i>
                        <span class="text">Home</span>
                </a>

                <a href="./Bookings.html" class="navbar-item ${currentPage === 'Bookings.html' ? 'selected' : ''}">
                        <i class="fa fa-ticket" aria-hidden="true"></i>
                        <span class="text">Bookings</span>
                </a>

                <a href="./searchbus.html" class="navbar-item ${currentPage === 'searchbus.html' ? 'selected' : ''}">
                        <i class="fa fa-bus"></i>
                        <span class="text">Search Buses</span>
                </a>

                <a href="./notifications.html" class="navbar-item ${currentPage === 'notifications.html' ? 'selected' : ''}">
                        <i class="fa fa-bell" aria-hidden="true"></i>
                        <span class="text">Notifications</span>
                </a>

                <a href="./contactus.html" class="navbar-item ${currentPage === 'contactus.html' ? 'selected' : ''}">
                        <i class="fa fa-phone" aria-hidden="true"></i>
                        <span class="text">Contact Us</span>
                </a>
                `;
            }// Add more roles as needed
        })
        .catch(error => console.error("Error loading navbar:", error));
});
