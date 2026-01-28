# Sidebar Folders and Messages Table Implementation

## Summary
Successfully created all missing folders for sidebar menu items and implemented the messages database table with full model support.

## Changes Made

### 1. Created Missing View Folders

#### Premise Officer (`/app/views/premiseofficer/`)
- ✅ `schedule/` - For schedule management views
- ✅ `leaverequests/` - For leave request views
- ✅ `profile/` - For profile management views
- ✅ `messages/` - For messaging views

#### Supervisor (`/app/views/supervisor/`)
- ✅ `messages/` - For messaging views
- ✅ `leave_requests/` - For leave request views
- ✅ `attendance/` - For attendance management views
- ✅ `profile/` - For profile management views

#### Client (`/app/views/client/`)
- ✅ `history/` - For viewing history
- ✅ `equipmentRequests/` - For equipment request views
- ✅ `profile/` - For profile management views

### 2. Messages Database Table

**File:** `/dev/create_messages_table.sql`

Created comprehensive messages table with:
- `id` - Primary key (auto-increment)
- `sender_id` - References Users.id
- `recipient_id` - References Users.id
- `message` - Message content (TEXT)
- `is_read` - Read status (BOOLEAN, default FALSE)
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

**Indexes for Performance:**
- `idx_sender` - On sender_id
- `idx_recipient` - On recipient_id
- `idx_conversation` - On (sender_id, recipient_id)
- `idx_read_status` - On is_read
- `idx_created` - On created_at

**Foreign Keys:**
- CASCADE delete on user deletion

### 3. Message Model Implementation

**File:** `/app/models/M_message.php`

Implemented complete MessageModel class with methods:

#### Core Methods
- `getConversations($user_id)` - Get all conversations with last message and unread count
- `getAllUsers($current_user_id)` - Get all users for starting new conversations
- `getMessages($user_id, $other_user_id)` - Get all messages between two users
- `sendMessage($sender_id, $recipient_id, $message)` - Send a new message
- `markAsRead($user_id, $other_user_id)` - Mark messages as read

#### Utility Methods
- `getUnreadCount($user_id)` - Get total unread message count
- `searchConversations($user_id, $search_term)` - Search conversations by name
- `deleteMessage($message_id, $user_id)` - Delete own messages
- `getMessageById($message_id)` - Get single message
- `getConversationStats($user_id, $other_user_id)` - Get conversation statistics

## Existing Files Verified

### Already Existing View Files
- Caretaker: All menu items have corresponding folders and files ✓
- Client: Dashboard, Officers, Requests views exist ✓
- Supervisor: Dashboard, Site Info, Incidents views exist ✓
- Premise Officer: Dashboard view exists ✓

### Already Existing Functionality
- All roles have working `v_messages.php` view files
- Message controllers exist in all role controllers (Admin, Client, Supervisor, PremiseOfficer, CareTaker)
- Messaging functionality is integrated throughout the application

## Installation Instructions

### 1. Create Messages Table
Run the SQL file to create the messages table:
```bash
mysql -u root -p redforce_db < /opt/lampp/htdocs/RedForce/dev/create_messages_table.sql
```

Or via PHPMyAdmin:
1. Open PHPMyAdmin
2. Select `redforce_db` database
3. Go to SQL tab
4. Copy and paste content from `/dev/create_messages_table.sql`
5. Click "Go"

### 2. Verify Folders
All necessary folders have been created. You can now add specific view files to these folders as needed.

## Next Steps

1. **Add View Files**: Create specific view files inside each new folder based on your requirements
2. **Test Messages**: Ensure the messages table works correctly with existing message views
3. **Add Controllers**: If needed, add specific controller methods for new views

## Folder Structure

```
app/views/
├── premiseofficer/
│   ├── schedule/          (NEW)
│   ├── leaverequests/     (NEW)
│   ├── profile/           (NEW)
│   ├── messages/          (NEW)
│   └── v_*.php files
├── supervisor/
│   ├── messages/          (NEW)
│   ├── leave_requests/    (NEW)
│   ├── attendance/        (NEW)
│   ├── profile/           (NEW)
│   └── v_*.php files
├── client/
│   ├── history/           (NEW)
│   ├── equipmentRequests/ (NEW)
│   ├── profile/           (NEW)
│   └── v_*.php files
└── caretaker/
    └── (all folders already existed)
```

## Database Schema

```sql
messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sender_id INT NOT NULL,
    recipient_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES Users(id),
    FOREIGN KEY (recipient_id) REFERENCES Users(id)
)
```

All tasks completed successfully! ✓
