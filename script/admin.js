
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const body = document.body;
    
    sidebar.classList.toggle('hidden');
    
    // Add/remove class on body to control content positioning
    if (sidebar.classList.contains('hidden')) {
        body.classList.add('sidebar-hidden');
    } else {
        body.classList.remove('sidebar-hidden');
    }
}

// Wait for DOM to be ready before adding event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar click outside to close
    document.addEventListener('click', function (event) {
        const sidebar = document.getElementById('sidebar');
        const burgerMenu = document.querySelector('.burger-menu');

        // Close sidebar if clicked outside
        if (sidebar && burgerMenu && !sidebar.contains(event.target) && !burgerMenu.contains(event.target)) {
            sidebar.classList.add('hidden');
            document.body.classList.add('sidebar-hidden');
        }
    });

    // Dropdown functionality
    document.querySelectorAll('.dropdown-toggle').forEach(item => {
        item.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default anchor behavior
            const dropdownMenu = this.nextElementSibling; // Get the dropdown menu
            if (dropdownMenu) {
                dropdownMenu.classList.toggle('active'); // Toggle the active class
            }
        });
    });
    
    // Close the dropdown if clicked outside
    document.addEventListener('click', function(event) {
        const dropdowns = document.querySelectorAll('.dropdown-menu');
        dropdowns.forEach(dropdown => {
            if (dropdown.previousElementSibling && !dropdown.previousElementSibling.contains(event.target) && dropdown.classList.contains('active')) {
                dropdown.classList.remove('active'); // Close the dropdown if clicked outside
            }
        });
    });

});
