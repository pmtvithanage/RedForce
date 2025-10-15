<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/supervisor/Messages.style.css">


    <!-- Content will be loaded here -->
    <main class="main">
			<header class="main-header">
			</header>

			<section class="chat-card">
				<!-- Chat role selector (upper right) -->
				<select class="chat-role-select" aria-label="Select chat role">
					<option>Admin Panel</option>
					<option>Premise Officer</option>
					<option>Client</option>
					<option>Caretaker</option>
				</select>
				<div class="messages" id="messages">
					<div class="msg msg-in">
						<div class="bubble">
							<p>Good Morning!!</p>
							<p>Please Ensure that all security officers a site a have submitted their attendence by 9.00 AM.</p>
							<p>Also, don't forget to update the incident report If there were any issues during night shift</p>
							<p>Let me know once it's done</p>
							<p>Thank You</p>
						</div>
					</div>
					<div class="msg msg-out">
						<div class="bubble">Sure,I will take care of it.</div>
					</div>
				</div>

				<div class="composer">
					<div class="input-wrap">
						<textarea placeholder="Type Your message............"></textarea>
						<div class="row">
							<button class="attach"><i class="fas fa-paperclip"></i> Attach File</button>
							<button class="send">Send</button>
						</div>
					</div>
					
				</div>
			</section>
    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="<?php echo URL_ROOT; ?>/js/supervisor/messages.js"></script>