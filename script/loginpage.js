function openSignup(role) {
    document.getElementById('signupRole').value = role;

    // Show/hide parent-only fields
    document.querySelectorAll('.parent-only').forEach(el => {
        el.style.display = (role === 'parent') ? 'block' : 'none';
    });
    // Show/hide secretkey-only field
    document.querySelectorAll('.secretkey-only').forEach(el => {
        el.style.display = (role === 'parent') ? 'none' : 'block';
    });

    // Reset to step 1 when opening signup
    resetSignupSteps();

    // Hide role modal, show signup modal
    var roleModal = bootstrap.Modal.getInstance(document.getElementById('roleModal'));
    roleModal.hide();
    var signupModal = new bootstrap.Modal(document.getElementById('signupModal'));
    signupModal.show();
}

// Two-step signup functionality
function resetSignupSteps() {
    document.getElementById('step1').classList.add('active');
    document.getElementById('step2').classList.remove('active');
    document.getElementById('step2').style.display = 'none';
    document.getElementById('step1Indicator').classList.add('active');
    document.getElementById('step2Indicator').classList.remove('active');
    document.getElementById('signupProgress').style.width = '50%';
}

function nextStep() {
    // Validate step 1 fields
    const step1Fields = ['firstname', 'lastname', 'username', 'password', 'email'];
    let isValid = true;
    
    step1Fields.forEach(field => {
        const input = document.getElementById(field);
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });

    if (!isValid) {
        return;
    }

    // Move to step 2
    document.getElementById('step1').classList.remove('active');
    document.getElementById('step2').classList.add('active');
    document.getElementById('step2').style.display = 'block';
    document.getElementById('step1Indicator').classList.remove('active');
    document.getElementById('step2Indicator').classList.add('active');
    document.getElementById('signupProgress').style.width = '100%';
}

function prevStep() {
    // Move back to step 1
    document.getElementById('step2').classList.remove('active');
    document.getElementById('step1').classList.add('active');
    document.getElementById('step2').style.display = 'none';
    document.getElementById('step2Indicator').classList.remove('active');
    document.getElementById('step1Indicator').classList.add('active');
    document.getElementById('signupProgress').style.width = '50%';
}

// Sidebar functionality
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const body = document.body;
    const backdrop = document.getElementById('sidebarBackdrop');
    
    if (!sidebar) {
        console.log('Sidebar element not found');
        return;
    }
    
    console.log('Toggle sidebar called, current state:', sidebar.classList.contains('active'));
    
    // Toggle active class
    sidebar.classList.toggle('active');
    
    // Update body class and backdrop based on sidebar state
    if (sidebar.classList.contains('active')) {
        body.classList.remove('sidebar-hidden');
        if (backdrop) backdrop.classList.add('active');
        console.log('Sidebar now active, backdrop:', backdrop ? 'found' : 'not found');
    } else {
        body.classList.add('sidebar-hidden');
        if (backdrop) backdrop.classList.remove('active');
        console.log('Sidebar now hidden');
    }
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    
    if (!sidebar) {
        console.log('closeSidebar: Sidebar element not found');
        return;
    }
    
    console.log('closeSidebar called, sidebar active:', sidebar.classList.contains('active'));
    
    if (sidebar.classList.contains('active')) {
        sidebar.classList.remove('active');
        document.body.classList.add('sidebar-hidden');
        if (backdrop) backdrop.classList.remove('active');
        console.log('Sidebar closed successfully');
    } else {
        console.log('Sidebar was not active, nothing to close');
    }
}

// Add event listeners when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const nextBtn = document.getElementById('nextStep');
    const prevBtn = document.getElementById('prevStep');
    
    if (nextBtn) {
        nextBtn.addEventListener('click', nextStep);
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', prevStep);
    }

    // Reset steps when modal is closed
    const signupModal = document.getElementById('signupModal');
    if (signupModal) {
        signupModal.addEventListener('hidden.bs.modal', resetSignupSteps);
    }

    // Sidebar backdrop setup
    const backdrop = document.getElementById('sidebarBackdrop');
    if (backdrop) {
        backdrop.addEventListener('click', function() {
            console.log('Backdrop clicked, closing sidebar');
            closeSidebar();
        });
    } else {
        console.log('Backdrop element not found');
    }

    // Close sidebar when clicking outside
    document.addEventListener('click', function (event) {
        const sidebar = document.getElementById('sidebar');
        const burgerMenu = document.querySelector('.burger-menu');
        
        if (!sidebar || !burgerMenu) return;
        
        // Only close if sidebar is active and click is outside
        if (sidebar.classList.contains('active')) {
            const clickedInsideSidebar = sidebar.contains(event.target);
            const clickedBurger = burgerMenu.contains(event.target);
            
            if (!clickedInsideSidebar && !clickedBurger) {
                console.log('Click outside detected, closing sidebar');
                closeSidebar();
            }
        }
    });

    // Close sidebar with Escape key
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            console.log('Escape key pressed, closing sidebar');
            closeSidebar();
        }
    });

    // Dropdown functionality
    document.querySelectorAll('.dropdown-toggle').forEach(item => {
        item.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default anchor behavior
            const dropdownMenu = this.nextElementSibling; // Get the dropdown menu
            dropdownMenu.classList.toggle('active'); // Toggle the active class
        });
    });

    // Close the dropdown if clicked outside
    document.addEventListener('click', function(event) {
        const dropdowns = document.querySelectorAll('.dropdown-menu');
        dropdowns.forEach(dropdown => {
            if (!dropdown.previousElementSibling.contains(event.target) && dropdown.classList.contains('active')) {
                dropdown.classList.remove('active'); // Close the dropdown if clicked outside
            }
        });
    });

    // Feature box modal functionality - Modal 2
    const boxes = document.querySelectorAll('.feature-box');
    const featureModal = document.getElementById('featureModal');
    const featureModalTitle = document.getElementById('feature-modal-title');
    const featureModalText = document.getElementById('feature-modal-text');
    const featureModalImg = document.getElementById('feature-modal-img');
    const closeFeatureModal = document.getElementById('close-feature-modal');

    if (boxes.length > 0 && featureModal && featureModalTitle && featureModalText && featureModalImg && closeFeatureModal) {
        boxes.forEach(box => {
            box.addEventListener('click', () => {
                const title = box.getAttribute('data-title');
                const description = box.getAttribute('data-description');
                const image = box.getAttribute('data-image');
                featureModalTitle.textContent = title;
                featureModalText.innerHTML = `<span style="display:inline-block; width:2em;"></span>${description}`;
                featureModalImg.src = image;
                featureModalImg.alt = title + " icon";
                featureModal.style.display = 'flex';
            });
        });

        closeFeatureModal.addEventListener('click', () => {
            featureModal.style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target === featureModal) {
                featureModal.style.display = 'none';
            }
        });
    }

    console.log('All event listeners set up successfully');
});
