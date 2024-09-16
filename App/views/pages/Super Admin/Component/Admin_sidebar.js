// admin-sidebar.js
function createSidebar() {
    // Create a container for the sidebar
    const sidebar = document.createElement('div');
    sidebar.classList.add('sidebar');

    // Create the sidebar content
    sidebar.innerHTML = `
        <h2>Admin Panel</h2>
        <ul class="sidebar-menu">
            <li><a href="/Sub_Pages/Manage_fleet/Manage_fleet.html">Manage Fleet</a></li>
            <li><a href="/Schedules/Schedules.html">Schedules</a></li>
            <li><a href="../Users/Users.html">Users</a></li>
            <li><a href="Attendence.html">Attendence</a></li>
            <li><a href="Reports.html">Reports</a></li>
        </ul>
    `;

    // Append the sidebar to the body
    document.body.appendChild(sidebar);
}

// Call the function to create and insert the sidebar
createSidebar();
