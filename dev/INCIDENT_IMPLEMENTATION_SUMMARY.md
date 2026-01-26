# Incident Reporting System - Implementation Summary

## Overview
Implemented a complete backend functionality for mobile riders to create and submit incident reports through the RedForce security management system.

## Database Updates

### Migration File Created
**File:** `/opt/lampp/htdocs/RedForce/dev/update_incident_reports_table.sql`

### New Columns Added to `incident_reports` table:
- `site_id` (BIGINT UNSIGNED NULL) - Foreign key to sites table
- `priority` (ENUM: Low, Medium, High, Critical) - Incident priority level  
- `people_involved` (TEXT NULL) - Names of people involved in incident
- `latitude` (DECIMAL 10,8 NULL) - GPS latitude coordinates
- `longitude` (DECIMAL 11,8 NULL) - GPS longitude coordinates
- `status` (ENUM: Pending, In Progress, Resolved, Closed) - Incident status

### Indexes Added:
- idx_site_id - For better query performance
- idx_priority - For filtering by priority
- idx_status - For filtering by status
- idx_incident_date - For date-based queries

### Foreign Key Constraint:
- `fk_incident_site` - Links site_id to sites(id) table

## Backend Implementation

### Model Updates (`M_mobilerider.php`)

#### 1. Updated `addIncident()` Method
- Enhanced to support all new fields from the form
- Handles both old schema (property_site, severity) and new schema (site_id, priority)
- Properly binds all parameters including:
  - Basic incident details (type, date, time, description)
  - Location data (site_id, latitude, longitude)
  - People involved
  - Priority level
  - Evidence files (media_files)
  - Status tracking

#### 2. Updated `getIncidentsByUserId()` Method
- Modified to join with sites table using new site_id field
- Returns status and priority from new fields
- Falls back to old fields (severity) for backward compatibility

#### 3. Added `logActivity()` Method
- Logs incident creation to recent_activities table
- Tracks user activities for audit trail
- Records activity type, title, and details

### Controller Updates (`MobileRider.php`)

#### Enhanced `createIncident()` Method

**GET Request Handling:**
- Retrieves assigned route and sites for the mobile rider
- Prepares form with default values
- Displays sites assigned to the rider's route

**POST Request Handling:**
1. **Input Sanitization**
   - Filters all POST data using FILTER_SANITIZE_FULL_SPECIAL_CHARS

2. **Validation**
   - Validates required fields:
     - Incident type
     - Site selection
     - Incident date
     - Incident time
     - Description
   - Sets appropriate error messages for each field

3. **File Upload Handling**
   - Supports multiple evidence file uploads
   - Creates upload directory if not exists (`uploads/evidence/`)
   - Validates file types (JPEG, JPG, PNG, GIF)
   - Validates file size (max 5MB per file)
   - Generates unique filenames to prevent conflicts
   - Stores uploaded filenames in database

4. **Data Preparation**
   - Retrieves user details (name, role)
   - Maps form fields to database columns
   - Handles GPS coordinates (latitude/longitude)
   - Sets default status as 'Pending'

5. **Database Insertion**
   - Calls `addIncident()` model method
   - Logs activity upon successful creation
   - Provides success/error feedback via flash messages

6. **Error Handling**
   - Returns validation errors to form
   - Preserves user input on validation failure
   - Displays error messages next to form fields

### View Updates (`v_create_Incident.php`)

#### Form Action Updated
- Changed from empty action to: `<?php echo URL_ROOT; ?>/MobileRider/createIncident`
- Ensures form submits to correct controller method

#### Form Features:
- Client-side validation with JavaScript
- Real-time error indication (red borders)
- Multiple file upload support with preview
- Google Maps integration for location marking
- Auto-fills location based on selected site
- Responsive design with grid layout

## File Upload System

### Directory Structure:
```
public/
  uploads/
    evidence/
      [unique_id]_[filename].jpg
      [unique_id]_[filename].png
```

### Upload Security:
- File type validation (images only)
- File size limits (5MB max)
- Unique filename generation
- Directory permissions (0777)

## Activity Logging

Each incident creation is logged to `recent_activities` table with:
- user_id: Mobile rider who created the incident
- activity_type: 'incident_report'
- activity_titel: 'New Incident Reported'
- activity_details: Brief description with incident type and site

## Integration Points

### Frontend to Backend Flow:
1. User fills incident form (`v_create_Incident.php`)
2. Form submits to `MobileRider/createIncident` (POST)
3. Controller validates input
4. Controller handles file uploads
5. Model inserts data to `incident_reports` table
6. Activity logged to `recent_activities` table
7. User redirected to incidents list with success message

### Database Relations:
```
incident_reports
├── user_id → Users(id) - Reporter
├── site_id → sites(id) - Location
└── status, priority - Tracking fields
```

## How to Deploy

### 1. Run Database Migration:
```sql
mysql -u [username] -p redforce_db < dev/update_incident_reports_table.sql
```

### 2. Create Upload Directory:
```bash
mkdir -p public/uploads/evidence
chmod 777 public/uploads/evidence
```

### 3. Verify Permissions:
```bash
chmod 644 app/models/M_mobilerider.php
chmod 644 app/controllers/MobileRider.php
chmod 644 app/views/mobilerider/incidents/v_create_Incident.php
```

## Testing Checklist

- [ ] Database migration runs without errors
- [ ] Form displays with correct sites for logged-in mobile rider
- [ ] Form validation works (required fields)
- [ ] File upload works (single and multiple files)
- [ ] GPS coordinates save correctly
- [ ] Incident appears in incidents list after creation
- [ ] Activity is logged to recent_activities table
- [ ] Success message displays after submission
- [ ] Error messages display for invalid input

## API Endpoints

### POST `/MobileRider/createIncident`
**Parameters:**
- `incident_type` (required): Type of incident
- `site_id` (required): Site where incident occurred
- `incident_date` (required): Date of incident (YYYY-MM-DD)
- `incident_time` (required): Time of incident (HH:MM)
- `priority` (required): Priority level (Low/Medium/High/Critical)
- `description` (required): Detailed description
- `actions_taken` (optional): Actions taken
- `people_involved` (optional): Names of people involved
- `evidence_files[]` (optional): Multiple image files
- `latitude` (optional): GPS latitude
- `longitude` (optional): GPS longitude

**Response:**
- Success: Redirect to incidents list with success message
- Failure: Reload form with error messages

## Notes

- The system maintains backward compatibility with old schema (property_site, severity)
- Both site_id and property_site are populated for compatibility
- Priority field is preferred over severity for new reports
- Status field defaults to 'Pending' for all new incidents
- File uploads are stored in public/uploads/evidence/ directory
- Filenames are made unique using uniqid() to prevent conflicts

## Future Enhancements

1. Add incident editing capability
2. Implement incident status workflow (Admin approval/rejection)
3. Add email notifications for new incidents
4. Generate incident reports (PDF export)
5. Add incident analytics dashboard
6. Implement incident assignment to officers
7. Add follow-up tracking system
