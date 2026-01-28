<!-- Messages Component - Reusable messaging interface for all roles -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/components/messages.css">

<div class="messages-container">
  <!-- Left: Chat list -->
  <div class="chat-list-panel">
    <input type="text" placeholder="Search conversations..." class="search" id="searchInput">

    <div class="chat-list" id="chatList">
      <?php
        // Build list of conversation user ids
        $convIds = [];
        if (!empty($data['conversations'])) {
            foreach ($data['conversations'] as $conv) {
                $convIds[] = $conv->id;
            }
        }
      ?>

      <!-- Existing Conversations -->
      <?php if (!empty($data['conversations'])): ?>
        <?php foreach ($data['conversations'] as $conv): ?>
          <div class="chat-item" data-user-id="<?php echo $conv->id; ?>" onclick="selectConversation(<?php echo $conv->id; ?>, event)">
            <div class="avatar">
              <?php if (!empty($conv->profile_image)): ?>
                <?php 
                  $imagePath = (strtolower($conv->role) === 'client') 
                    ? URL_ROOT . '/uploads/clientLogos/' . $conv->profile_image 
                    : URL_ROOT . '/uploads/applicantPhotos/' . $conv->profile_image;
                ?>
                <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($conv->name); ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
              <?php else: ?>
                <span class="material-icons">person</span>
              <?php endif; ?>
            </div>
            <div class="chat-info">
              <p class="name">
                <?php echo htmlspecialchars($conv->name); ?>
               
              </p>
              <p class="preview"><?php echo htmlspecialchars(substr($conv->last_message ?? 'No messages yet', 0, 50)); ?></p>
            </div>
            <div class="chat-meta">
              <span class="time"><?php echo date('M d', strtotime($conv->last_message_time ?? 'now')); ?></span>
              <?php if (isset($conv->unread_count) && $conv->unread_count > 0): ?>
                <span class="unread-badge"><?php echo $conv->unread_count; ?></span>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

      <!-- Start new conversation (users without existing conversations) -->
      <?php
        $startableUsers = [];
        if (!empty($data['all_users'])) {
            foreach ($data['all_users'] as $user) {
                if (!in_array($user->id, $convIds)) {
                    $startableUsers[] = $user;
                }
            }
        }
      ?>

      <?php if (!empty($startableUsers)): ?>
        <p style="padding: 10px 15px; font-size: 12px; color: #999; border-top: 1px solid #f0f0f0; margin-top: 10px;">Start a new conversation</p>
        <?php foreach ($startableUsers as $user): ?>
          <div class="chat-item" data-user-id="<?php echo $user->id; ?>" onclick="selectConversation(<?php echo $user->id; ?>, event)">
            <div class="avatar">
              <?php if (!empty($user->profile_image)): ?>
                <?php 
                  $imagePath = (strtolower($user->role) === 'client') 
                    ? URL_ROOT . '/uploads/clientLogos/' . $user->profile_image 
                    : URL_ROOT . '/uploads/applicantPhotos/' . $user->profile_image;
                ?>
                <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($user->name); ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
              <?php else: ?>
                <span class="material-icons">person</span>
              <?php endif; ?>
            </div>
            <div class="chat-info">
              <p class="name">
                <?php echo htmlspecialchars($user->name); ?>
    
              </p>
              <p class="preview">Click to start conversation</p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php elseif (empty($data['conversations'])): ?>
        <p style="padding: 20px; text-align: center; color: #999;">No users available</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Right: Chat messages -->
  <div class="chat-window" id="chatWindow" style="display: none;">
    <div class="chat-header">
      <div class="chat-header-info">
        <div class="avatar" id="recipientAvatar" style="width: 40px; height: 40px; margin-right: 10px;">
          <span class="material-icons">person</span>
        </div>
        <div style="display: flex; flex-direction: column;">
          <span id="recipientName" style="font-weight: 600;">Select a conversation</span>
          <span id="recipientStatus" style="font-size: 12px; color: #28a745; font-weight: 300;"></span>
        </div>
      </div>
    </div>

    <div class="messages" id="messagesContainer"></div>

    <div class="chat-input">
      <input type="text" placeholder="Type a message" id="messageInput" onkeypress="handleKeyPress(event)">
      <button class="sent-btn" onclick="sendMessage()">
        <span class="material-icons">send</span>
      </button>
    </div>
  </div>

  <!-- No selection view -->
  <div class="no-selection" id="noSelection" style="display: flex; align-items: center; justify-content: center; flex: 1;">
    <div style="text-align: center; color: #999;">
      <span class="material-icons" style="font-size: 60px;">chat</span>
      <p>Select a conversation to start messaging</p>
    </div>
  </div>
  
  <!-- Delete confirmation modal -->
  <div class="delete-modal-overlay" id="deleteModalOverlay" style="display: none;">
    <div class="delete-modal">
      <div class="delete-modal-icon">
        <span class="material-icons">warning</span>
      </div>
      <h3>Delete Message</h3>
      <p>Are you sure you want to delete this message? This action cannot be undone.</p>
      <div class="delete-modal-actions">
        <button class="delete-modal-btn cancel" onclick="closeDeleteModal()">Cancel</button>
        <button class="delete-modal-btn confirm" onclick="confirmDeleteMessage()">Delete</button>
      </div>
    </div>
  </div>
