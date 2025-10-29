
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

// Feedback Modal Functions
function openFeedbackModal(feedback) {
    const modal = document.getElementById('feedbackModal');
    
    // Populate modal with feedback data
    document.getElementById('modalUserName').textContent = feedback.first_name && feedback.last_name 
        ? `${feedback.first_name} ${feedback.last_name}` 
        : feedback.username;
    
    document.getElementById('modalMaterialName').textContent = feedback.material_title;
    
    // Create rating stars
    const ratingContainer = document.getElementById('modalRating');
    ratingContainer.innerHTML = '';
    for (let i = 1; i <= 5; i++) {
        const star = document.createElement('span');
        star.className = `star ${i <= feedback.rating ? 'filled' : ''}`;
        star.textContent = '★';
        ratingContainer.appendChild(star);
    }
    
    document.getElementById('modalComment').textContent = feedback.comment || 'No comment provided';
    
    // Format date
    const date = new Date(feedback.created_at);
    document.getElementById('modalDate').textContent = 
        `Submitted on ${date.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        })}`;
    
    // Show modal
    modal.classList.add('active');
}

function closeFeedbackModal() {
    const modal = document.getElementById('feedbackModal');
    modal.classList.remove('active');
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('feedbackModal');
    if (event.target === modal) {
        closeFeedbackModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeFeedbackModal();
    }
});