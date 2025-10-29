function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active');
  }

  document.addEventListener('click', function (event) {
    const sidebar = document.getElementById('sidebar');
    const burgerMenu = document.querySelector('.burger-menu');

    // Close sidebar if clicked outside
    if (!sidebar.contains(event.target) && !burgerMenu.contains(event.target)) {
      sidebar.classList.remove('active');
    }
  });

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

  //featurebox
  const boxes = document.querySelectorAll('.feature-box');
  const modal = document.getElementById('modal');
  const modalTitle = document.getElementById('modal-title');
  const modalText = document.getElementById('modal-text');
  const modalImg = document.getElementById('modal-img');
  const closeModal = document.getElementById('close-modal');

  boxes.forEach(box => {
      box.addEventListener('click', () => {
          const title = box.getAttribute('data-title');
          const description = box.getAttribute('data-description');
          const image = box.getAttribute('data-image');
          modalTitle.textContent = title;
          modalText.innerHTML = `<span style="display:inline-block; width:2em;"></span>${description}`;
          modalImg.src = image;
          modalImg.alt = title + " icon";
          modal.style.display = 'flex';
      });
  });

  closeModal.addEventListener('click', () => {
      modal.style.display = 'none';
  });

  window.addEventListener('click', (e) => {
      if (e.target === modal) {
          modal.style.display = 'none';
      }
  });