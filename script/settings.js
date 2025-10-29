function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active');
  }

  // User dropdown functionality
  function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('active');
  }

  // Close dropdown when clicking outside
  document.addEventListener('click', function (event) {
    const sidebar = document.getElementById('sidebar');
    const burgerMenu = document.querySelector('.burger-menu');
    const userInfo = document.querySelector('.user-info');
    const dropdown = document.getElementById('userDropdown');

    // Close sidebar if clicked outside
    if (!sidebar.contains(event.target) && !burgerMenu.contains(event.target)) {
      sidebar.classList.remove('active');
    }

    // Close user dropdown if clicked outside
    if (!userInfo.contains(event.target)) {
      dropdown.classList.remove('active');
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


  // Show settings section
  function showSettings() {
      const modal = document.getElementById('settingsModal');
      if (modal) {
          modal.style.display = 'flex';
          modal.classList.add('active');
      }
      document.getElementById('userDropdown').classList.remove('active');
  }

  // Show profile section
  function showProfile() {
      document.getElementById('userDropdown').classList.remove('active');
  }

  // Close settings modal
  function closeSettingsModal() {
      const modal = document.getElementById('settingsModal');
      if (modal) {
          modal.style.display = 'none';
          modal.classList.remove('active');
      }
  }

  // Font size change functionality
  function changeFontSize(size) {
      const body = document.body;
      body.classList.remove('font-small', 'font-medium', 'font-large', 'font-extra-large');
      body.classList.add('font-' + size);
      
      // Store in localStorage
      localStorage.setItem('fontSize', size);
  }

  // Line spacing change functionality
  function changeLineSpacing(spacing) {
      const body = document.body;
      body.classList.remove('line-tight', 'line-normal', 'line-relaxed', 'line-loose');
      body.classList.add('line-' + spacing);
      
      // Store in localStorage
      localStorage.setItem('lineSpacing', spacing);
  }

  // Save settings
  function saveSettings() {
      alert('Settings saved successfully!');
      closeSettingsModal();
  }

  // Load saved settings on page load
  document.addEventListener('DOMContentLoaded', function() {
      // Load font size
      const savedFontSize = localStorage.getItem('fontSize') || 'medium';
      document.getElementById('fontSize').value = savedFontSize;
      changeFontSize(savedFontSize);

      // Load line spacing
      const savedLineSpacing = localStorage.getItem('lineSpacing') || 'normal';
      document.getElementById('lineSpacing').value = savedLineSpacing;
      changeLineSpacing(savedLineSpacing);
  });