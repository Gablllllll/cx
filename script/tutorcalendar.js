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

      document.addEventListener('DOMContentLoaded', function () {
      const calendarEl = document.getElementById('calendar');

      const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        themeSystem: 'bootstrap5',
        events: [
          {
            title: 'Sample Event',
            start: new Date().toISOString().split('T')[0],
            color: '#0d6efd'
          }
        ]
      });

      calendar.render();
    });