<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_mobilerider_sidebar.php'; ?>


<!-- Content will be loaded here -->
<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Link to Dashboard CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/mobilerider/messages.css">

<div class="messages-container">

  <!-- Left: Chat list -->
  <div class="chat-list-panel">
    
    <input type="text" placeholder="Search" class="search">

    <div class="chat-list">
      <div class="chat-item">
        <div class="avatar"><span class="material-symbols-outlined">person</span></div>
        <div class="chat-info">
          <p class="name">Ashen Fernando <span class="role">Premise Officer</span></p>
          <p class="preview">I was present on duty on 18th July...</p>
        </div>
        <span class="time">14:32</span>
      </div>

      <div class="chat-item">
        <div class="avatar"><span class="material-symbols-outlined">person</span></div>
        <div class="chat-info">
          <p class="name">John Silva <span class="role">Supervisor</span></p>
          <p class="preview">Officer Ravindu Fernando was...</p>
        </div>
        <span class="time">12:32</span>
      </div>

      <div class="chat-item">
        <div class="avatar"><span class="material-symbols-outlined">person</span></div>
        <div class="chat-info">
          <p class="name">Naduni Senanayake <span class="role">HR Officer</span></p>
          <p class="preview">Officer training certificates...</p>
        </div>
        <span class="time">01:42</span>
      </div>

      <div class="chat-item">
        <div class="avatar"><span class="material-symbols-outlined">person</span></div>
        <div class="chat-info">
          <p class="name">Nishadi Dissanayake <span class="role">Admin</span></p>
          <p class="preview">Updated shift schedules for all...</p>
        </div>
        <span class="time">01:22</span>
      </div>
    </div>
  </div>

  <!-- Right: Chat messages -->
  <div class="chat-window">
    <div class="chat-header">Ashen Fernando</div>

    <div class="messages">
      <div class="msg sent">What do you mean?</div>
      <div class="msg received">I think the idea that things are chaning isnt good</div>
      <div class="msg sent">What do you mean?</div>
      <div class="msg received">I think the idea that things are chaning isnt good</div>
      <div class="msg sent">What do you mean?</div>
      <div class="msg received">I think the idea that things are chaning isnt good</div>
    </div>

    <div class="chat-input">
      <input type="text" placeholder="Type a message">
      <button class="sent-btn">^</button>
    </div>
  </div>

</div>


</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>