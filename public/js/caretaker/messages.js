// Sample messages data (will be replaced with server data)
const sampleMessages = [
  {
    id: 1,
    sender: 'Admin - Red Force',
    initials: 'A',
    subject: 'Deployment update',
    preview: 'Dear Mr. Fernando, kindly note that we are assigning 2 officers tonight to the Kurune...',
    time: '2h ago',
    timestamp: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString()
  },
  {
    id: 2,
    sender: 'John Silva',
    initials: 'JS',
    subject: 'Attendance notice',
    preview: 'Officer Ravindu Fernando was absent yesterday. Please follow up.',
    time: '5h ago',
    timestamp: new Date(Date.now() - 5 * 60 * 60 * 1000).toISOString()
  },
  {
    id: 3,
    sender: 'Nadi Senanayake',
    initials: 'NS',
    subject: 'Training docs',
    preview: 'Officer training certificates need to be submitted by end of week.',
    time: '1d ago',
    timestamp: new Date(Date.now() - 24 * 60 * 60 * 1000).toISOString()
  },
  {
    id: 4,
    sender: 'Nishadi Dissanayake',
    initials: 'ND',
    subject: 'Shift schedules',
    preview: 'Updated shift schedules for all officers have been sent to your email.',
    time: '2d ago',
    timestamp: new Date(Date.now() - 48 * 60 * 60 * 1000).toISOString()
  },
  {
    id: 5,
    sender: 'Supervisor Team',
    initials: 'ST',
    subject: 'Site inspection report',
    preview: 'Monthly site inspection has been completed. Please review the attached report.',
    time: '2d ago',
    timestamp: new Date(Date.now() - 50 * 60 * 60 * 1000).toISOString()
  },
  {
    id: 6,
    sender: 'HR Department',
    initials: 'HR',
    subject: 'Leave approval',
    preview: 'Your leave request for next week has been approved. Enjoy your time off!',
    time: '3d ago',
    timestamp: new Date(Date.now() - 72 * 60 * 60 * 1000).toISOString()
  },
  {
    id: 7,
    sender: 'Kamal Perera',
    initials: 'KP',
    subject: 'Equipment maintenance',
    preview: 'Security equipment at Site B requires urgent maintenance. Please arrange.',
    time: '3d ago',
    timestamp: new Date(Date.now() - 75 * 60 * 60 * 1000).toISOString()
  },
  {
    id: 8,
    sender: 'Admin - Red Force',
    initials: 'A',
    subject: 'Monthly meeting',
    preview: 'Reminder: Monthly coordination meeting scheduled for Friday at 10:00 AM.',
    time: '4d ago',
    timestamp: new Date(Date.now() - 96 * 60 * 60 * 1000).toISOString()
  },
  {
    id: 9,
    sender: 'Saman Jayasinghe',
    initials: 'SJ',
    subject: 'Incident report',
    preview: 'Minor incident reported at Gate 3. All personnel are safe, no injuries.',
    time: '5d ago',
    timestamp: new Date(Date.now() - 120 * 60 * 60 * 1000).toISOString()
  },
  {
    id: 10,
    sender: 'Finance Team',
    initials: 'FT',
    subject: 'Salary payment',
    preview: 'Your salary for this month has been processed and credited to your account.',
    time: '6d ago',
    timestamp: new Date(Date.now() - 144 * 60 * 60 * 1000).toISOString()
  },
  {
    id: 11,
    sender: 'Ravi Wickramasinghe',
    initials: 'RW',
    subject: 'New officer onboarding',
    preview: 'Three new officers will join next Monday. Please prepare orientation schedule.',
    time: '6d ago',
    timestamp: new Date(Date.now() - 150 * 60 * 60 * 1000).toISOString()
  },
  {
    id: 12,
    sender: 'Security Operations',
    initials: 'SO',
    subject: 'Emergency drill',
    preview: 'Emergency evacuation drill scheduled for next Wednesday. All officers must participate.',
    time: '1w ago',
    timestamp: new Date(Date.now() - 168 * 60 * 60 * 1000).toISOString()
  },
  {
    id: 13,
    sender: 'Chaminda Silva',
    initials: 'CS',
    subject: 'Uniform replacement',
    preview: 'Request for uniform replacement has been approved. Please collect from stores.',
    time: '1w ago',
    timestamp: new Date(Date.now() - 180 * 60 * 60 * 1000).toISOString()
  },
  {
    id: 14,
    sender: 'Admin - Red Force',
    initials: 'A',
    subject: 'Policy update',
    preview: 'New security protocols have been implemented. Please review attached document.',
    time: '1w ago',
    timestamp: new Date(Date.now() - 192 * 60 * 60 * 1000).toISOString()
  },
  {
    id: 15,
    sender: 'Prasad Fernando',
    initials: 'PF',
    subject: 'Vehicle inspection',
    preview: 'Company vehicle inspection completed. All vehicles passed the safety check.',
    time: '2w ago',
    timestamp: new Date(Date.now() - 336 * 60 * 60 * 1000).toISOString()
  }
];

