<?php
include "myconnector.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="css/calendar.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <!-- Burger Menu -->
    <div class="burger-menu" onclick="toggleSidebar()">
      <div></div>
      <div></div>
      <div></div>
    </div>
    <!-- Title -->
    <div class="nav-center">Classix</div>
    <!-- User Info -->
    <div class="user-info">
      <img src="Images/user-svgrepo-com.svg" alt="User Icon">
    </div>
  </nav>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <ul>
        <li><a href="#">Home</a></li>
       <li><a href="#">Features</a></li>
        <li><a href="#">About Us</a></li>
        <li>
            <a href="#" class="dropdown-toggle">Here</a>
            <ul class="dropdown-menu">
                <li><a href="#"><img src="Images/calendar-month-svgrepo-com.svg" alt="Calendar Icon"> Calendar</a></li>
                <li><a href="#"><img src="Images/book-svgrepo-com.svg" alt="Modules Icon"> Modules</a></li>
                <li><a href="#"><img src="Images/user-svgrepo-com.svg" alt="Student Icon"> Student</a></li>
                <li><a href="#"><img src="Images/settings-2-svgrepo-com.svg" alt="Settings Icon"> Settings</a></li>
            </ul>
        <li>
     </ul>
  </div>
    <div class="container">
        <h1 class="text-center mb-4">Calendar</h1>
        <div id="calendar"></div>
    </div>

  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script>
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
  </script>
</body>
</html>