</div>

<script>
let currentRecipientId = null;
let currentRecipientName = null;
let urlRoot = '<?php echo URL_ROOT; ?>';
let messageRefreshInterval;
// Convert role to controller name (remove spaces, capitalize each word)
let userRole = '<?php 
  $role = $_SESSION['user_role'] ?? 'user';
  // Convert "mobile rider" to "MobileRider", "premise officer" to "PremiseOfficer", etc.
  $role = str_replace(' ', '', ucwords($role));
  echo $role;
?>';

// Select conversation
function selectConversation(userId, event) {
  if (event) {
    event.preventDefault();
  }
  
  currentRecipientId = userId;
  
  // Get recipient info from chat item
  const chatItem = document.querySelector(`.chat-item[data-user-id="${userId}"]`);
  if (chatItem) {
    const nameElement = chatItem.querySelector('.name');
    const roleElement = chatItem.querySelector('.role');
    currentRecipientName = nameElement ? nameElement.textContent.trim().split('\n')[0] : 'User';
    const currentRecipientRole = roleElement ? roleElement.textContent.trim() : '';
    
    // Get avatar/image
    const avatarDiv = chatItem.querySelector('.avatar');
    const recipientAvatar = document.getElementById('recipientAvatar');
    
    if (avatarDiv) {
      const img = avatarDiv.querySelector('img');
      if (img) {
        // User has profile image
        recipientAvatar.innerHTML = '<img src="' + img.src + '" alt="' + currentRecipientName + '" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">';
      } else {
        // Use default icon
        recipientAvatar.innerHTML = '<span class="material-icons">person</span>';
      }
    }
    
    // Update status (online check)
    checkUserOnlineStatus(userId);
  }
  
  // Update UI
  document.getElementById('recipientName').textContent = currentRecipientName;
  document.getElementById('chatWindow').style.display = 'flex';
  document.getElementById('noSelection').style.display = 'none';
  
  // Highlight selected conversation
  document.querySelectorAll('.chat-item').forEach(item => {
    item.classList.remove('active');
  });
  if (chatItem) {
    chatItem.classList.add('active');
  }
  
  // Load messages
  loadMessages();
  
  // Auto-refresh messages every 3 seconds
  if (messageRefreshInterval) {
    clearInterval(messageRefreshInterval);
  }
  messageRefreshInterval = setInterval(function() {
    if (currentRecipientId) {
      loadMessages();
      checkUserOnlineStatus(currentRecipientId);
    }
  }, 3000);
  
  // Also refresh conversation list
  refreshConversationList();
}

// Refresh conversation list
function refreshConversationList() {
  fetch(urlRoot + '/' + userRole + '/getConversations', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 'success') {
      updateConversationList(data.conversations);
    }
  })
  .catch(error => console.error('Error refreshing conversations:', error));
}

// Update conversation list without losing selection
function updateConversationList(conversations) {
  const chatList = document.getElementById('chatList');
  const existingItems = chatList.querySelectorAll('.chat-item');
  
  conversations.forEach(conv => {
    const existingItem = Array.from(existingItems).find(item => item.dataset.userId == conv.id);
    
    if (existingItem) {
      // Update existing item
      const preview = existingItem.querySelector('.preview');
      const time = existingItem.querySelector('.time');
      const unreadBadge = existingItem.querySelector('.unread-badge');
      
      if (preview) preview.textContent = (conv.last_message || 'No messages yet').substring(0, 50);
      if (time) time.textContent = new Date(conv.last_message_time || Date.now()).toLocaleDateString('en-US', {month: 'short', day: 'numeric'});
      
      if (conv.unread_count > 0) {
        if (unreadBadge) {
          unreadBadge.textContent = conv.unread_count;
        } else {
          const meta = existingItem.querySelector('.chat-meta');
          const badge = document.createElement('span');
          badge.className = 'unread-badge';
          badge.textContent = conv.unread_count;
          meta.appendChild(badge);
        }
      } else if (unreadBadge) {
        unreadBadge.remove();
      }
    }
  });
}

