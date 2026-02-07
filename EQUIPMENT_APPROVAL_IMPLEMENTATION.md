# Equipment Approval Workflow Implementation

## Overview
Multi-level equipment approval system for caretaker equipment requests that require both supervisor and client approval.

## Workflow
1. **Caretaker** submits equipment request
2. **Supervisor** reviews and approves/rejects
3. If supervisor approves, request goes to **Client** for final approval
4. **Client** makes final approval decision

## Database Schema

### equipment_requests Table
```sql
ALTER TABLE equipment_requests 
MODIFY COLUMN status ENUM('Pending','Approved','Rejected','Supervisor Approved','Client Approved','Completed');

-- New columns added:
supervisor_approved_by INT(11) NULL
supervisor_approved_date DATETIME NULL
supervisor_notes TEXT NULL
client_approved_by INT(11) NULL
client_approved_date DATETIME NULL
client_notes TEXT NULL
rejection_reason TEXT NULL
```

## Supervisor Implementation

### Files Created/Modified

#### 1. View: `/app/views/supervisor/equipmentApprovals/v_equipment_approvals.php`
**Purpose:** Supervisor interface for reviewing equipment requests from caretakers

**Features:**
- Statistics dashboard showing:
  - Pending requests count
  - Approved requests count
  - Rejected requests count
- Equipment requests table with:
  - Caretaker information (name, phone)
  - Site details
  - Equipment type and quantity
  - Priority badges (Critical, High, Medium, Low)
  - Request dates
  - Action buttons (Approve/Reject)
- Modal-based approval workflow with optional notes field
- Modal-based rejection workflow with required rejection reason

**Actions:**
- `POST /supervisor/approveEquipmentRequest` - Approve request
- `POST /supervisor/rejectEquipmentRequest` - Reject request

#### 2. Controller: `/app/controllers/Supervisor.php`
**Methods Added:**

**equipmentApprovals()**
- Displays equipment approval interface
- Fetches pending requests for supervisor's assigned sites
- Retrieves approval statistics

**approveEquipmentRequest()**
- Updates request status to 'Supervisor Approved'
- Records supervisor ID and approval timestamp
- Saves optional supervisor notes
- Sends notification to client for final approval

**rejectEquipmentRequest()**
- Updates request status to 'Rejected'
- Records rejection reason and timestamp
- Sends notification to caretaker

#### 3. Model: `/app/models/M_supervisor.php`
**Methods Added:**

**getPendingEquipmentRequests($supervisor_id)**
- Fetches all pending equipment requests from caretakers at supervisor's assigned sites
- Joins with caretaker_site_assignments, sites, Users tables
- Orders by priority (Critical > High > Medium > Low) and creation date

**getEquipmentApprovalStats($supervisor_id)**
- Returns counts for pending, approved, and rejected requests
- Filters by supervisor's assigned sites

**getEquipmentRequestById($request_id)**
- Fetches detailed request information including:
  - Request details
  - Caretaker information
  - Site information
  - Client information

**approveEquipmentRequest($request_id, $supervisor_id, $notes)**
- Updates request status to 'Supervisor Approved'
- Records supervisor_approved_by, supervisor_approved_date, supervisor_notes
- Only updates requests with status 'Pending'

**rejectEquipmentRequest($request_id, $supervisor_id, $reason)**
- Updates request status to 'Rejected'
- Records supervisor_approved_by, supervisor_approved_date, rejection_reason
- Only updates requests with status 'Pending'

## Notification System Integration

### Supervisor Approves Request
- **Recipient:** Client (site owner)
- **Type:** Info
- **Title:** "Equipment Request Approved by Supervisor"
- **Message:** "Equipment request for {equipment_type} from {caretaker_name} has been approved by supervisor and needs your approval."
- **Link:** `/client/equipmentApprovals`

### Supervisor Rejects Request
- **Recipient:** Caretaker (requester)
- **Type:** Warning
- **Title:** "Equipment Request Rejected"
- **Message:** "Your equipment request for {equipment_type} has been rejected by supervisor. Reason: {rejection_reason}"
- **Link:** `/caretaker/equipment`

## Status Flow

```
Pending (Caretaker submits)
   ↓
Supervisor Reviews
   ↓
   ├─→ Rejected (Supervisor rejects) → End
   └─→ Supervisor Approved (Supervisor approves)
          ↓
       Client Reviews
          ↓
          ├─→ Rejected (Client rejects) → End
          └─→ Client Approved (Client approves) → Completed
```

## Access Control
- Supervisors can only see requests from caretakers at their assigned sites
- Supervisors can only approve/reject requests with status 'Pending'
- Authorization checks in all controller methods

## Next Steps - Client Implementation

### TODO: Client Equipment Approval View
Create `/app/views/client/equipmentApprovals/v_equipment_approvals.php` with:
- View supervisor-approved requests
- Approve/reject functionality for final decision
- View supervisor notes
- Add client notes

### TODO: Client Controller Methods
Add to `/app/controllers/Client.php`:
- `equipmentApprovals()` - Display view
- `approveEquipmentRequest()` - Final approval (status → 'Client Approved')
- `rejectEquipmentRequest()` - Final rejection

### TODO: Client Model Methods
Add to `/app/models/M_client.php`:
- `getSupervisorApprovedRequests($client_id)` - Fetch requests needing client approval
- `getEquipmentApprovalStats($client_id)` - Stats for client dashboard
- `approveEquipmentRequest($request_id, $client_id, $notes)` - Update to 'Client Approved'
- `rejectEquipmentRequest($request_id, $client_id, $reason)` - Final rejection

## Testing Checklist

### Supervisor Functionality
- [ ] Supervisor can view pending equipment requests from their site's caretakers
- [ ] Statistics display correct counts
- [ ] Priority badges display correctly
- [ ] Approve modal opens with request details
- [ ] Reject modal opens with request details
- [ ] Approve action updates database and sends notification to client
- [ ] Reject action updates database and sends notification to caretaker
- [ ] Only pending requests can be approved/rejected
- [ ] Supervisor cannot see requests from other sites

### Database Integrity
- [ ] Status transitions are correct
- [ ] Timestamps are recorded
- [ ] Approval chain is tracked (supervisor → client)
- [ ] Notes and reasons are saved properly

### Notifications
- [ ] Client receives notification when supervisor approves
- [ ] Caretaker receives notification when supervisor rejects
- [ ] Notification links work correctly

## Implementation Date
December 2024

## Status
✅ Supervisor approval interface - COMPLETE
✅ Supervisor backend logic - COMPLETE  
⏳ Client approval interface - PENDING
⏳ Client backend logic - PENDING
