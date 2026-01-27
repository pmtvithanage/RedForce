<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>


<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">



<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/mobilerider/messages.css">

<div class="messages-container">
  <!-- Left: Chat list -->
  <div class="chat-list-panel">
    <input type="text" placeholder="Search" class="search" id="searchInput">

    <div class="chat-list" id="chatList">
      <?php
        // Build list of conversation user ids so we can show "Start a new conversation" users separately
        $convIds = [];
        if (!empty($conversations)) {
            foreach ($conversations as $conv) {
                $convIds[] = $conv->id;
            }
        }
      ?>

      <!-- Conversations (existing chats) -->
      <?php if (!empty($conversations)): ?>
        <?php foreach ($conversations as $conv): ?>
          <div class="chat-item" data-user-id="<?php echo $conv->id; ?>" onclick="selectConversation(<?php echo $conv->id; ?>, event)">
            <div class="avatar">
              <span class="material-icons">person</span>
            </div>
            <div class="chat-info">
              <p class="name">
                <?php echo htmlspecialchars($conv->name); ?>
                <span class="role"><?php echo htmlspecialchars($conv->role); ?></span>
              </p>
              <p class="preview">
                <?php echo htmlspecialchars(substr($conv->last_message ?? 'No messages', 0, 50)); ?>...
              </p>
            </div>
            <div class="chat-meta">
              <span class="time">
                <?php 
                  if (!empty($conv->last_message_time)) {
                    $messageTime = new DateTime($conv->last_message_time);
                    echo $messageTime->format('H:i');
                  }
                ?>
              </span>
              <?php if (!empty($conv->unread_count) && $conv->unread_count > 0): ?>
                <span class="unread-badge"><?php echo $conv->unread_count; ?></span>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

      <!-- Start new conversation (users without existing conversations) -->
      <?php
        $startableUsers = [];
        if (!empty($all_users)) {
            foreach ($all_users as $user) {
                if (!in_array($user->id, $convIds)) {
                    $startableUsers[] = $user;
                }
            }
        }
      ?>

      <?php if (!empty($startableUsers)): ?>
        <p style="padding: 10px 15px; font-size: 12px; color: #999; border-bottom: 1px solid #f0f0f0;">Start a new conversation</p>
        <?php foreach ($startableUsers as $user): ?>
          <div class="chat-item" data-user-id="<?php echo $user->id; ?>" onclick="selectConversation(<?php echo $user->id; ?>, event)">
            <div class="avatar">
              <span class="material-icons">person</span>
            </div>
            <div class="chat-info">
              <p class="name">
                <?php echo htmlspecialchars($user->name); ?>
                <span class="role"><?php echo htmlspecialchars($user->role); ?></span>
              </p>
              <p class="preview">Click to start conversation</p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php elseif (empty($conversations)): ?>
        <p style="padding: 20px; text-align: center; color: #999;">No users available</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Right: Chat messages -->
  <div class="chat-window" id="chatWindow" style="display: none;">
    <div class="chat-header">
      <span id="recipientName">Select a conversation</span>
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
</div>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<script>
let currentRecipientId = null;
let currentRecipientName = null;
let urlRoot = '<?php echo URL_ROOT; ?>';
let messageRefreshInterval;

function selectConversation(userId, event) {
  if (event) {
    event.preventDefault();
  }
  
  currentRecipientId = userId;
  
  // Update UI - remove active class from all
  document.querySelectorAll('.chat-item').forEach(item => {
    item.classList.remove('active');
  });
  
  // Add active class to clicked item
  const activeItem = document.querySelector(`[data-user-id="${userId}"]`);
  if (activeItem) {
    activeItem.classList.add('active');
    currentRecipientName = activeItem.querySelector('.name').textContent.trim();
  }
  
  // Show chat window
  document.getElementById('chatWindow').style.display = 'flex';
  document.getElementById('noSelection').style.display = 'none';
  
  // Load messages
  loadMessages();
  
  // Update header
  document.getElementById('recipientName').textContent = currentRecipientName;
  
  // Clear any existing interval
  if (messageRefreshInterval) {
    clearInterval(messageRefreshInterval);
  }
  
  // Auto-load messages every 3 seconds
  messageRefreshInterval = setInterval(function() {
    if (currentRecipientId) {
      loadMessages();
    }
  }, 3000);
}

function loadMessages() {
  if (!currentRecipientId) return;
  
  fetch(urlRoot + '/supervisor/loadMessages', {
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
    } else {
      console.error('Error loading messages:', data.message);
    }
  })
  .catch(error => console.error('Error:', error));
}

function displayMessages(messages) {
  const container = document.getElementById('messagesContainer');
  container.innerHTML = '';
  
  const currentUserId = <?php echo $_SESSION['user_id']; ?>;
  
  if (!Array.isArray(messages)) {
    console.error('Messages is not an array:', messages);
    return;
  }
  
  messages.forEach(msg => {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'msg ' + (msg.sender_id == currentUserId ? 'sent' : 'received');
    messageDiv.textContent = msg.message;
    container.appendChild(messageDiv);
  });
}

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
  
  fetch(urlRoot + '/supervisor/sendMessage', {
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

function handleKeyPress(event) {
  if (event.key === 'Enter' && !event.shiftKey) {
    event.preventDefault();
    sendMessage();
  }
}

function scrollToBottom() {
  const container = document.getElementById('messagesContainer');
  container.scrollTop = container.scrollHeight;
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
});
</script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>