// Start auto-refresh for conversation list every 5 seconds
setInterval(refreshConversationList, 5000);

// Check user online status
function checkUserOnlineStatus(userId) {
  fetch(urlRoot + '/' + userRole + '/getUserStatus', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'user_id=' + userId
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 'success') {
      updateUserStatus(data.is_online, data.last_seen);
    }
  })
  .catch(error => console.error('Error checking user status:', error));
}

// Update user status display
function updateUserStatus(isOnline, lastSeen) {
  const statusElement = document.getElementById('recipientStatus');
  
  // Check if user is truly online (last_seen within 2 minutes)
  let trulyOnline = false;
  if (isOnline && lastSeen) {
    const lastSeenDate = new Date(lastSeen);
    const now = new Date();
    const diffMs = now - lastSeenDate;
    const diffMins = Math.floor(diffMs / 60000);
    trulyOnline = diffMins < 2; // Consider online if active within last 2 minutes
  }
  
  if (trulyOnline) {
    statusElement.textContent = 'Online';
    statusElement.style.color = '#28a745';
  } else if (lastSeen) {
    const lastSeenDate = new Date(lastSeen);
    const now = new Date();
    const diffMs = now - lastSeenDate;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);
    
    let statusText = '';
    if (diffMins < 1) {
      statusText = 'Last seen just now';
    } else if (diffMins < 60) {
      statusText = 'Last seen ' + diffMins + ' min' + (diffMins > 1 ? 's' : '') + ' ago';
    } else if (diffHours < 24) {
      statusText = 'Last seen ' + diffHours + ' hour' + (diffHours > 1 ? 's' : '') + ' ago';
    } else if (diffDays < 7) {
      statusText = 'Last seen ' + diffDays + ' day' + (diffDays > 1 ? 's' : '') + ' ago';
    } else {
      statusText = 'Last seen ' + lastSeenDate.toLocaleDateString();
    }
    
    statusElement.textContent = statusText;
    statusElement.style.color = '#999';
  } else {
    statusElement.textContent = 'Offline';
    statusElement.style.color = '#999';
  }
}

// Load messages
function loadMessages() {
  if (!currentRecipientId) return;
  
  fetch(urlRoot + '/' + userRole + '/loadMessages', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'recipient_id=' + currentRecipientId
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 'success') {
      displayMessages(data.messages);
      scrollToBottom();
      
      // Mark messages as seen after a 1 second delay (gives user time to actually see them)
      setTimeout(function() {
        markMessagesAsSeen();
      }, 1000);
    } else {
      console.error('Error loading messages:', data.message);
    }
  })
  .catch(error => console.error('Error:', error));
}

// Mark messages as seen
function markMessagesAsSeen() {
  if (!currentRecipientId) return;
  
  fetch(urlRoot + '/' + userRole + '/markAsSeen', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'recipient_id=' + currentRecipientId
  })
  .then(response => response.json())
  .catch(error => console.error('Error marking as seen:', error));
}

