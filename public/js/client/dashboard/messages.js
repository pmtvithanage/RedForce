// Messages Page JavaScript

// Load chat function
function loadChat(chatId, participantName) {
    // Update active conversation
    document.querySelectorAll('.conversation-item').forEach(item => {
        item.classList.remove('active');
    });
    event.currentTarget.classList.add('active');
    
    // Update chat header
    document.getElementById('chatName').textContent = participantName;
    
    // Clear unread badge
    const badge = event.currentTarget.querySelector('.unread-badge');
    if (badge) {
        badge.remove();
    }
    
    // Load chat messages (mock data for now)
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.innerHTML = `
        <div class="message received">
            <div class="message-avatar">${participantName.charAt(0)}</div>
            <div class="message-content">
                <div class="message-text">Sample message from ${participantName}</div>
                <div class="message-time">Just now</div>
            </div>
        </div>
    `;
    
    // Scroll to bottom
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Send message function
function sendMessage() {
    const input = document.getElementById('messageInput');
    const messageText = input.value.trim();
    
    if (messageText === '') return;
    
    const chatMessages = document.getElementById('chatMessages');
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
    
    // Create message element
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message sent';
    messageDiv.innerHTML = `
        <div class="message-content">
            <div class="message-text">${messageText}</div>
            <div class="message-time">${timeString}</div>
        </div>
    `;
    
    chatMessages.appendChild(messageDiv);
    
    // Clear input
    input.value = '';
    
    // Scroll to bottom
    chatMessages.scrollTop = chatMessages.scrollHeight;
    
    // Simulate response after 2 seconds
    setTimeout(() => {
        const responseDiv = document.createElement('div');
        responseDiv.className = 'message received';
        responseDiv.innerHTML = `
            <div class="message-avatar">A</div>
            <div class="message-content">
                <div class="message-text">Thank you for your message. We'll get back to you shortly.</div>
                <div class="message-time">${timeString}</div>
            </div>
        `;
        chatMessages.appendChild(responseDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }, 2000);
}

// Handle Enter key press
function handleKeyPress(event) {
    if (event.key === 'Enter') {
        sendMessage();
    }
}

// Search messages
document.getElementById('searchMessages').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const conversations = document.querySelectorAll('.conversation-item');
    
    conversations.forEach(conversation => {
        const name = conversation.querySelector('.conversation-name').textContent.toLowerCase();
        const preview = conversation.querySelector('.conversation-preview').textContent.toLowerCase();
        
        if (name.includes(searchTerm) || preview.includes(searchTerm)) {
            conversation.style.display = 'flex';
        } else {
            conversation.style.display = 'none';
        }
    });
});
