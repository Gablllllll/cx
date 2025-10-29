// Admin Feedback Management JavaScript

// View feedback details in modal
function viewFeedback(feedbackId) {
	const modal = document.getElementById('viewModal');
	const modalContent = document.getElementById('modalContent');
	
	// Find the feedback row
	const row = document.querySelector(`tr[data-feedback-id="${feedbackId}"]`);
	if (!row) return;
	
	// Extract data from the row
	const studentInfo = row.querySelector('.student-name').textContent.trim();
	const studentUsername = row.querySelector('.student-username').textContent.trim();
	const material = row.querySelector('.material-info').textContent.trim();
	const rating = row.querySelectorAll('.rating-stars .star.filled').length;
	const commentElement = row.querySelector('.comment-text');
	const comment = commentElement ? commentElement.textContent.trim() : 'No comment provided';
	const date = row.querySelector('.date-cell').textContent.trim();
	const time = row.querySelector('.time-cell').textContent.trim();
	
	// Build modal content
	let modalHTML = `
		<div class="modal-detail-row">
			<div class="modal-detail-label">Student</div>
			<div class="modal-detail-value">
				<strong>${studentInfo}</strong><br>
				<span style="color: #6b7280;">${studentUsername}</span>
			</div>
		</div>
		
		<div class="modal-detail-row">
			<div class="modal-detail-label">Learning Material</div>
			<div class="modal-detail-value">${material}</div>
		</div>
		
		<div class="modal-detail-row">
			<div class="modal-detail-label">Rating</div>
			<div class="modal-detail-value">
				<div class="modal-rating">
	`;
	
	for (let i = 1; i <= 5; i++) {
		modalHTML += `<span class="star">${i <= rating ? '★' : '☆'}</span>`;
	}
	
	modalHTML += `
					<span style="color: #6b7280; margin-left: 0.5rem;">(${rating} out of 5)</span>
				</div>
			</div>
		</div>
		
		<div class="modal-detail-row">
			<div class="modal-detail-label">Comment</div>
			<div class="modal-detail-value modal-comment">${comment}</div>
		</div>
		
		<div class="modal-detail-row">
			<div class="modal-detail-label">Submitted</div>
			<div class="modal-detail-value">${date} at ${time}</div>
		</div>
	`;
	
	modalContent.innerHTML = modalHTML;
	modal.classList.add('active');
}

// Close modal
function closeModal() {
	const modal = document.getElementById('viewModal');
	modal.classList.remove('active');
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
	const modal = document.getElementById('viewModal');
	
	modal.addEventListener('click', function(e) {
		if (e.target === modal) {
			closeModal();
		}
	});
	
	// Close modal on Escape key
	document.addEventListener('keydown', function(e) {
		if (e.key === 'Escape') {
			closeModal();
		}
	});
});

// Delete feedback
function deleteFeedback(feedbackId) {
	if (!confirm('Are you sure you want to delete this feedback? This action cannot be undone.')) {
		return;
	}
	
	// Send delete request
	fetch('delete_feedback.php', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: `feedback_id=${feedbackId}`
	})
	.then(response => response.json())
	.then(data => {
		if (data.success) {
			// Remove the row from the table with animation
			const row = document.querySelector(`tr[data-feedback-id="${feedbackId}"]`);
			if (row) {
				row.style.transition = 'all 0.3s ease';
				row.style.opacity = '0';
				row.style.transform = 'translateX(-20px)';
				
				setTimeout(() => {
					row.remove();
					
					// Check if table is now empty
					const tbody = document.querySelector('.feedback-table tbody');
					if (tbody && tbody.children.length === 0) {
						location.reload(); // Reload to show empty state
					}
				}, 300);
			}
			
			// Show success message
			showNotification('Feedback deleted successfully', 'success');
		} else {
			showNotification('Failed to delete feedback: ' + data.message, 'error');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		showNotification('An error occurred while deleting feedback', 'error');
	});
}

// Show notification
function showNotification(message, type = 'info') {
	// Create notification element
	const notification = document.createElement('div');
	notification.className = `notification notification-${type}`;
	notification.textContent = message;
	notification.style.cssText = `
		position: fixed;
		top: 100px;
		right: 20px;
		padding: 1rem 1.5rem;
		background: ${type === 'success' ? '#48bb78' : '#ef4444'};
		color: white;
		border-radius: 10px;
		font-weight: 600;
		box-shadow: 0 10px 30px rgba(0,0,0,0.2);
		z-index: 10001;
		animation: slideInRight 0.3s ease-out;
	`;
	
	document.body.appendChild(notification);
	
	// Remove after 3 seconds
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

// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
	const ratingSelect = document.getElementById('rating');
	const materialSelect = document.getElementById('material');
	const form = document.getElementById('filterForm');
	
	if (ratingSelect && materialSelect && form) {
		ratingSelect.addEventListener('change', function() {
			form.submit();
		});
		
		materialSelect.addEventListener('change', function() {
			form.submit();
		});
	}
});

