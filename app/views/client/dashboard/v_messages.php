<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/client/dashboard/messages_style.css">

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Back Button -->
<div class="main-content">
    <button class="tertiary-btn back-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/client/dashboard'">
        <span class="material-icons">arrow_back</span>
        Back
    </button>

    <!-- Messages Container -->
    <div class="messages-container">
        <div class="messages-header">
            <h2>Messages</h2>
            <div class="search-container">
                <span class="material-icons search-icon">search</span>
                <input type="text" placeholder="Search conversations..." class="search-input" id="searchMessages">
            </div>
        </div>

        <div class="messages-layout">
            <!-- Conversation List -->
            <div class="conversation-list">
                <div class="conversation-item active" onclick="loadChat(1, 'Admin - Red Force')">
                    <div class="conversation-avatar">A</div>
                    <div class="conversation-info">
                        <div class="conversation-name">Admin - Red Force</div>
                        <div class="conversation-preview">Dear Mr. Fernando, kindly note that we are assigning 2 officers tonight...</div>
                    </div>
                    <div class="conversation-meta">
                        <span class="conversation-time">2:30 PM</span>
                        <span class="unread-badge">2</span>
                    </div>
                </div>

                <div class="conversation-item" onclick="loadChat(2, 'John Silva')">
                    <div class="conversation-avatar">J</div>
                    <div class="conversation-info">
                        <div class="conversation-name">John Silva</div>
                        <div class="conversation-preview">Officer Ravindu Fernando was...</div>
                    </div>
                    <div class="conversation-meta">
                        <span class="conversation-time">1:15 PM</span>
                    </div>
                </div>

                <div class="conversation-item" onclick="loadChat(3, 'Nadi Senanayake')">
                    <div class="conversation-avatar">N</div>
                    <div class="conversation-info">
                        <div class="conversation-name">Nadi Senanayake</div>
                        <div class="conversation-preview">Officer training certificates...</div>
                    </div>
                    <div class="conversation-meta">
                        <span class="conversation-time">Yesterday</span>
                    </div>
                </div>

                <div class="conversation-item" onclick="loadChat(4, 'Nishadi Dissanayake')">
                    <div class="conversation-avatar">N</div>
                    <div class="conversation-info">
                        <div class="conversation-name">Nishadi Dissanayake</div>
                        <div class="conversation-preview">Updated shift schedules for all...</div>
                    </div>
                    <div class="conversation-meta">
                        <span class="conversation-time">2 days ago</span>
                    </div>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="chat-area">
                <div class="chat-header">
                    <div class="chat-participant">
                        <div class="participant-avatar">A</div>
                        <div class="participant-info">
                            <div class="participant-name" id="chatName">Admin - Red Force</div>
                            <div class="participant-status">Active now</div>
                        </div>
                    </div>
                </div>

                <div class="chat-messages" id="chatMessages">
                    <div class="message received">
                        <div class="message-avatar">A</div>
                        <div class="message-content">
                            <div class="message-text">Dear Mr. Fernando, kindly note that we are assigning 2 officers tonight to the Kurunegala branch for the evening shift.</div>
                            <div class="message-time">2:30 PM</div>
                        </div>
                    </div>

                    <div class="message received">
                        <div class="message-avatar">A</div>
                        <div class="message-content">
                            <div class="message-text">Please confirm if you need any additional security personnel for the weekend.</div>
                            <div class="message-time">2:31 PM</div>
                        </div>
                    </div>

                    <div class="message sent">
                        <div class="message-content">
                            <div class="message-text">Thank you for the update. The arrangement looks good.</div>
                            <div class="message-time">2:35 PM</div>
                        </div>
                    </div>
                </div>

                <div class="chat-input">
                    <input type="text" placeholder="Type a message..." id="messageInput" onkeypress="handleKeyPress(event)">
                    <button class="primary-btn send-btn" onclick="sendMessage()">
                        <span class="material-icons">send</span>
                        Send
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo URL_ROOT; ?>/js/client/dashboard/messages.js"></script>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
