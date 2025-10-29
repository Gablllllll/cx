function redirectToNextPage() {
    // You can perform any form validation here if needed
    window.location.href = "mainpage.html";  // Replace with your target page
}


// Function to open the modal with content
function openModal(title, body) {
    const modal = document.getElementById("modal");
    document.getElementById("modal-title").textContent = title;
    document.getElementById("modal-body").textContent = body;
    modal.classList.remove("hidden");
    modal.style.display = "flex"; // Show modal
}

// Function to close the modal
function closeModal() {
    const modal = document.getElementById("modal");
    modal.style.display = "none"; // Hide modal
}

// Add event listeners for the close button
document.getElementById("closeModal").addEventListener("click", closeModal);

// Close modal on clicking outside the content
document.getElementById("modal").addEventListener("click", (e) => {
    if (e.target === e.currentTarget) closeModal();
});

/SIGNUP LOGIN DATABASE/
document.addEventListener("DOMContentLoaded", () => {
    showAnnouncementsPage(1); // Show first page of announcements
    showNewsPage(1);          // Show first page of news
});
// Function to handle step navigation
function nextStep() {
    const step1Inputs = document.querySelectorAll('#step1 input, #step1 select');
    for (const input of step1Inputs) {
        if (!input.checkValidity()) {
            alert('Please fill out all required fields in Step 1.');
            return;
        }
    }
    document.getElementById("step1").classList.add("hidden");
    document.getElementById("step2").classList.remove("hidden");
}

function previousStep() {
    document.getElementById("step2").classList.add("hidden");
    document.getElementById("step1").classList.remove("hidden");
}

// Form validation on submit

document.querySelector('.login-button').addEventListener('click', function(event) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    
    if (password !== confirmPassword) {
        event.preventDefault();  // Prevent form submission
        openModal('Password Mismatch', 'The passwords you entered do not match. Please try again.');
    }
});

// Function to open the modal
function openModal(title, body) {
    const modal = document.getElementById("modal");
    document.getElementById("modal-title").textContent = title;
    document.getElementById("modal-body").textContent = body;
    modal.style.display = "flex";
}

// Close the modal
function closeModal() {
    document.getElementById("modal").style.display = "none";
}

// Function to open the modal (inherited from the signup form)
function openModal(title, body) {
    const modal = document.getElementById("modal");
    document.getElementById("modal-title").textContent = title;
    document.getElementById("modal-body").textContent = body;
    modal.style.display = "flex";
}

// Close the modal when clicking outside the content
document.getElementById("modal").addEventListener("click", (e) => {
    if (e.target === e.currentTarget) closeModal();
});

function closeModal() {
    document.getElementById("modal").style.display = "none";
}