// Chat conversation data
const chatConversations = {
  1: [
    { sender: 'Admin - Red Force', text: 'Dear Mr. Fernando, kindly note that we are assigning 2 officers tonight to the Kurunegala site.', time: '2h ago', isOwn: false },
    { sender: 'You', text: 'Understood, I will coordinate with the team.', time: '1h ago', isOwn: true },
    { sender: 'Admin - Red Force', text: 'Thank you for your prompt response.', time: '1h ago', isOwn: false }
  ],
  2: [
    { sender: 'John Silva', text: 'Officer Ravindu Fernando was absent yesterday without prior notice.', time: '5h ago', isOwn: false },
    { sender: 'You', text: 'I will follow up with Officer Fernando immediately.', time: '4h ago', isOwn: true }
  ],
  3: [
    { sender: 'Nadi Senanayake', text: 'Officer training certificates need to be submitted by end of week.', time: '1d ago', isOwn: false },
    { sender: 'You', text: 'I will collect all certificates and submit them by Friday.', time: '1d ago', isOwn: true }
  ],
  4: [
    { sender: 'Nishadi Dissanayake', text: 'Updated shift schedules for all officers have been sent to your email.', time: '2d ago', isOwn: false },
    { sender: 'You', text: 'Received. I have reviewed the schedules.', time: '2d ago', isOwn: true }
  ],
  5: [
    { sender: 'Supervisor Team', text: 'Monthly site inspection has been completed. Please review the attached report.', time: '2d ago', isOwn: false },
    { sender: 'You', text: 'Thank you. I will review and respond by tomorrow.', time: '2d ago', isOwn: true }
  ],
  6: [
    { sender: 'HR Department', text: 'Your leave request for next week has been approved. Enjoy your time off!', time: '3d ago', isOwn: false },
    { sender: 'You', text: 'Thank you very much!', time: '3d ago', isOwn: true }
  ],
  7: [
    { sender: 'Kamal Perera', text: 'Security equipment at Site B requires urgent maintenance. Please arrange.', time: '3d ago', isOwn: false },
    { sender: 'You', text: 'I will contact the maintenance team today.', time: '3d ago', isOwn: true }
  ],
  8: [
    { sender: 'Admin - Red Force', text: 'Reminder: Monthly coordination meeting scheduled for Friday at 10:00 AM.', time: '4d ago', isOwn: false },
    { sender: 'You', text: 'Confirmed. I will attend.', time: '4d ago', isOwn: true }
  ],
  9: [
    { sender: 'Saman Jayasinghe', text: 'Minor incident reported at Gate 3. All personnel are safe, no injuries.', time: '5d ago', isOwn: false },
    { sender: 'You', text: 'Good to hear everyone is safe. I will review the incident report.', time: '5d ago', isOwn: true }
  ],
  10: [
    { sender: 'Finance Team', text: 'Your salary for this month has been processed and credited to your account.', time: '6d ago', isOwn: false }
  ],
  11: [
    { sender: 'Ravi Wickramasinghe', text: 'Three new officers will join next Monday. Please prepare orientation schedule.', time: '6d ago', isOwn: false },
    { sender: 'You', text: 'I will prepare the orientation program and send it for review.', time: '6d ago', isOwn: true }
  ],
  12: [
    { sender: 'Security Operations', text: 'Emergency evacuation drill scheduled for next Wednesday. All officers must participate.', time: '1w ago', isOwn: false },
    { sender: 'You', text: 'Noted. I will ensure all officers are informed.', time: '1w ago', isOwn: true }
  ],
  13: [
    { sender: 'Chaminda Silva', text: 'Request for uniform replacement has been approved. Please collect from stores.', time: '1w ago', isOwn: false },
    { sender: 'You', text: 'Thank you. I will collect it this week.', time: '1w ago', isOwn: true }
  ],
  14: [
    { sender: 'Admin - Red Force', text: 'New security protocols have been implemented. Please review attached document.', time: '1w ago', isOwn: false },
    { sender: 'You', text: 'I have reviewed the protocols and will implement them.', time: '1w ago', isOwn: true }
  ],
  15: [
    { sender: 'Prasad Fernando', text: 'Company vehicle inspection completed. All vehicles passed the safety check.', time: '2w ago', isOwn: false }
  ]
};

