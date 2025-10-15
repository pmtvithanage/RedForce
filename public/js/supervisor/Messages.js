// RED FORCE - Supervisor Communication System
// JavaScript functionality for the communication interface

document.addEventListener('DOMContentLoaded', function() {
    // Initialize the communication system
    const CommunicationSystem = {
        messages: [],
        currentRole: 'Admin Panel',
        isTyping: false,
        
        // DOM elements
        elements: {
            messagesContainer: document.getElementById('messages'),
            textarea: document.querySelector('.input-wrap textarea'),
            sendButton: document.querySelector('.send'),
            attachButton: document.querySelector('.attach'),
            roleSelect: document.querySelector('.chat-role-select'),
            requestLeaveBtn: document.querySelector('.actions .ghost:first-child'),
            reportIssuesBtn: document.querySelector('.actions .ghost:last-child')
        },

        // Initialize the system
        init() {
            this.bindEvents();
            this.loadInitialMessages();
            this.setupAutoResize();
            this.setupRoleSwitching();
        },

        // Bind event listeners
        bindEvents() {
            // Send message functionality
            this.elements.sendButton.addEventListener('click', () => this.sendMessage());
            this.elements.textarea.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.sendMessage();
                }
            });

            // Attach file functionality
            this.elements.attachButton.addEventListener('click', () => this.attachFile());

            // Action buttons
            this.elements.requestLeaveBtn.addEventListener('click', () => this.requestLeave());
            this.elements.reportIssuesBtn.addEventListener('click', () => this.reportIssues());

            // Menu navigation
            document.querySelectorAll('.menu .item').forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.handleNavigation(item);
                });
            });
        },

        // Load initial messages
        loadInitialMessages() {
            // Add some sample messages to demonstrate functionality
            const sampleMessages = [
                {
                    type: 'in',
                    content: 'Good Morning!!\nPlease Ensure that all security officers a site a have submitted their attendence by 9.00 AM.\nAlso, don\'t forget to update the incident report If there were any issues during night shift\nLet me know once it\'s done\nThank You',
                    timestamp: new Date(Date.now() - 3600000), // 1 hour ago
                    sender: 'Admin Panel'
                },
                {
                    type: 'out',
                    content: 'Sure, I will take care of it.',
                    timestamp: new Date(Date.now() - 1800000), // 30 minutes ago
                    sender: 'Supervisor'
                }
            ];

            sampleMessages.forEach(msg => {
                this.messages.push(msg);
                this.displayMessage(msg);
            });
        },

        // Send a new message
        sendMessage() {
            const content = this.elements.textarea.value.trim();
            if (!content) return;

            const message = {
                type: 'out',
                content: content,
                timestamp: new Date(),
                sender: 'Supervisor'
            };

            this.messages.push(message);
            this.displayMessage(message);
            this.elements.textarea.value = '';
            this.elements.textarea.style.height = 'auto';

            // Simulate response after a short delay
            setTimeout(() => this.simulateResponse(), 1000 + Math.random() * 2000);
        },

        // Display a message in the chat
        displayMessage(message) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `msg msg-${message.type}`;
            
            const bubble = document.createElement('div');
            bubble.className = 'bubble';
            
            // Handle multi-line content
            const contentLines = message.content.split('\n');
            contentLines.forEach((line, index) => {
                if (line.trim()) {
                    const p = document.createElement('p');
                    p.textContent = line;
                    bubble.appendChild(p);
                }
            });

            // Add timestamp
            const timestamp = document.createElement('small');
            timestamp.style.cssText = 'font-size: 11px; opacity: 0.7; display: block; margin-top: 4px;';
            timestamp.textContent = this.formatTimestamp(message.timestamp);
            bubble.appendChild(timestamp);

            messageDiv.appendChild(bubble);
            this.elements.messagesContainer.appendChild(messageDiv);
            
            // Scroll to bottom
            this.scrollToBottom();
        },

        // Simulate automated responses
        simulateResponse() {
            const responses = {
                'Admin Panel': [
                    'Thank you for the update.',
                    'Please keep me informed of any developments.',
                    'Good work, continue monitoring the situation.',
                    'I\'ll review the reports and get back to you.',
                    'Make sure all protocols are being followed.'
                ],
                'Premise Officer': [
                    'Understood, I\'ll coordinate with the team.',
                    'Will ensure all security measures are in place.',
                    'I\'ll check the perimeter and report back.',
                    'Noted, I\'ll update the duty roster accordingly.',
                    'I\'ll verify all equipment is functioning properly.'
                ],
                'Client': [
                    'Thank you for your attention to this matter.',
                    'We appreciate your professional service.',
                    'Please keep us updated on any issues.',
                    'Your security team is doing excellent work.',
                    'We value your commitment to safety.'
                ],
                'Caretaker': [
                    'I\'ll make sure the premises are properly maintained.',
                    'Will check all facilities and report any issues.',
                    'I\'ll coordinate with the cleaning staff.',
                    'Noted, I\'ll ensure everything is in order.',
                    'I\'ll monitor the building systems closely.'
                ]
            };

            const currentResponses = responses[this.currentRole] || responses['Admin Panel'];
            const randomResponse = currentResponses[Math.floor(Math.random() * currentResponses.length)];

            const responseMessage = {
                type: 'in',
                content: randomResponse,
                timestamp: new Date(),
                sender: this.currentRole
            };

            this.messages.push(responseMessage);
            this.displayMessage(responseMessage);
        },

        // Format timestamp
        formatTimestamp(date) {
            const now = new Date();
            const diff = now - date;
            const minutes = Math.floor(diff / 60000);
            const hours = Math.floor(diff / 3600000);

            if (minutes < 1) return 'Just now';
            if (minutes < 60) return `${minutes}m ago`;
            if (hours < 24) return `${hours}h ago`;
            return date.toLocaleDateString();
        },

        // Scroll to bottom of messages
        scrollToBottom() {
            this.elements.messagesContainer.scrollTop = this.elements.messagesContainer.scrollHeight;
        },

        // Setup auto-resize for textarea
        setupAutoResize() {
            this.elements.textarea.addEventListener('input', () => {
                this.elements.textarea.style.height = 'auto';
                this.elements.textarea.style.height = Math.min(this.elements.textarea.scrollHeight, 120) + 'px';
            });
        },

        // Setup role switching
        setupRoleSwitching() {
            this.elements.roleSelect.addEventListener('change', (e) => {
                this.currentRole = e.target.value;
                this.updateChatHeader();
                this.showRoleNotification();
            });
        },

        // Update chat header based on selected role
        updateChatHeader() {
            // You can add visual indicators here
            console.log(`Switched to: ${this.currentRole}`);
        },

        // Show role switch notification
        showRoleNotification() {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #e91e63;
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                font-size: 14px;
                z-index: 1000;
                animation: slideIn 0.3s ease-out;
            `;
            notification.textContent = `Switched to ${this.currentRole}`;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-in';
                setTimeout(() => notification.remove(), 300);
            }, 2000);
        },

        // Attach file functionality
        attachFile() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = '.pdf,.doc,.docx,.jpg,.jpeg,.png';
            input.style.display = 'none';
            
            input.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    this.handleFileAttachment(file);
                }
            });
            
            document.body.appendChild(input);
            input.click();
            document.body.removeChild(input);
        },

        // Handle file attachment
        handleFileAttachment(file) {
            const message = {
                type: 'out',
                content: `📎 Attached: ${file.name} (${this.formatFileSize(file.size)})`,
                timestamp: new Date(),
                sender: 'Supervisor',
                attachment: file
            };

            this.messages.push(message);
            this.displayMessage(message);
        },

        // Format file size
        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        // Request leave functionality
        requestLeave() {
            const leaveForm = this.createLeaveForm();
            this.showModal('Request Leave', leaveForm);
        },

        // Report issues functionality
        reportIssues() {
            const issueForm = this.createIssueForm();
            this.showModal('Report Issues', issueForm);
        },

        // Create leave request form
        createLeaveForm() {
            return `
                <div style="padding: 20px;">
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Leave Type:</label>
                        <select style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            <option>Sick Leave</option>
                            <option>Annual Leave</option>
                            <option>Emergency Leave</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">From Date:</label>
                        <input type="date" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">To Date:</label>
                        <input type="date" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Reason:</label>
                        <textarea style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; min-height: 80px;" placeholder="Please provide a reason for your leave request..."></textarea>
                    </div>
                    <div style="text-align: right;">
                        <button onclick="this.closest('.modal').remove()" style="background: #ccc; border: none; padding: 8px 16px; border-radius: 4px; margin-right: 10px; cursor: pointer;">Cancel</button>
                        <button onclick="this.closest('.modal').remove(); alert('Leave request submitted successfully!')" style="background: #e91e63; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Submit</button>
                    </div>
                </div>
            `;
        },

        // Create issue report form
        createIssueForm() {
            return `
                <div style="padding: 20px;">
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Issue Type:</label>
                        <select style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            <option>Security Breach</option>
                            <option>Equipment Malfunction</option>
                            <option>Personnel Issue</option>
                            <option>Safety Concern</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Priority:</label>
                        <select style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            <option>Low</option>
                            <option>Medium</option>
                            <option>High</option>
                            <option>Critical</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Description:</label>
                        <textarea style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; min-height: 100px;" placeholder="Please describe the issue in detail..."></textarea>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Location:</label>
                        <input type="text" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" placeholder="Enter the location where the issue occurred">
                    </div>
                    <div style="text-align: right;">
                        <button onclick="this.closest('.modal').remove()" style="background: #ccc; border: none; padding: 8px 16px; border-radius: 4px; margin-right: 10px; cursor: pointer;">Cancel</button>
                        <button onclick="this.closest('.modal').remove(); alert('Issue report submitted successfully!')" style="background: #e91e63; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Submit</button>
                    </div>
                </div>
            `;
        },

        // Show modal
        showModal(title, content) {
            const modal = document.createElement('div');
            modal.className = 'modal';
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
            `;

            const modalContent = document.createElement('div');
            modalContent.style.cssText = `
                background: white;
                border-radius: 8px;
                max-width: 500px;
                width: 90%;
                max-height: 80vh;
                overflow-y: auto;
            `;

            const modalHeader = document.createElement('div');
            modalHeader.style.cssText = `
                padding: 15px 20px;
                border-bottom: 1px solid #eee;
                font-weight: 600;
                font-size: 16px;
            `;
            modalHeader.textContent = title;

            modalContent.appendChild(modalHeader);
            modalContent.insertAdjacentHTML('beforeend', content);
            modal.appendChild(modalContent);
            document.body.appendChild(modal);

            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.remove();
            });
        },

        // Handle navigation
        handleNavigation(item) {
            // Remove active class from all items
            document.querySelectorAll('.menu .item').forEach(i => i.classList.remove('active'));
            // Add active class to clicked item
            item.classList.add('active');
            
            // You can add navigation logic here
            console.log(`Navigating to: ${item.textContent.trim()}`);
        }
    };

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);

    // Initialize the communication system
    CommunicationSystem.init();

    // Add some additional utility functions
    window.CommunicationSystem = CommunicationSystem;
});

// Export for potential module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CommunicationSystem;
}
