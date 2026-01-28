# Last Seen & Message Seen Features Implementation

## Database Changes

### Users Table
Added columns for online status tracking:
- `is_online` (TINYINT, default 0) - Indicates if user is currently online
- `last_seen` (DATETIME, NULL) - Timestamp of user's last activity
- Indexes added on both columns for query performance

### Messages Table
Added columns for message read tracking:
- `read_at` (DATETIME, NULL) - Timestamp when message was read/seen
- Already has `is_read` (TINYINT) for basic read status
- Index added on `read_at` for performance

## Features Implemented

### 1. Last Seen Status
**User Online Tracking:**
- Users are marked as online (`is_online = 1`) when they access any page
- `last_seen` timestamp is updated on every page load (via bootloader.php)
- Heartbeat updates every 30 seconds to keep online status fresh
- Users are marked offline when they leave the messaging page

**Display:**
- Shows "Online" in green for currently online users
- Shows "Last seen X mins/hours/days ago" for offline users
- Displayed below recipient's name in chat header
- Auto-refreshes every 3 seconds during active conversation

### 2. Message Seen Status
**For Sent Messages (Your Own):**
- ✓ **Sent** - Single checkmark, message delivered to server
- ✓✓ **Delivered** - Double checkmark (gray), message marked as read
- ✓✓ **Seen [time]** - Double checkmark (blue), shows when recipient viewed it

**Tracking:**
- `is_read` flag set to 1 when recipient opens conversation
- `read_at` timestamp recorded at same time
- Updates in real-time (3-second refresh interval)

**Display:**
- Shown below each sent message with appropriate icon
- Color-coded: gray for delivered, blue for seen
- Shows relative time for seen messages
- Not shown for deleted messages

### 3. Real-time Updates
- Message list refreshes every 3 seconds
- Conversation list refreshes every 5 seconds
- Online status checks every 3 seconds during active chat
- Heartbeat keeps user online every 30 seconds

## Backend Implementation

### Models (M_message.php)
- `markAsRead()` - Updated to set `read_at = NOW()`
- `getMessages()` - Returns `read_at` field

### Models (M_users.php)
- `getUserOnlineStatus()` - Gets is_online and last_seen
- `updateLastSeen()` - Sets last_seen to NOW and is_online = 1
- `setUserOffline()` - Sets is_online = 0

### Controllers (Admin.php, MobileRider.php)
- `getUserStatus()` - Returns online status via AJAX
- `updateLastSeen()` - Heartbeat endpoint
- `setOffline()` - Called when user leaves page

### Bootloader (bootloader.php)
- Automatically updates last_seen on every page load
- Sets is_online = 1 for logged-in users

## Frontend Implementation

### JavaScript (messages.php component)
- `checkUserOnlineStatus()` - Fetches online status
- `updateUserStatus()` - Displays status with smart formatting
- Heartbeat interval (30s) for keeping user online
- BeforeUnload handler to set offline on page exit
- Enhanced `displayMessages()` to show seen status

### CSS (messages.css)
- `.message-seen` - Styles for seen status indicators
- Responsive icons and text alignment

## User Experience

### Seen by Sender:
1. Message appears with ✓ "Sent" immediately
2. Changes to ✓✓ "Delivered" when recipient opens conversation
3. Updates to ✓✓ "Seen [time]" when message is viewed

### Online Status:
1. "Online" in green if user is active (< 30s since last activity)
2. "Last seen X ago" if user was recently active
3. Updates automatically without refresh

## Security & Privacy
- Users can only see read status for their own sent messages
- Last seen updates only for logged-in users
- All status queries validate user authentication
- Soft delete preserves seen status history

## SQL Migration Files
- `/dev/add_seen_features.sql` - Adds all required columns and indexes
- Execute with: `mysql -u root -P 3308 -h 127.0.0.1 redforce_db < dev/add_seen_features.sql`
