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
  <style>
    body {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
      background-color: #fcfbd7;
      font-family: 'OpenDyslexic', sans-serif;
    }
    @font-face {
      font-family: 'OpenDyslexic';
      src: url('fonts/OpenDyslexic-Bold.otf') format('opentype'),
            url('fonts/OpenDyslexic-Regular.otf') format('opentype');
    }
    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 15px;
      background-color: #fcfbd7;
      border-bottom: 1px solid #ccc;
      z-index: 1000;
    }
    .burger-menu {
      display: flex;
      flex-direction: column;
      cursor: pointer;
    }
    .burger-menu div {
      width: 25px;
      height: 3px;
      background-color: black;
      margin: 4px 0;
    }
    .nav-center {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      font-size: 30px;
      font-weight: bold;
    }
    .user-info {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-right: 20px;
    }
    .user-info img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
    }
    .sidebar {
      position: fixed;
      top: 0;
      left: -400px;
      width: 300px;
      height: 100%;
      background-color: #fcfbd7;
      padding: 20px;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
      transition: left 0.3s ease;
      z-index: 10000;
    }
    .sidebar.active {
      left: 0;
    }
    .sidebar ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .sidebar ul li {
      margin: 20px 0;
    }
    .sidebar ul li a {
      text-decoration: none;
      color: black;
      font-size: 18px;
      display: block;
      padding: 10px;
      transition: background-color 0.3s;
    }
    .sidebar ul li a:hover {
        background-color: #ffcc00; /* Highlight color on hover */
        color: white;
        border-radius: 5px;
    }
    .sidebar ul li a img{
        width: 30px; /* Adjust the size of the icons */
        height: 30px;
    }
    #calendar {
        max-width: 1000px;
        margin: 20px auto;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .content {
      margin-top: 60px;
      padding: 20px;
    }
    .container h1 {
        display: flex;               /* Use flexbox */
        flex-direction: column;      /* Stack children vertically */
        align-items: center;         /* Center items horizontally */
        justify-content: center;     /* Center items vertically */
        max-width: 100%;            /* Ensure it doesn't exceed the width */
        margin-top: 100px;          /* Maintain the top margin */
    }
    @media  (max-width: 768px) {
      .sidebar{
        width: 200px;
      }
    }
  </style>
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
        <li><a href="#"><img src="Images/calendar-month-svgrepo-com.svg" alt="Calendar Icon"> Calendar</a></li>
        <li><a href="#"><img src="Images/book-svgrepo-com.svg" alt="Modules Icon"> Modules</a></li>
        <li><a href="#"><img src="Images/user-svgrepo-com.svg" alt="Tutors Icon"> Students</a></li>
        <li><a href="#"><img src="Images/settings-2-svgrepo-com.svg" alt="Settings Icon"> Settings</a></li>
     </ul>
  </div>
    <div class="container">
        <h1 class="text-center mb-4">My FullCalendar</h1>
        <div id="calendar"></div>
    </div>

  <!-- Content -->
  <div class="content">
    <h1>Welcome to My Webpage</h1>
    <p>This is the main content area.</p>
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