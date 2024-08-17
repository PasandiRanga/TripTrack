const navbarItems = document.querySelectorAll('.navbar-item');
    
        // Add click event listener to each navbar item
        navbarItems.forEach(item => {
            item.addEventListener('click', function() {
                // Remove the 'selected' class from all items
                navbarItems.forEach(nav => nav.classList.remove('selected'));
    
                // Add the 'selected' class to the clicked item
                this.classList.add('selected');
            });
        });