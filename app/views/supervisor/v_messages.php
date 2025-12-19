<?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>


<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">


<link rel="stylesheet" href="<?= URL_ROOT ?>/css/caretaker/message_style.css">

<!-- Messages Content -->
<div class="messages-content">
  <div class="messages-container">
    <div id="messagesList" class="messages-list" aria-live="polite">
      <!-- Messages will be loaded by JavaScript -->
    </div>
  </div>
  <div id="chatBackdrop" class="backdrop" hidden></div>
  <div id="chatModal" class="chat-modal" role="dialog" aria-modal="true" aria-labelledby="chatTitle" hidden>
    <div class="chat-card">
      <header class="chat-header">
        <div class="title-wrap">
          <h2 id="chatTitle">Messages</h2>
          <p class="subtitle" id="chatSubtitle">Admin - Red Force</p>
        </div>
        <button id="closeChatBtn" class="icon-btn" aria-label="Close">✕</button>
      </header>

      <div id="chatMessages" class="messages" aria-live="polite"></div>

      <footer class="composer">
        <div class="input-wrap">
          <input id="chatInput" type="text" placeholder="Type a message" autocomplete="off" />
          <button id="sendBtn" class="send" aria-label="Send">➤</button>
          <button id="micBtn" class="mic" aria-label="Voice"><span>🎤</span></button>
        </div>
      </footer>
    </div>
  </div>

  <template id="messageItemTpl">
    <button class="message-item" type="button">
      <div class="avatar" data-initials="A"></div>
      <div class="content">
        <div class="top-row">
          <span class="sender">Sender</span>
          <time class="time" datetime="">Now</time>
        </div>
        <div class="subject">Subject line</div>
        <div class="preview">Message preview...</div>
      </div>
    </button>
  </template>
</div>
</main>
</div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<script src="<?= URL_ROOT ?>/js/caretaker/messages.js"></script>