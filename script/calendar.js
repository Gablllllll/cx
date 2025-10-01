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

// --- Close Sidebar When Clicking Outside ---
document.addEventListener('click', function (event) {
    const sidebar = document.getElementById('sidebar');
    const burgerMenu = document.querySelector('.burger-menu');
    if (sidebar && burgerMenu && !sidebar.contains(event.target) && !burgerMenu.contains(event.target)) {
        sidebar.classList.add('hidden');
        document.body.classList.add('sidebar-hidden');
    }
});



  // --- FULLCALENDAR INITIALIZATION AND LOGIC ---
  document.addEventListener('DOMContentLoaded', function () {
      const calendarEl = document.getElementById('calendar');
      // Get user info from globals injected by PHP in the page
      const userRole = (window.USER_ROLE || '').toString();
      const userId = Number(window.USER_ID || 0);

      // Initialize FullCalendar
      window.calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          themeSystem: 'bootstrap5',
          selectable: true, // Allow both tutor and student to create slots
          editable: userRole === 'tutor',   // Only tutors can drag/drop events (optional: set to true for all if you want)
          eventSources: [
              {
                  url: 'calendar_api.php', // Fetch events from API
                  method: 'GET',
                  failure: () => { alert('There was an error fetching events!'); }
              }
          ],

          
          select: async function(info) {
              // Fetch all users except self
              const res = await fetch('calendar_api.php?get_users=1');
              const users = await res.json();
              if (!Array.isArray(users) || users.length === 0) {
                  alert('No users found.');
                  return;
              }
              
              // Populate user select dropdown
              const userSelect = document.getElementById('userSelect');
              userSelect.innerHTML = '<option value="">Select a user...</option>';
              users.forEach(u => {
                  const option = document.createElement('option');
                  option.value = `${u.user_id}|${u.role}`;
                  option.textContent = `${u.name} (${u.role})`;
                  userSelect.appendChild(option);
              });
              
              // Store the selected date for later use
              window.selectedDate = info.startStr;
              
              // Show the modal
              document.getElementById('eventCreationModal').style.display = 'flex';
              window.calendar.unselect();
          },

          // --- EVENT CLICK: TUTOR (EDIT/DELETE) & STUDENT (BOOK/CANCEL) ---
          eventClick: function(info) {
              const eventObj = info.event;
              const props = eventObj.extendedProps;

              // --- Show event/session details for everyone ---
              let details = `Session: ${eventObj.title.replace(/^(Available: |Booked: )/, '')}\n`;
              details += `Date: ${eventObj.start.toLocaleDateString()}\n`;
              details += `Time: ${eventObj.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}`;
              if (eventObj.end) {
                  details += ` - ${eventObj.end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}`;
              }
              details += `\nLocation/Mode: ${props.location_mode || 'N/A'}`;
              details += `\nStatus: ${props.is_booked ? 'Booked' : 'Available'}`;

              // Add both user names
              details += `\nScheduled by: ${Number(props.created_by) === userId ? 'You' : (props.tutor_id == props.created_by ? props.tutor_name : props.student_name)}`;
              details += `\nTutor: ${props.tutor_name || 'N/A'}`;
              details += `\nStudent: ${props.student_name || 'N/A'}`;

              alert(details);

              // --- Existing logic for tutor and student actions below ---
              // Tutor: Edit or Delete their own slots
              if (userRole === 'tutor' && Number(props.tutor_id) === userId) {
                  const action = prompt(
                      `Edit session name or type "delete" to remove:\nCurrent: ${eventObj.title.replace(/^(Available: |Booked: )/, '')}`,
                      eventObj.title.replace(/^(Available: |Booked: )/, '')
                  );
                  if (action === null) return;
                  if (action.toLowerCase() === 'delete') {
                      if (confirm('Delete this slot?')) {
                          fetch('calendar_api.php', {
                              method: 'POST',
                              headers: { 'Content-Type': 'application/json' },
                              body: JSON.stringify({ action: 'delete', id: eventObj.id })
                          })
                          .then(res => res.json())
                          .then(data => {
                              if (data.success) {
                                  window.calendar.refetchEvents();
                                  showCalendarNotify(false, "Schedule deleted successfully!");
                              } else {
                                  alert(data.message || 'Failed to delete');
                              }
                          });
                      }
                  } else if (action !== eventObj.title.replace(/^(Available: |Booked: )/, '')) {
                      const location = prompt('Edit location/mode:', props.location_mode);
                      const from = prompt('Edit start time (HH:MM, 24h):', eventObj.start.toISOString().substr(11,5));
                      const to = prompt('Edit end time (HH:MM, 24h):', eventObj.end ? eventObj.end.toISOString().substr(11,5) : '');
                      fetch('calendar_api.php', {
                          method: 'POST',
                          headers: { 'Content-Type': 'application/json' },
                          body: JSON.stringify({
                              action: 'update',
                              id: eventObj.id,
                              day: action,
                              session_date: eventObj.start.toISOString().split('T')[0],
                              available_from: from + ':00',
                              available_to: to + ':00',
                              location_mode: location
                          })
                      })
                      .then(res => res.json())
                      .then(data => {
                          if (data.success) window.calendar.refetchEvents();
                          else alert(data.message || 'Failed to update');
                      });
                  }
              }
              // Student: Book or Cancel booking
               else if (userRole === 'student') {
                  if (!props.is_booked || props.student_id == "<?php echo $_SESSION['user_id'] ?? 0; ?>") {
                      if (!props.is_booked) {
                          if (confirm('Book this session?')) {
                              fetch('calendar_api.php', {
                                  method: 'POST',
                                  headers: { 'Content-Type': 'application/json' },
                                  body: JSON.stringify({ action: 'book', id: eventObj.id })
                              })
                              .then(res => res.json())
                              .then(data => {
                                  if (data.success) window.calendar.refetchEvents();
                                  else alert(data.message || 'Failed to book');
                              });
                          }
                       } else if (Number(props.student_id) === userId) {
                          if (confirm('Cancel your booking?')) {
                              fetch('calendar_api.php', {
                                  method: 'POST',
                                  headers: { 'Content-Type': 'application/json' },
                                  body: JSON.stringify({ action: 'cancel', id: eventObj.id })
                              })
                              .then(res => res.json())
                              .then(data => {
                                  if (data.success) window.calendar.refetchEvents();
                                  else alert(data.message || 'Failed to cancel');
                              });
                          }
                      }
                  }
              }
          },

          // --- TUTOR: DRAG & DROP TO UPDATE DATE/TIME ---
          eventDrop: function(info) {
              if (userRole !== 'tutor') return;
              fetch('calendar_api.php', {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify({
                      action: 'update',
                      id: info.event.id,
                      day: info.event.title.replace(/^(Available: |Booked: )/, ''),
                      session_date: info.event.start.toISOString().split('T')[0],
                      available_from: info.event.start.toISOString().substr(11,8),
                      available_to: info.event.end ? info.event.end.toISOString().substr(11,8) : null,
                      location_mode: info.event.extendedProps.location_mode
                  })
              })
              .then(res => res.json())
              .then(data => {
                  if (data.success) window.calendar.refetchEvents();
                  else alert(data.message || 'Failed to update');
              });
          }
      });

      // --- Render the calendar on the page ---
      window.calendar.render();
  });

  // Show notification modal
  function showCalendarNotify(success, message) {
    const modal = document.getElementById('calendarNotifyModal');
    const title = document.getElementById('calendarNotifyTitle');
    const msg = document.getElementById('calendarNotifyMsg');
    title.textContent = success ? "Success" : "Deleted";
    msg.textContent = message;
    modal.style.display = 'flex';
    setTimeout(() => {
      modal.style.display = 'none';
    }, 1800);
  }

  // Show notification based on URL parameter
  window.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    if (params.get('calendar') === 'created') {
      showCalendarNotify(true, "Schedule created successfully!");
    } else if (params.get('calendar') === 'deleted') {
      showCalendarNotify(false, "Schedule deleted successfully!");
    }
  });

  // Modal event handlers
  document.addEventListener('DOMContentLoaded', function() {
    // Create button event handler
    document.getElementById('createBtn').addEventListener('click', function() {
      const userSelect = document.getElementById('userSelect');
      const sessionName = document.getElementById('sessionName');
      const locationMode = document.getElementById('locationMode');
      const startTime = document.getElementById('startTime');
      const endTime = document.getElementById('endTime');
      
      const [otherUserId, otherUserRole] = userSelect.value.split('|');
      const day = sessionName.value;
      const location = locationMode.value;
      const from = startTime.value;
      const to = endTime.value;
      
      if (otherUserId && otherUserRole && day && from && to && location) {
        fetch('calendar_api.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            action: 'create',
            other_user_id: otherUserId,
            other_user_role: otherUserRole,
            day: day,
            session_date: window.selectedDate,
            available_from: from + ':00',
            available_to: to + ':00',
            location_mode: location
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            // Refresh calendar events
            if (window.calendar) {
              window.calendar.refetchEvents();
            }
            showCalendarNotify(true, "Schedule created successfully!");
            closeEventModal();
          } else {
            alert(data.message || 'Failed to create slot');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while creating the schedule.');
        });
      } else {
        alert('Please fill all fields.');
      }
    });

    // Close modal when clicking outside
    document.getElementById('eventCreationModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeEventModal();
      }
    });
  });

  // Close event modal function
  function closeEventModal() {
    const modal = document.getElementById('eventCreationModal');
    modal.style.display = 'none';
    
    // Clear form fields
    document.getElementById('userSelect').value = '';
    document.getElementById('sessionName').value = '';
    document.getElementById('locationMode').value = '';
    document.getElementById('startTime').value = '08:00';
    document.getElementById('endTime').value = '09:00';
  }