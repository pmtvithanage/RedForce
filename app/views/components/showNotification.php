<style>
/* Notification Messages */
.notification-container {
  position: fixed;
  top: 80px;
  right: 20px;
  z-index: 9999;
  display: flex;
  flex-direction: column;
  gap: 10px;
  max-width: 400px;
}

.notification {
  padding: 16px 20px;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  display: flex;
  align-items: center;
  gap: 12px;
  animation: slideIn 0.3s ease-out;
  transition: all 0.3s ease;
}

.notification.success {
  background: #10b981;
  color: white;
}

.notification.error {
  background: #ef4444;
  color: white;
}

.notification.warning {
  background: #f59e0b;
  color: white;
}

.notification.info {
  background: #3b82f6;
  color: white;
}

.notification .notification-icon {
  font-size: 24px;
  flex-shrink: 0;
}

.notification .notification-message {
  flex: 1;
  font-size: 14px;
  font-weight: 500;
}

.notification .notification-close {
  background: none;
  border: none;
  color: white;
  font-size: 20px;
  cursor: pointer;
  padding: 0;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0.8;
  transition: opacity 0.2s;
}

.notification .notification-close:hover {
  opacity: 1;
}

@keyframes slideIn {
  from {
    transform: translateX(400px);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes slideOut {
  from {
    transform: translateX(0);
    opacity: 1;
  }
  to {
    transform: translateX(400px);
    opacity: 0;
  }
}

.notification.hiding {
  animation: slideOut 0.3s ease-out forwards;
}
</style>

<!-- Notification Container -->
<div class="notification-container" id="notificationContainer"></div>

<script>
// Notification System
function showNotification(message, type = 'info') {
  const container = document.getElementById('notificationContainer');
  if (!container) return;
  
  const notification = document.createElement('div');
  notification.className = `notification ${type}`;
  
  const iconMap = {
    success: 'check_circle',
    error: 'error',
    warning: 'warning',
    info: 'info'
  };
  
  notification.innerHTML = `
    <span class="material-symbols-outlined notification-icon">${iconMap[type]}</span>
    <div class="notification-message">${message}</div>
    <button class="notification-close">
      <span class="material-symbols-outlined">close</span>
    </button>
  `;
  
  container.appendChild(notification);
  
  // Add click event to close button
  const closeBtn = notification.querySelector('.notification-close');
  closeBtn.addEventListener('click', function() {
    notification.classList.add('hiding');
    setTimeout(() => {
      notification.remove();
    }, 300);
  });
  
  // Auto-remove after 5 seconds
  setTimeout(() => {
    if (notification && notification.parentElement) {
      notification.classList.add('hiding');
      setTimeout(() => {
        notification.remove();
      }, 300);
    }
  }, 5000);
}

function closeNotification(button) {
  const notification = button.closest('.notification');
  if (!notification) return;
  
  notification.classList.add('hiding');
  setTimeout(() => {
    notification.remove();
  }, 300);
}
</script>
