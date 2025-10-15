function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Open chat modal with specific conversation
function openChatModal(messageId) {
    const chatModal = document.getElementById('chatModal');
    const chatContent = document.getElementById('chatContent');
    
    // Load conversation data
    loadConversation(messageId, chatContent);
    
    chatModal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Load conversation data
function loadConversation(messageId, container) {
    // Mock conversation data
    const conversations = {
        1: {
            title: "Messages",
            participant: "Admin - Red Force",
            messages: [
                {
                    sender: "Admin - Red Force",
                    message: "Dear Officer, kindly note that we are assigning you to the night shift tonight as per the schedule.",
                    time: "14:32",
                    isOwn: false
                },
                {
                    sender: "You",
                    message: "Understood. What time should I report?",
                    time: "14:35",
                    isOwn: true
                },
                {
                    sender: "Admin - Red Force",
                    message: "Please report at 8:00 PM sharp. The shift ends at 6:00 AM.",
                    time: "14:36",
                    isOwn: false
                }
            ]
        },
        2: {
            title: "Messages",
            participant: "John Silva - Supervisor",
            messages: [
                {
                    sender: "John Silva",
                    message: "Your shift schedule has been updated. Please check your email for details.",
                    time: "12:32",
                    isOwn: false
                },
                {
                    sender: "You",
                    message: "Thank you for the update.",
                    time: "12:35",
                    isOwn: true
                }
            ]
        },
        3: {
            title: "Messages",
            participant: "Naduni Senanayake - HR Officer",
            messages: [
                {
                    sender: "Naduni Senanayake",
                    message: "Please submit your monthly report by the end of this week.",
                    time: "01:42",
                    isOwn: false
                }
            ]
        },
        4: {
            title: "Messages",
            participant: "Manager - Site Manager",
            messages: [
                {
                    sender: "Manager",
                    message: "Team meeting scheduled for tomorrow at 10:00 AM. Please attend.",
                    time: "01:22",
                    isOwn: false
                }
            ]
        }
    };

    const conversation = conversations[messageId] || {
        title: "Messages",
        participant: "Red Force Team",
        messages: []
    };

    // Update modal header
    document.getElementById('chatTitle').textContent = conversation.title;
    document.getElementById('chatParticipant').textContent = conversation.participant;

    // Clear and populate messages
    container.innerHTML = '';
    
    if (conversation.messages.length === 0) {
        container.innerHTML = '<div class="no-messages">No messages in this conversation</div>';
        return;
    }

    conversation.messages.forEach(msg => {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${msg.isOwn ? 'own-message' : 'other-message'}`;
        
        messageDiv.innerHTML = `
            <div class="message-bubble">
                <div class="message-text">${msg.message}</div>
            </div>
        `;
        
        container.appendChild(messageDiv);
    });

    // Scroll to bottom
    container.scrollTop = container.scrollHeight;
}

// Send new message
function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    
    if (message === '') return;

    const chatContent = document.getElementById('chatContent');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'chat-message own-message';
    
    messageDiv.innerHTML = `
        <div class="message-bubble">
            <div class="message-text">${message}</div>
        </div>
    `;
    
    chatContent.appendChild(messageDiv);
    chatContent.scrollTop = chatContent.scrollHeight;
    
    messageInput.value = '';
}

// Handle Enter key in message input
function handleMessageKeyPress(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        sendMessage();
    }
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modals = document.getElementsByClassName('modal');
    for (let modal of modals) {
        if (event.target == modal) {
            modal.style.display = 'none';
            if (modal.id === 'chatModal') {
                document.body.style.overflow = 'auto';
            }
        }
    }
}

// Initialize dashboard when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Premise Officer Dashboard loaded successfully');
});