// DOM elements
let messagesList;
let chatModal;
let chatBackdrop;
let closeChatBtn;
let chatMessages;
let chatInput;
let sendBtn;
let micBtn;
let chatTitle;
let chatSubtitle;
let currentChatId = null;

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', () => {
  // Get DOM elements
  messagesList = document.getElementById('messagesList');
  chatModal = document.getElementById('chatModal');
  chatBackdrop = document.getElementById('chatBackdrop');
  closeChatBtn = document.getElementById('closeChatBtn');
  chatMessages = document.getElementById('chatMessages');
  chatInput = document.getElementById('chatInput');
  sendBtn = document.getElementById('sendBtn');
  micBtn = document.getElementById('micBtn');
  chatTitle = document.getElementById('chatTitle');
  chatSubtitle = document.getElementById('chatSubtitle');

  // Render messages
  renderMessages(sampleMessages);

  // Event listeners
  closeChatBtn?.addEventListener('click', closeChat);
  chatBackdrop?.addEventListener('click', closeChat);
  sendBtn?.addEventListener('click', sendMessage);
  chatInput?.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') sendMessage();
  });
  micBtn?.addEventListener('click', () => {
    alert('Voice message feature coming soon!');
  });

  // Close on Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !chatModal.hasAttribute('hidden')) {
      closeChat();
    }
  });
});

// Render messages list
function renderMessages(messages) {
  if (!messagesList) return;
  
  messagesList.innerHTML = '';
  
  messages.forEach(msg => {
    const messageItem = createMessageItem(msg);
    messagesList.appendChild(messageItem);
  });
}

// Create message item
function createMessageItem(msg) {
  const template = document.getElementById('messageItemTpl');
  const clone = template.content.cloneNode(true);
  
  const button = clone.querySelector('.message-item');
  const avatar = clone.querySelector('.avatar');
  const sender = clone.querySelector('.sender');
  const time = clone.querySelector('.time');
  const subject = clone.querySelector('.subject');
  const preview = clone.querySelector('.preview');
  
  // Set avatar color based on initials
  const colors = ['#e91e63', '#9c27b0', '#3f51b5', '#00bcd4', '#4caf50', '#ff9800'];
  const colorIndex = msg.initials.charCodeAt(0) % colors.length;
  avatar.style.backgroundColor = colors[colorIndex];
  avatar.setAttribute('data-initials', msg.initials);
  avatar.textContent = msg.initials;
  
  sender.textContent = msg.sender;
  time.textContent = msg.time;
  time.setAttribute('datetime', msg.timestamp);
  subject.textContent = msg.subject;
  preview.textContent = msg.preview;
  
  button.addEventListener('click', () => openChat(msg));
  
  return clone;
}

// Open chat modal
function openChat(msg) {
  currentChatId = msg.id;
  chatSubtitle.textContent = msg.sender;
  
  // Load conversation
  const conversation = chatConversations[msg.id] || [];
  renderConversation(conversation);
  
  // Show modal
  chatModal.removeAttribute('hidden');
  chatBackdrop.removeAttribute('hidden');
  chatInput.focus();
}

// Close chat modal
function closeChat() {
  chatModal.setAttribute('hidden', '');
  chatBackdrop.setAttribute('hidden', '');
  currentChatId = null;
  chatInput.value = '';
}

// Render conversation
function renderConversation(conversation) {
  chatMessages.innerHTML = '';
  
  conversation.forEach(msg => {
    const bubble = document.createElement('div');
    bubble.className = `message-bubble ${msg.isOwn ? 'own' : 'other'}`;
    
    const content = document.createElement('div');
    content.className = 'bubble-content';
    content.textContent = msg.text;
    
    const time = document.createElement('time');
    time.className = 'bubble-time';
    time.textContent = msg.time;
    
    bubble.appendChild(content);
    bubble.appendChild(time);
    chatMessages.appendChild(bubble);
  });
  
  // Scroll to bottom
  chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Send message
function sendMessage() {
  const text = chatInput.value.trim();
  if (!text || currentChatId === null) return;
  
  // Add to conversation
  const newMsg = {
    sender: 'You',
    text: text,
    time: 'Just now',
    isOwn: true
  };
  
  if (!chatConversations[currentChatId]) {
    chatConversations[currentChatId] = [];
  }
  chatConversations[currentChatId].push(newMsg);
  
  // Re-render
  renderConversation(chatConversations[currentChatId]);
  
  // Clear input
  chatInput.value = '';
  chatInput.focus();
  
  // TODO: Send to server via AJAX
}
