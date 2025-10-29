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
    <link rel="stylesheet" href="tutormodule.css">
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
        <ul>
            <li><a href="tutorlanding.php"><img src="Images/home-svgrepo-com.svg" alt="Home Icon"> Home</a></li>
            <li><a href="tutorcalendar.php"><img src="Images/calendar-month-svgrepo-com.svg" alt="Calendar Icon"> Calendar</a></li>
            <li><a href="tutormodule.php"><img src="Images/book-svgrepo-com.svg" alt="Modules Icon"> Modules</a></li>
            <li><a href="studentlist.php"><img src="Images/user-svgrepo-com.svg" alt="Tutors Icon"> Students</a></li>

            <li>
                <a href="#" class="dropdown-toggle">Here</a>
                <ul class="dropdown-menu">
                    <li><a href="#"><img src="Images/idea-svgrepo-com.svg" alt="Features Icon">Features</a></li>
                    <li><a href="#"><img src="Images/about-filled-svgrepo-com.svg" alt="About-Us Icon">About Us</a></li>
                    <li><a href="#"><img src="Images/settings-2-svgrepo-com.svg" alt="Settings Icon"> Settings</a></li>
                </ul>
            <li>
        </ul>
    </div>


    <h2>Hello Tutor</h2>
    <div style="text-align:center; margin-bottom: 24px;">
        <button id="open-upload-box" class="upload-module-btn">Upload Module</button>
    </div>

    <!-- Upload Box Modal -->
    <div id="upload-box-modal" class="upload-box-modal">
      <div class="upload-box-content">
        <h3 style="text-align:left; margin-top:0;">Good Day Tutor!</h3>
        <p style="font-size:1rem; margin-bottom:18px; margin-top:0;">
          Note: Use clear filenames (e.g., Lesson1_IntroToComputer.pdf) for easier tracking.
        </p>
        <form action="upload_material.php" method="POST" enctype="multipart/form-data">
          <div class="upload-form-row">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" placeholder="Ex: Introduction to computer">
          </div>
          <div class="upload-form-row">
            <label for="description">Description</label>
            <input type="text" id="description" name="description" placeholder="Please kindly apply to this course and learn about computer">
          </div>
          <div class="upload-form-row" style="justify-content:center;">
            <label for="file" class="custom-file-upload">
              <img src="Images/upload.png" alt="Upload Icon" style="width:200px; height:180px; display:block; margin:auto;">
              <div style="text-align:center; margin-top:8px;">Upload File (PDF)</div>
            </label>
            <input id="file" type="file" name="file" accept=".pdf" style="display:none;" require> 
          </div>
          <div style="text-align:center;">
            <button type="submit" class="upload-submit-btn">Upload</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Upload Result Modal -->
    <div id="uploadResultModal" class="modal" style="display:none;">
      <div class="modal-content upload-result-modal-content">
        <span id="close-upload-result-modal" class="close-upload-result-modal">&times;</span>
        <div class="upload-checkmark">
          <svg width="120" height="120" viewBox="0 0 120 120">
            <circle cx="60" cy="60" r="54" fill="none" stroke="#2ecc40" stroke-width="4"/>
            <polyline points="40,65 55,80 80,45" fill="none" stroke="#2ecc40" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <h2 id="uploadResultTitle" class="upload-result-title"></h2>
        <p id="uploadResultMsg" class="upload-result-message"></p>
      </div>
    </div>

    <!-- Module List Container -->
    <?php
    $query = "SELECT material_id, title, description, uploaded_by, approved_at, file_url FROM learning_materials WHERE is_approved = 1 AND uploaded_by = ? ORDER BY approved_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $_SESSION['first_name']);
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) > 0) {
        echo '<div class="module-list">';
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="module-item">';
            
            // Module Header
            echo '<div class="module-header">';
            echo '  <div class="module-user">';
            echo '      <img src="Images/user-svgrepo-com.svg" alt="User Icon" class="module-user-icon">';
            echo '      <span class="module-user-name">' . htmlspecialchars($row['uploaded_by']) . '</span>';
            echo '      <img src="Images/verified.png" alt="Verified" class="module-verified-icon">';
            echo '  </div>';
            echo '  <div class="module-date">' . date("F j, Y", strtotime($row['approved_at'])) . '</div>';
            echo '</div>';
            
            // Module Body
            echo '<div class="module-body">';
            echo '  <div class="module-title-desc">';
            echo      '<span class="module-title">' . htmlspecialchars($row['title']) . '</span>';
            echo      '<span class="module-desc"> - ' . htmlspecialchars($row['description']) . '</span>';
            echo '  </div>';
            echo '  <div class="module-actions">';
            echo '      <a href="modules.php?file_url=' . urlencode($row['file_url']) . '" class="module-edit">View</a>';
            echo '      <a href="delete_module.php?id=' . $row['material_id'] . '" class="module-delete" onclick="return confirm(\'Are you sure you want to delete this?\');">Delete</a>';
            echo '  </div>';
            echo '</div>';

            echo '</div>'; // .module-item
        }

        echo '</div>'; // .module-list
    } else {
        echo '<p>No materials found.</p>';
    }
    ?>

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

    // Upload box modal
    document.getElementById('open-upload-box').addEventListener('click', function() {
      document.getElementById('upload-box-modal').style.display = 'flex';
    });

    // Close modal when clicking outside of the modal content
    window.addEventListener('click', function(event) {
      const modal = document.getElementById('upload-box-modal');
      if (event.target == modal) {
        modal.style.display = 'none';
      }
    });

    // Show upload result modal
    function showUploadResultModal(success, message) {
        const modal = document.getElementById('uploadResultModal');
        const title = document.getElementById('uploadResultTitle');
        const msg = document.getElementById('uploadResultMsg');
        title.textContent = success ? "Upload Successful" : "Upload Failed";
        msg.textContent = message;
        modal.style.display = 'flex';
    }

    // Close modal on X click
    document.getElementById('close-upload-result-modal').onclick = function() {
        document.getElementById('uploadResultModal').style.display = 'none';
    };
    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('uploadResultModal');
        if (e.target === modal) modal.style.display = 'none';
    });

    // Check URL for upload result
    window.addEventListener('DOMContentLoaded', function() {
        const params = new URLSearchParams(window.location.search);
        if (params.get('upload') === 'success') {
            showUploadResultModal(true, "Document uploaded successfully!");
        } else if (params.get('upload') === 'fail') {
            showUploadResultModal(false, "There was an error uploading your file.");
        } else if (params.get('upload') === 'deleted') {
            showUploadResultModal(true, "Document deleted successfully!");
            document.getElementById('uploadResultTitle').textContent = "Delete Successful";
            document.querySelector('.upload-checkmark svg circle').setAttribute('stroke', '#ff5252'); // red
            document.querySelector('.upload-checkmark svg polyline').setAttribute('stroke', '#ff5252');
        }
    });
  </script>
</body>
</html>