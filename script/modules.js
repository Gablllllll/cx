function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const body = document.body;
    
    // Toggle the hidden class
    sidebar.classList.toggle('hidden');
    
    // Add/remove class on body to control content positioning
    if (sidebar.classList.contains('hidden')) {
        body.classList.add('sidebar-hidden');
    } else {
        body.classList.remove('sidebar-hidden');
    }
}

// Wait for DOM to be ready before adding event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar click outside to close
    document.addEventListener('click', function (event) {
        const sidebar = document.getElementById('sidebar');
        const burgerMenu = document.querySelector('.burger-menu');

        // Close sidebar if clicked outside
        if (sidebar && burgerMenu && !sidebar.contains(event.target) && !burgerMenu.contains(event.target)) {
            sidebar.classList.add('hidden');
            document.body.classList.add('sidebar-hidden');
        }
    });
    document.querySelectorAll('.dropdown-toggle').forEach(item => {
        item.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            const dropdownMenu = this.nextElementSibling;
            dropdownMenu.classList.toggle('active');
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        const dropdowns = document.querySelectorAll('.dropdown-menu');
        dropdowns.forEach(dropdown => {
            if (!dropdown.previousElementSibling.contains(event.target) && dropdown.classList.contains('active')) {
                dropdown.classList.remove('active');
            }
        });
    });

    // Feature box functionality
    const boxes = document.querySelectorAll('.feature-box');
    const modal = document.getElementById('modal');
    const modalTitle = document.getElementById('modal-title');
    const modalText = document.getElementById('modal-text');
    const modalImg = document.getElementById('modal-img');
    const closeModal = document.getElementById('close-modal');

    if (boxes.length > 0 && modal && modalTitle && modalText && modalImg && closeModal) {
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
    }

    // Calendar functionality
    let currentDate = new Date();
    let selectedDate = null;

    function updateCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        // Update month/year display
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        document.getElementById('currentMonthYear').textContent = 
            `${monthNames[month]} ${year}`;

        // Get first day of month and number of days
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay();

        // Clear calendar grid
        const calendarGrid = document.getElementById('calendarGrid');
        calendarGrid.innerHTML = '';

        // Add day headers
        const dayHeaders = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
        dayHeaders.forEach(day => {
            const dayHeader = document.createElement('div');
            dayHeader.className = 'calendar-day-header';
            dayHeader.textContent = day;
            calendarGrid.appendChild(dayHeader);
        });

        // Add empty cells for days before the first day of the month
        for (let i = 0; i < startingDayOfWeek; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'calendar-day other-month';
            emptyDay.textContent = '';
            calendarGrid.appendChild(emptyDay);
        }

        // Add days of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';
            dayElement.textContent = day;

            // Check if it's today
            const today = new Date();
            if (year === today.getFullYear() && month === today.getMonth() && day === today.getDate()) {
                dayElement.classList.add('today');
            }

            // Check if it's selected
            if (selectedDate && 
                year === selectedDate.getFullYear() && 
                month === selectedDate.getMonth() && 
                day === selectedDate.getDate()) {
                dayElement.classList.add('selected');
            }

            // Add click event
            dayElement.addEventListener('click', () => {
                // Remove previous selection
                document.querySelectorAll('.calendar-day.selected').forEach(el => {
                    el.classList.remove('selected');
                });
                
                // Add selection to clicked day
                dayElement.classList.add('selected');
                selectedDate = new Date(year, month, day);
                
                // Optional: You can add functionality here for when a date is selected
                console.log('Selected date:', selectedDate.toDateString());
            });

            calendarGrid.appendChild(dayElement);
        }
    }

    // Navigation event listeners
    document.getElementById('prevMonth').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        updateCalendar();
    });

    document.getElementById('nextMonth').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        updateCalendar();
    });

    // Initialize calendar
    updateCalendar();
});
