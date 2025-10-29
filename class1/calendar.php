<?php
// --- PHP: Include database connection and start session ---
include "myconnector.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar - ClassXic</title>
    <!-- Link to landingpage CSS for consistent design -->
    <link rel="stylesheet" href="css/landingpage.css">
    <!-- Link to calendar CSS for sidebar responsiveness -->
    <link rel="stylesheet" href="css/calendar.css">
    <!-- Additional calendar-specific styles -->
    <style>
        /* Calendar-specific styles */
       
        
        .calendar-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .calendar-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        
        .calendar-header p {
            color: #6b7280;
            font-size: 1.1rem;
        }
        
        #calendar {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        /* FullCalendar customization */
        .fc {
            font-family: 'OpenDyslexic', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .fc-toolbar {
            margin-bottom: 2rem !important;
        }
        
        .fc-toolbar-title {
            font-size: 1.5rem !important;
            font-weight: 600 !important;
            color: #2d3748 !important;
        }
        
        .fc-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 0.5rem 1rem !important;
            font-weight: 500 !important;
            transition: all 0.3s ease !important;
        }
        
        .fc-button:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3) !important;
        }
        
        .fc-button:active {
            transform: translateY(0) !important;
        }
        
        .fc-daygrid-day {
            border-radius: 8px !important;
            margin: 2px !important;
        }
        
        .fc-daygrid-day:hover {
            background-color: rgba(102, 126, 234, 0.05) !important;
        }
        
        .fc-event {
            border-radius: 6px !important;
            border: none !important;
            font-weight: 500 !important;
        }
        
        .fc-event-main {
            padding: 2px 6px !important;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .calendar-container {
                padding: 1rem;
                margin-top: 80px;
            }
            
            .calendar-header h1 {
                font-size: 2rem;
            }
            
            #calendar {
                padding: 1rem;
            }
        }
        
        /* Notification modal improvements */
        .calendar-notify-modal-content {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }
        
        .calendar-notify-title {
            color: #2d3748;
            margin: 1rem 0;
        }
        
        .calendar-notify-message {
            color: #6b7280;
            line-height: 1.6;
        }

        /* Event Creation Modal Styles */
        .event-creation-modal-content {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem 1rem 2rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            background-color: #f3f4f6;
            color: #374151;
        }

        .modal-body {
            padding: 1.5rem 2rem;
        }

        .modal-footer {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding: 1rem 2rem 1.5rem 2rem;
            border-top: 1px solid #e5e7eb;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'OpenDyslexic', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            transition: all 0.3s ease;
            background-color: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'OpenDyslexic', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
            transform: translateY(-1px);
        }

        /* Responsive modal */
        @media (max-width: 768px) {
            .event-creation-modal-content {
                width: 95%;
                margin: 1rem;
            }
            
            .modal-header,
            .modal-body,
            .modal-footer {
                padding: 1rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
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
        <div class="nav-center">ClassXic</div>
        <!-- User Info -->
        <div class="user-info">
            <span><?php echo htmlspecialchars($_SESSION['first_name']); ?></span>
            <img src="Images/user-svgrepo-com.svg" alt="User Icon">
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-content">
            <ul class="sidebar-nav">
                <li><a href="landingpage.php"><img src="Images/home-svgrepo-com.svg" alt="Home Icon"> Home</a></li>
                <li><a href="calendar.php"><img src="Images/calendar-month-svgrepo-com.svg" alt="Calendar Icon"> Calendar</a></li>
                <li><a href="studentmodule.php"><img src="Images/book-svgrepo-com.svg" alt="Modules Icon"> Modules</a></li>
                <li><a href="tutorlist.php"><img src="Images/user-svgrepo-com.svg" alt="Tutors Icon"> Tutor</a></li>
                <li><a href="progress.php"><img src="Images/progress-svgrepo-com.svg" alt="Progress Icon">Progress</a></li>
            </ul>
            <div class="sidebar-bottom">
                <ul class="sidebar-options">
                    <li>
                        <a href="#" class="dropdown-toggle"><img src="Images/option.png" alt="Option Icon">Option</a>
                        <ul class="dropdown-menu">
                            <li><a href="settings.php"><img src="Images/settings-2-svgrepo-com.svg" alt="Settings Icon"> Settings</a></li>
                            <li><a href="logout.php"><img src="Images/logout-svgrepo-com.svg" alt="Logout Icon">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Notification Modal -->
    <div id="calendarNotifyModal" class="modal" style="display:none;">
        <div class="modal-content calendar-notify-modal-content">
            <div class="calendar-notify-checkmark">
                <svg width="80" height="80" viewBox="0 0 120 120">
                    <circle cx="60" cy="60" r="54" fill="none" stroke="#19d219" stroke-width="4"/>
                    <polyline points="40,65 55,80 80,45" fill="none" stroke="#19d219" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h2 id="calendarNotifyTitle" class="calendar-notify-title"></h2>
            <p id="calendarNotifyMsg" class="calendar-notify-message"></p>
        </div>
    </div>

    <!-- Event Creation Modal -->
    <div id="eventCreationModal" class="modal" style="display:none;">
        <div class="modal-content event-creation-modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Create New Session</h3>
                <button class="modal-close" onclick="closeEventModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="userSelect" class="form-label">Choose User:</label>
                    <select id="userSelect" class="form-input">
                        <option value="">Select a user...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sessionName" class="form-label">Session Name:</label>
                    <input type="text" id="sessionName" class="form-input" placeholder="Enter session name">
                </div>
                <div class="form-group">
                    <label for="locationMode" class="form-label">Location/Mode:</label>
                    <input type="text" id="locationMode" class="form-input" placeholder="e.g., Room 101, Online, etc.">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="startTime" class="form-label">Start Time:</label>
                        <input type="time" id="startTime" class="form-input" value="08:00">
                    </div>
                    <div class="form-group">
                        <label for="endTime" class="form-label">End Time:</label>
                        <input type="time" id="endTime" class="form-input" value="09:00">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="createBtn" class="btn btn-primary">Create Session</button>
                <button id="cancelBtn" class="btn btn-secondary" onclick="closeEventModal()">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="calendar-container">
        <div class="calendar-header">
            <h1>Academic Calendar</h1>
            <p>Stay organized with your learning schedule and important dates</p>
        </div>
        <div id="calendar"></div>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-left">
            © ClassXic
        </div>
        <div class="footer-right">
            <a href="#"><img src="Images/facebook.png" alt="Facebook"></a>
            <a href="#"><img src="Images/social.png" alt="Instagram"></a>
            <a href="#"><img src="Images/linkedin.png" alt="LinkedIn"></a>
            <a href="#"><img src="Images/twitter.png" alt="X"></a>
            <span class="footer-directory">SOCIAL MEDIA PAGES</span>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="script/calendar.js"></script>
    <script src="script/landingpage.js"></script>

</body>
</html>