// Display messages
function displayMessages(messages) {
  const container = document.getElementById('messagesContainer');
  container.innerHTML = '';
  
  const currentUserId = <?php echo $_SESSION['user_id'] ?? 0; ?>;
  
  if (!Array.isArray(messages)) {
    console.error('Messages is not an array:', messages);
    return;
  }
  
  messages.forEach(msg => {
    const messageWrapper = document.createElement('div');
    messageWrapper.className = 'message-wrapper';
    
    const messageDiv = document.createElement('div');
    messageDiv.className = 'msg ' + (msg.sender_id == currentUserId ? 'sent' : 'received');
    
    // Check if message is deleted
    if (msg.is_deleted == 1) {
      messageDiv.innerHTML = '<em style="opacity: 0.6;">This message was deleted</em>';
      messageDiv.style.fontStyle = 'italic';
      messageDiv.style.opacity = '0.7';
    } else {
      messageDiv.textContent = msg.message;
      messageDiv.dataset.messageId = msg.id;
      messageDiv.dataset.messageText = msg.message;
      
      // Add click handler for own messages (only if not deleted)
      if (msg.sender_id == currentUserId) {
        messageDiv.style.cursor = 'pointer';
        messageDiv.addEventListener('click', function(e) {
          showMessageOptions(e, msg.id, msg.message);
        });
      }
      
      // Add edited indicator
      if (msg.is_edited == 1) {
        const editedSpan = document.createElement('span');
        editedSpan.className = 'edited-indicator';
        editedSpan.textContent = ' (edited)';
        messageDiv.appendChild(editedSpan);
      }
    }
    
    const timeDiv = document.createElement('div');
    timeDiv.className = 'message-time';
    timeDiv.textContent = formatMessageTime(msg.created_at);
    
    messageWrapper.appendChild(messageDiv);
    messageWrapper.appendChild(timeDiv);
    
    // Add seen status for sent messages
    if (msg.sender_id == currentUserId && msg.is_deleted != 1) {
      const seenDiv = document.createElement('div');
      seenDiv.className = 'message-seen';
      if (msg.read_at) {
        seenDiv.innerHTML = '<span class="material-icons">done_all</span> Seen ' + formatMessageTime(msg.read_at);
        seenDiv.style.color = '#4a90e2';
      } else if (msg.is_read == 1) {
        seenDiv.innerHTML = '<span class="material-icons">done_all</span> Delivered';
      } else {
        seenDiv.innerHTML = '<span class="material-icons">done</span> Sent';
      }
      messageWrapper.appendChild(seenDiv);
    }
    
    container.appendChild(messageWrapper);
  });
}

// Format message time
function formatMessageTime(timestamp) {
  const date = new Date(timestamp);
  const now = new Date();
  const diff = now - date;
  const hours = date.getHours().toString().padStart(2, '0');
  const minutes = date.getMinutes().toString().padStart(2, '0');
  
  // If today, show time only
  if (date.toDateString() === now.toDateString()) {
    return hours + ':' + minutes;
  }
  
  // If yesterday
  const yesterday = new Date(now);
  yesterday.setDate(yesterday.getDate() - 1);
  if (date.toDateString() === yesterday.toDateString()) {
    return 'Yesterday ' + hours + ':' + minutes;
  }
  
  // Otherwise show date and time
  return date.toLocaleDateString() + ' ' + hours + ':' + minutes;
}

// Send message
function sendMessage() {
  if (!currentRecipientId) {
    alert('Please select a conversation first');
    return;
  }
  
  const messageInput = document.getElementById('messageInput');
  const message = messageInput.value.trim();
  
  if (!message) {
    return;
  }
  
  fetch(urlRoot + '/' + userRole + '/sendMessage', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'recipient_id=' + currentRecipientId + '&message=' + encodeURIComponent(message)
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 'success') {
      messageInput.value = '';
      loadMessages();
      setTimeout(scrollToBottom, 100);
    } else {
      alert('Failed to send message: ' + (data.message || 'Unknown error'));
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Error sending message');
  });
}

// Handle key press
function handleKeyPress(event) {
  if (event.key === 'Enter' && !event.shiftKey) {
    event.preventDefault();
    sendMessage();
  }
}

// Scroll to bottom
function scrollToBottom() {
  const container = document.getElementById('messagesContainer');
  container.scrollTop = container.scrollHeight;
}

// Show message options (edit/delete)
function showMessageOptions(event, messageId, messageText) {
  event.stopPropagation();
  
  // Remove any existing options menu
  const existingMenu = document.querySelector('.message-options-menu');
  if (existingMenu) {
    existingMenu.remove();
  }
  
  // Create options menu
  const menu = document.createElement('div');
  menu.className = 'message-options-menu';
  menu.innerHTML = `
    <div class="option-item" onclick="editMessage(${messageId}, '${messageText.replace(/'/g, "\\'")}')"><span class="material-icons">edit</span> Edit</div>
    <div class="option-item delete" onclick="deleteMessageConfirm(${messageId})"><span class="material-icons">delete</span> Delete</div>
  `;
  
  // Position menu
  const rect = event.target.getBoundingClientRect();
  menu.style.position = 'fixed';
  menu.style.top = rect.top + 'px';
  menu.style.left = (rect.left - 150) + 'px';
  
  document.body.appendChild(menu);
  
  // Close menu when clicking outside
  setTimeout(() => {
    document.addEventListener('click', function closeMenu(e) {
      if (!menu.contains(e.target)) {
        menu.remove();
        document.removeEventListener('click', closeMenu);
      }
    });
  }, 100);
}

