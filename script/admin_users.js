// Admin Users Management JavaScript

// View user details
function viewUser(userId) {
	const card = document.querySelector(`.user-card[data-user-id="${userId}"]`);
	if (!card) return;
	
	// Extract user data from card
	const name = card.querySelector('.user-card-name').textContent.trim();
	const username = card.querySelector('.user-card-username').textContent.trim();
	const badges = card.querySelectorAll('.badge');
	const infoItems = card.querySelectorAll('.info-item span');
	
	let email = '';
	let contact = '';
	let joined = '';
	
	infoItems.forEach(item => {
		const text = item.textContent.trim();
		if (text.includes('@')) email = text;
		else if (text.includes('Joined')) joined = text;
		else if (contact === '') contact = text;
	});
	
	let role = '';
	badges.forEach(badge => {
		if (badge.classList.contains('badge-student') || badge.classList.contains('badge-admin')) {
			role = badge.textContent.trim();
		}
	});
	
	// Build modal content
	const modalContent = `
		<div class="modal-user-header">
			<div class="modal-user-avatar">
				<img src="Images/user-svgrepo-com.svg" alt="User">
			</div>
			<h2 class="modal-user-name">${name}</h2>
			<p class="modal-user-username">${username}</p>
			<div class="modal-badges">
				<span class="badge badge-${role.toLowerCase()}">${role}</span>
			</div>
		</div>
		
		<div class="modal-detail-grid">
			<div class="modal-detail-item">
				<div class="modal-detail-label">Email</div>
				<div class="modal-detail-value">${email}</div>
			</div>
			${contact && !contact.includes('Joined') ? `
			<div class="modal-detail-item">
				<div class="modal-detail-label">Contact Number</div>
				<div class="modal-detail-value">${contact}</div>
			</div>
			` : ''}
			<div class="modal-detail-item full-width">
				<div class="modal-detail-label">Member Since</div>
				<div class="modal-detail-value">${joined}</div>
			</div>
		</div>
	`;
	
	document.getElementById('viewModalContent').innerHTML = modalContent;
	document.getElementById('viewModal').classList.add('active');
}

// Edit user
function editUser(userId) {
	// Fetch user data via AJAX
	fetch(`get_user.php?user_id=${userId}`)
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				const user = data.user;
				
				// Populate form
				document.getElementById('edit_user_id').value = user.user_id;
				document.getElementById('edit_first_name').value = user.first_name || '';
				document.getElementById('edit_last_name').value = user.last_name || '';
				document.getElementById('edit_email').value = user.email || '';
				document.getElementById('edit_role').value = user.role || 'student';
				document.getElementById('edit_contact').value = user.contact_number || '';
				document.getElementById('edit_address').value = user.address || '';
				
				// Open modal
				document.getElementById('editModal').classList.add('active');
			} else {
				showNotification('Failed to load user data', 'error');
			}
		})
		.catch(error => {
			console.error('Error:', error);
			showNotification('An error occurred while loading user data', 'error');
		});
}

// Submit edit user form
function submitEditUser(event) {
	event.preventDefault();
	
	const form = document.getElementById('editUserForm');
	const formData = new FormData(form);
	
	fetch('update_user.php', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (data.success) {
			showNotification('User updated successfully!', 'success');
			closeModal('editModal');
			
			// Reload page after short delay
			setTimeout(() => {
				location.reload();
			}, 1000);
		} else {
			showNotification('Failed to update user: ' + data.message, 'error');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		showNotification('An error occurred while updating user', 'error');
	});
}

// Delete user
function deleteUser(userId) {
	if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
		return;
	}
	
	fetch('delete_user.php', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: `user_id=${userId}`
	})
	.then(response => response.json())
	.then(data => {
		if (data.success) {
			showNotification('User deleted successfully', 'success');
			
			// Remove the card with animation
			const card = document.querySelector(`.user-card[data-user-id="${userId}"]`);
			if (card) {
				card.style.transition = 'all 0.3s ease';
				card.style.opacity = '0';
				card.style.transform = 'scale(0.9)';
				
				setTimeout(() => {
					card.remove();
					
					// Check if grid is now empty
					const grid = document.querySelector('.users-grid');
					if (grid && grid.children.length === 0) {
						location.reload(); // Reload to show empty state
					}
				}, 300);
			}
		} else {
			showNotification('Failed to delete user: ' + data.message, 'error');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		showNotification('An error occurred while deleting user', 'error');
	});
}

// Close modal
function closeModal(modalId) {
	const modal = document.getElementById(modalId);
	modal.classList.remove('active');
}

// Show notification
function showNotification(message, type = 'info') {
	const notification = document.createElement('div');
	notification.className = `notification notification-${type}`;
	notification.textContent = message;
	notification.style.cssText = `
		position: fixed;
		top: 100px;
		right: 20px;
		padding: 1rem 1.5rem;
		background: ${type === 'success' ? '#48bb78' : type === 'error' ? '#ef4444' : '#3b82f6'};
		color: white;
		border-radius: 10px;
		font-weight: 600;
		box-shadow: 0 10px 30px rgba(0,0,0,0.2);
		z-index: 10001;
		animation: slideInRight 0.3s ease-out;
	`;
	
	document.body.appendChild(notification);
	
	setTimeout(() => {
		notification.style.animation = 'slideOutRight 0.3s ease-out';
		setTimeout(() => {
			notification.remove();
		}, 300);
	}, 3000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
	@keyframes slideInRight {
		from {
			transform: translateX(100%);
			opacity: 0;
		}
		to {
			transform: translateX(0);
			opacity: 1;
		}
	}
	
	@keyframes slideOutRight {
		from {
			transform: translateX(0);
			opacity: 1;
		}
		to {
			transform: translateX(100%);
			opacity: 0;
		}
	}
`;
document.head.appendChild(style);

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
	// Close modals when clicking outside
	const modals = document.querySelectorAll('.modal-overlay');
	modals.forEach(modal => {
		modal.addEventListener('click', function(e) {
			if (e.target === modal) {
				closeModal(modal.id);
			}
		});
	});
	
	// Close modals on Escape key
	document.addEventListener('keydown', function(e) {
		if (e.key === 'Escape') {
			modals.forEach(modal => {
				if (modal.classList.contains('active')) {
					closeModal(modal.id);
				}
			});
		}
	});
	
	// Auto-submit filters on change
	const roleSelect = document.getElementById('role');
	const form = document.getElementById('filterForm');
	
	if (roleSelect && form) {
		roleSelect.addEventListener('change', function() {
			form.submit();
		});
	}
});


