<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_mobilerider_sidebar.php'; ?>

<?php
$notes = $data['notes'];
?>
<!-- Content will be loaded here -->

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Link to Dashboard CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/mobilerider/dashboard.css">

<!-- Dashboard Content -->

<body>
    <div class="dashboard">
        <!-- Left Column -->
        <div>
            <!-- Notifications Section -->
            <div class="section">
                <h2 class="section-title">Notifications</h2>

                <div class="notification-item">
                    <div class="avatar">S</div>
                    <div class="notification-content">
                        <div class="notification-title">System - Attendance Sync Failed</div>
                        <div class="notification-text">Your attendance data failed to sync at 9:00 AM. Please try again
                            or contact support.</div>
                    </div>
                </div>

                <div class="notification-item">
                    <div class="avatar">R</div>
                    <div class="notification-content">
                        <div class="notification-title">Reminder: Missing Check-out</div>
                        <div class="notification-text">You forgot to check out from Mobitel Office yesterday at 4:00 PM.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages Section -->
            <div class="section">
                <h2 class="section-title">Messages</h2>

                <div class="message-item">
                    <div class="avatar">A</div>
                    <div class="message-content">
                        <div class="message-title">Ashen Fernando - Premise Officer</div>
                        <div class="message-text">I was present on duty on 18th July, but my attendance shows absent.
                            Please review.</div>
                    </div>
                </div>

                <div class="message-item">
                    <div class="avatar">J</div>
                    <div class="message-content">
                        <div class="message-title">John Silva - Supervisor</div>
                        <div class="message-text">Officer Ravindu Fernando was 20 minutes late. Marked as 'Late' in the
                            system.</div>
                    </div>
                </div>

                <div class="message-item">
                    <div class="avatar">N</div>
                    <div class="message-content">
                        <div class="message-title">Nishadi Dissanayake - Admin</div>
                        <div class="message-text">Updated shift schedules for all Colombo sites effective from 6th
                            August.</div>
                    </div>
                </div>

                <div class="message-item">
                    <div class="avatar">N</div>
                    <div class="message-content">
                        <div class="message-title">Naduni Senanayake - HR Officer</div>
                        <div class="message-text">Officer training certificates for new recruits have been uploaded to
                            the system.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div>
            <!-- Quick Actions Panel -->
            <div class="section">
                <h2 class="section-title">Quick Actions Panel</h2>

                <div class="quick-actions">
                    <a href="#" class="action-btn">
                        <span class="action-icon">📷</span>
                        <div class="action-text">Report Incident</div>
                    </a>



                    <a href="#" class="action-btn">
                        <span class="action-icon">📝</span>
                        <div class="action-text">Add Notes</div>
                    </a>
                </div>
            </div>

            <!-- Route Updates Section -->
            <div class="section">
                <h2 class="section-title">Route Updates</h2>

                <div class="route-item">
                    <div class="route-location">People's Leasing HQ</div>
                    <span class="status-badge status-visited">Visited</span>
                </div>

                <div class="route-item">
                    <div class="route-location">Mobitel Office</div>
                    <span class="status-badge status-skipped">Skipped</span>
                </div>

                <div class="route-item">
                    <div class="route-location">Abans Warehouse</div>
                    <span class="status-badge status-visited">Visited</span>
                </div>

                <div class="route-item">
                    <div class="route-location">SLT Data Center</div>
                    <span class="status-badge status-next">Next</span>
                </div>

                <div class="route-item">
                    <div class="route-location">National Hospital Wing B</div>
                    <span class="status-badge status-upcoming">Upcoming</span>
                </div>

                <div class="route-item">
                    <div class="route-location">Dialog Tower</div>
                    <span class="status-badge status-upcoming">Upcoming</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes Popup Modal (Initially Hidden) -->
    <div class="notes-modal" id="notesModal" style="display: none;">
        <div class="notes-backdrop" onclick="closeNotesPopup()"></div>
        <div class="notes-popup">
            <div class="notes-header">
                <span id="modalTitle">My Notes</span>
                <button class="close-notes-btn" onclick="closeNotesPopup()">×</button>
            </div>

            <div class="notes-container">
                <!-- Notes List View -->
                <div id="notesListView">
                    <div class="notes-list-header">
                        <button class="add-note-btn" onclick="showAddNoteForm()">
                            <span class="add-icon">+</span> Add New Note
                        </button>
                    </div>

                    <div id="notesList">
                        <!-- Notes will be dynamically added here -->
                    </div>

                    <div id="emptyNotesMessage" style="display: none;">
                        <div class="empty-notes">
                            <p>No notes yet. Click "Add New Note" to get started!</p>
                        </div>
                    </div>
                </div>

                <!-- Add/Edit Note Form View -->
                <div id="noteFormView" style="display: none;">
                    <form action="<?= URL_ROOT ?>/MobileRider/addNote" method="post">
                        <input type="hidden" id="noteId" name="noteId" value="">
                        <div class="form-group">
                            <label for="noteTitle">Title:</label>
                            <input type="text" name="title" id="noteTitle" placeholder="Enter note title..." required>
                        </div>
                        <div class="form-group">
                            <label for="noteContent">Content:</label>
                            <textarea id="noteContent" name="content" rows="8" placeholder="Write your note here..."
                                required></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="save-btn">Save Note</button>
                            <button type="button" class="cancel-btn" onclick="showNotesList()">Cancel</button>
                        </div>
                    </form>
                </div>

                <!-- <div id="editNoteFormView" style="display: none;">
                    <form action="<?= URL_ROOT ?>/MobileRider/editNote" method="post">
                        <input type="hidden" id="editNoteId" name="noteId" value="">
                        <div class="form-group">
                            <label for="noteTitle">Title:</label>
                            <input type="text" name="title" id="editNoteTitle" placeholder="Enter note title..."
                                required>
                        </div>
                        <div class="form-group">
                            <label for="noteContent">Content:</label>
                            <textarea id="editNoteContent" name="content" rows="8" placeholder="Write your note here..."
                                required></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="save-btn">Save Note</button>
                            <button type="button" class="cancel-btn" onclick="showNotesList()">Cancel</button>
                        </div>
                    </form>
                </div> -->

                <!-- Individual Note View -->
                <div id="noteViewDetails" style="display: none;">
                    <div class="note-view-header">
                        <h3 id="viewNoteTitle"></h3>
                        <div class="note-actions">
                            <button class="edit-note-btn" onclick="editCurrentNote()">Edit</button>
                            <button class="delete-note-btn" onclick="deleteCurrentNote()">Delete</button>
                        </div>
                    </div>
                    <div class="note-view-content">
                        <div id="viewNoteContent"></div>
                        <div class="note-view-date" id="viewNoteDate"></div>
                    </div>
                    <button class="back-btn" onclick="showNotesList()">← Back to Notes</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sample notes data
        let notesData = <?= json_encode($notes) ?>;

        let currentNoteId = null;
        let nextNoteId = 3;

        // Function to open notes popup
        function openNotesPopup() {
            document.getElementById('notesModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
            showNotesList();
        }

        // Function to close notes popup
        function closeNotesPopup() {
            document.getElementById('notesModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            resetForm();
        }

        // Function to show notes list
        function showNotesList() {
            document.getElementById('notesListView').style.display = 'block';
            document.getElementById('noteFormView').style.display = 'none';
            document.getElementById('noteViewDetails').style.display = 'none';
            document.getElementById('modalTitle').textContent = 'My Notes';
            renderNotesList();
        }

        // Function to render notes list
        function renderNotesList() {
            const notesList = document.getElementById('notesList');
            const emptyMessage = document.getElementById('emptyNotesMessage');

            if (notesData.length === 0) {
                notesList.style.display = 'none';
                emptyMessage.style.display = 'block';
                return;
            }

            notesList.style.display = 'block';
            emptyMessage.style.display = 'none';

            notesList.innerHTML = notesData.map(note => {
                const date = new Date(note.date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                const preview = note.content.length > 100 ?
                    note.content.substring(0, 100) + '...' :
                    note.content;

                return `
                    <div class="note-item" onclick="viewNote(Number(${note.id}))">
                        <div class="note-item-title">${note.title}</div>
                        <div class="note-item-preview">${preview}</div>
                        <div class="note-item-date">${note.created_at}</div>
                    </div>
                `;
            }).join('');
        }

        // Function to view a specific note
        function viewNote(noteId) {
            const note = notesData.find(n => n.id == noteId);
            if (!note) return;

            currentNoteId = noteId;

            document.getElementById('notesListView').style.display = 'none';
            document.getElementById('noteFormView').style.display = 'none';
            document.getElementById('noteViewDetails').style.display = 'block';
            document.getElementById('modalTitle').textContent = 'View Note';

            document.getElementById('viewNoteTitle').textContent = note.title;
            document.getElementById('viewNoteContent').textContent = note.content;

            const date = new Date(note.created_at).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('viewNoteDate').textContent = `Created: ${date}`;
        }

        // Function to show add note form
        function showAddNoteForm() {
            currentNoteId = null;
            document.getElementById('notesListView').style.display = 'none';
            document.getElementById('noteViewDetails').style.display = 'none';
            document.getElementById('noteFormView').style.display = 'block';
            document.getElementById('modalTitle').textContent = 'Add New Note';
            resetForm();
        }

        // Function to edit current note
        function editCurrentNote() {
            if (!currentNoteId) return;

            const note = notesData.find(n => n.id == currentNoteId);
            if (!note) return;

            document.getElementById('noteViewDetails').style.display = 'none';
            document.getElementById('notesListView').style.display = 'none';
            document.getElementById('noteFormView').style.display = 'block';
            document.getElementById('modalTitle').textContent = 'Edit Note';

            document.getElementById('noteId').value = note.id;
            document.getElementById('noteTitle').value = note.title;
            document.getElementById('noteContent').value = note.content;
        }

        // function saveNote(e) {
        //     e.preventDefault();

        //     const noteId = document.getElementById('noteId').value;
        //     const title = document.getElementById('noteTitle').value.trim();
        //     const content = document.getElementById('noteContent').value.trim();

        //     if (!title || !content) {
        //         alert('Please fill in both title and content.');
        //         return;
        //     }

        //     if (noteId) {
        //         // Edit existing note
        //         const noteIndex = notesData.findIndex(n => n.id == noteId);
        //         if (noteIndex !== -1) {
        //             notesData[noteIndex].title = title;
        //             notesData[noteIndex].content = content;
        //             notesData[noteIndex].updated_at = new Date().toISOString();
        //         }

        //         // Send AJAX to backend to update database
        //         fetch(`<?= URL_ROOT ?>/MobileRider/editNote`, {
        //                 method: 'POST',
        //                 headers: {
        //                     'Content-Type': 'application/json'
        //                 },
        //                 body: JSON.stringify({
        //                     id: noteId,
        //                     title,
        //                     content
        //                 })
        //             })
        //             .then(res => res.json())
        //             .then(data => {
        //                 console.log('Note updated on backend', data);
        //                 // Show updated note in modal
        //                 viewNote(noteId);
        //             });

        //     } else {
        //         // Add new note
        //         const newNote = {
        //             id: nextNoteId++,
        //             title,
        //             content,
        //             created_at: new Date().toISOString()
        //         };
        //         notesData.push(newNote);

        //         // Send AJAX to backend to add new note
        //         fetch(`<?= URL_ROOT ?>/MobileRider/addNote`, {
        //             method: 'POST',
        //             headers: {
        //                 'Content-Type': 'application/json'
        //             },
        //             body: JSON.stringify({
        //                 title,
        //                 content
        //             })
        //         }).then(res => res.json()).then(data => {
        //             console.log('New note added', data);
        //         });
        //     }

        //     showNotesList();
        // }




        // Function to delete current note
        function deleteCurrentNote() {
            if (!currentNoteId) return;

            if (confirm('Are you sure you want to delete this note?')) {
                currentNote = notesData.filter(n => n.id !== currentNoteId);
                //currentNoteId = currentNote.id;
                window.location.href = `<?= URL_ROOT ?>/MobileRider/deleteNote?id=${currentNoteId}`;
                showNotesList();
            }
        }

        // Function to reset form
        function resetForm() {
            const form = document.getElementById('noteForm');
            if (form) {
                form.reset();
            }
            document.getElementById('noteId').value = '';
            currentNoteId = null;
        }



        // Initialize everything when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Check URL parameters to see if notes popup should be opened
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('notes') === 'open') {
                openNotesPopup();
                // Clean up URL by removing the parameter
                const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
            }

            // Add form submit handler
            // const noteForm = document.getElementById('noteForm');
            // if (noteForm) {
            //     noteForm.addEventListener('submit', saveNote);
            // }

            // Add functionality to checkboxes (if you have any todo items)
            document.querySelectorAll('.todo-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const todoText = this.nextElementSibling;
                    if (this.checked) {
                        todoText.classList.add('todo-completed');
                    } else {
                        todoText.classList.remove('todo-completed');
                    }
                });
            });

            // Add hover effects and click handlers for toolbar buttons
            document.querySelectorAll('.toolbar-btn, .icon-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    console.log('Button clicked:', this.textContent || this.innerHTML);
                });
            });

            // Update the dashboard action buttons
            document.querySelectorAll('.action-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const actionText = this.querySelector('.action-text').textContent;

                    // Check if it's the "Report Incident" button
                    if (actionText === 'Report Incident') {
                        // Redirect to incident page with a parameter to open the modal
                        window.location.href = `<?= URL_ROOT ?>/MobileRider/incidents?report=open`;
                    }
                    // Check if it's the "Add Notes" button
                    else if (actionText === 'Add Notes') {
                        openNotesPopup();
                    } else {
                        alert(`${actionText} clicked!`);
                    }
                });
            });

            // Add click handlers for notifications and messages
            document.querySelectorAll('.notification-item, .message-item').forEach(item => {
                item.addEventListener('click', function() {
                    this.style.background = 'rgba(244, 114, 182, 0.1)';
                    setTimeout(() => {
                        this.style.background = 'rgba(252, 231, 243, 0.8)';
                    }, 200);
                });
            });

            // Close modal when pressing ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeNotesPopup();
                }
            });
        });
    </script>

</body>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>