// Edit message
let editingMessageId = null;

function editMessage(messageId, currentText) {
  // Remove options menu
  const menu = document.querySelector('.message-options-menu');
  if (menu) menu.remove();
  
  // Set input to edit mode
  const messageInput = document.getElementById('messageInput');
  messageInput.value = currentText;
  messageInput.focus();
  editingMessageId = messageId;
  
  // Change button to update
  const sendBtn = document.querySelector('.sent-btn');
  sendBtn.innerHTML = '<span class="material-icons">check</span>';
  sendBtn.onclick = updateMessage;
  
  // Add cancel button
  const chatInput = document.querySelector('.chat-input');
  let cancelBtn = document.getElementById('cancelEditBtn');
  if (!cancelBtn) {
    cancelBtn = document.createElement('button');
    cancelBtn.id = 'cancelEditBtn';
    cancelBtn.className = 'cancel-edit-btn';
    cancelBtn.innerHTML = '<span class="material-icons">close</span>';
    cancelBtn.onclick = cancelEdit;
    chatInput.insertBefore(cancelBtn, sendBtn);
  }
}

// Update message
function updateMessage() {
  if (!editingMessageId) return;
  
  const messageInput = document.getElementById('messageInput');
  const newText = messageInput.value.trim();
  
  if (!newText) {
    alert('Message cannot be empty');
    return;
  }
  
  fetch(urlRoot + '/' + userRole + '/updateMessage', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'message_id=' + editingMessageId + '&message=' + encodeURIComponent(newText)
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 'success') {
      cancelEdit();
      loadMessages();
    } else {
      alert('Failed to update message: ' + (data.message || 'Unknown error'));
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Error updating message');
  });
}

// Cancel edit
function cancelEdit() {
  editingMessageId = null;
  const messageInput = document.getElementById('messageInput');
  messageInput.value = '';
  
  // Restore send button
  const sendBtn = document.querySelector('.sent-btn');
  sendBtn.innerHTML = '<span class="material-icons">send</span>';
  sendBtn.onclick = sendMessage;
  
  // Remove cancel button
  const cancelBtn = document.getElementById('cancelEditBtn');
  if (cancelBtn) cancelBtn.remove();
}

// Delete message with confirmation
let pendingDeleteMessageId = null;

function deleteMessageConfirm(messageId) {
  // Remove options menu
  const menu = document.querySelector('.message-options-menu');
  if (menu) menu.remove();
  
  // Store message ID and show modal
  pendingDeleteMessageId = messageId;
  document.getElementById('deleteModalOverlay').style.display = 'flex';
}

function closeDeleteModal() {
  document.getElementById('deleteModalOverlay').style.display = 'none';
  pendingDeleteMessageId = null;
}

function confirmDeleteMessage() {
  if (!pendingDeleteMessageId) return;
  
  const messageId = pendingDeleteMessageId;
  closeDeleteModal();
  
  fetch(urlRoot + '/' + userRole + '/deleteMessage', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'message_id=' + messageId
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 'success') {
      loadMessages();
      refreshConversationList();
    } else {
      alert('Failed to delete message');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Error deleting message');
  });
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
  const searchTerm = e.target.value.toLowerCase();
  const chatItems = document.querySelectorAll('.chat-item');
  
  chatItems.forEach(item => {
    const name = item.querySelector('.name').textContent.toLowerCase();
    if (name.includes(searchTerm)) {
      item.style.display = 'flex';
    } else {
      item.style.display = 'none';
    }
  });
});

// Cleanup interval when page unloads
window.addEventListener('beforeunload', function() {
  if (messageRefreshInterval) {
    clearInterval(messageRefreshInterval);
  }
  
  // Set user offline when leaving the page
  navigator.sendBeacon(urlRoot + '/' + userRole + '/setOffline');
});

// Keep user online with heartbeat (every 30 seconds)
setInterval(function() {
  fetch(urlRoot + '/' + userRole + '/updateLastSeen', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    }
  });
}, 30000);

// Auto-select conversation if specified in URL
<?php if (isset($data['current_recipient_id']) && $data['current_recipient_id']): ?>
window.addEventListener('DOMContentLoaded', function() {
  selectConversation(<?php echo $data['current_recipient_id']; ?>, null);
});
<?php endif; ?>
</script>
