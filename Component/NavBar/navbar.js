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
            } else if (userRole === "Admin") {
                navbarItems.innerHTML = `
                    <a href="./Super_Admin_home.html" class="navbar-item ${currentPage === 'Super_Admin_home.html' ? 'selected' : ''}">
                        <i class="fa fa-house"></i>
                        <span class="text">Home</span>
                    </a>
                    <a href="./Super_Admin_Support.html" class="navbar-item ${currentPage === 'Super_Admin_Support.html' ? 'selected' : ''}">
                        <i class="fa-regular fa-message"></i>
                        <span class="text">Support</span>
                    </a>
                    <a href="./Super_Admin_Profile.html" class="navbar-item ${currentPage === 'Super_Admin_Profile.html' ? 'selected' : ''}">
                        <i class="fa-regular fa-user"></i>
                        <span class="text">Profile</span>
                    </a>
                    <a href="./Super_Admin_Logout.html" class="navbar-item ${currentPage === 'Super_Admin_Logout.html' ? 'selected' : ''}">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span class="text">Log out</span>
                    </a> 
                `;
            } else if(userRole == "RegisteredUser") {
                navbarItems.innerHTML = `
                <a href="./Home.html" class="navbar-item ${currentPage === 'Home.html' ? 'selected' : ''}">
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
