// Role selection removed; always sign up as student
function openSignup() {
    var roleInput = document.getElementById('signupRole');
    if (roleInput) roleInput.value = 'student';
    resetSignupSteps();
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
    const nextStepBtn = document.getElementById('nextStep');
    const prevStepBtn = document.getElementById('prevStep');
    
    if (nextStepBtn) {
        nextStepBtn.addEventListener('click', nextStep);
    }
    
    if (prevStepBtn) {
        prevStepBtn.addEventListener('click', prevStep);
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

    // Sliding paragraph functionality
    const slides = document.querySelectorAll('.slide');
    const indicators = document.querySelectorAll('.indicator');
    let currentSlide = 0;
    let slideInterval;
    const AUTO_SLIDE = true; // set to false to disable auto sliding
    const SLIDE_INTERVAL_MS = 8000; // slower auto-slide interval (ms)

    function showSlide(index) {
        // Remove active class from all slides and indicators
        slides.forEach(slide => slide.classList.remove('active'));
        indicators.forEach(indicator => indicator.classList.remove('active'));
        
        // Add active class to current slide and indicator
        slides[index].classList.add('active');
        indicators[index].classList.add('active');
        
        currentSlide = index;
    }

    function nextSlide() {
        const nextIndex = (currentSlide + 1) % slides.length;
        showSlide(nextIndex);
    }

    function prevSlide() {
        const prevIndex = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(prevIndex);
    }

    function startSliding() {
        if (!AUTO_SLIDE) return;
        slideInterval = setInterval(nextSlide, SLIDE_INTERVAL_MS);
    }

    function stopSliding() {
        clearInterval(slideInterval);
    }

    // Add click event listeners to indicators
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            showSlide(index);
            stopSliding();
            startSliding(); // Restart auto-sliding
        });
    });

    // Hook up invisible nav hit areas
    const slideNextBtn = document.getElementById('nextSlideBtn');
    const slidePrevBtn = document.getElementById('prevSlideBtn');
    if (slideNextBtn) {
        slideNextBtn.addEventListener('click', () => {
            nextSlide();
            stopSliding();
            startSliding();
        });
    }
    if (slidePrevBtn) {
        slidePrevBtn.addEventListener('click', () => {
            prevSlide();
            stopSliding();
            startSliding();
        });
    }

    // Start auto-sliding when page loads
    if (slides.length > 0) {
        startSliding();
        
        // Pause sliding on hover, resume on mouse leave
        const slidingSection = document.querySelector('.sliding-section');
        if (slidingSection) {
            slidingSection.addEventListener('mouseenter', stopSliding);
            slidingSection.addEventListener('mouseleave', startSliding);
        }
    }

    console.log('All event listeners set up successfully');
});
