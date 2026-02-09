# Service Request Form Enhancement - Business Registration Information

## Overview
This update enhances the service request form to collect comprehensive business registration information required for proper client onboarding, contract generation, and invoicing.

## Database Changes

### New Columns Added to `client_requests` Table:
1. **legal_company_name** (VARCHAR 255) - Legal/Registered company name as it appears on registration documents
2. **company_type** (VARCHAR 50) - Type of business entity (LLC, Inc., PLC, Partnership, Sole Proprietor, etc.)
3. **business_registration_number** (VARCHAR 100) - Official registration number (VAT, GSTIN, EIN, Company Number, CIF/NIF, etc.)
4. **registered_address** (TEXT) - Official legal/registered business address
5. **business_document** (VARCHAR 255) - Filename of uploaded business registration/incorporation certificate

### Migration Script
✅ **Database migration completed successfully!**

The SQL script has been executed and all new columns have been added to the `client_requests` table.

To view the migration script, see:
```
/opt/lampp/htdocs/RedForce/dev/update_service_requests_table.sql
```

**Note:** If the `Clients` table also needs these fields, uncomment the relevant section in the migration script and run it again.

## File Upload Directory

### Required Directory
✅ **Upload directory created and configured successfully!**

The directory has been created with proper permissions:
```bash
/opt/lampp/htdocs/RedForce/public/uploads/businessDocuments
```

**Permissions:** drwxrwxrwx (777) - Ready for file uploads

If you need to recreate or modify permissions:
```bash
sudo mkdir -p /opt/lampp/htdocs/RedForce/public/uploads/businessDocuments
sudo chmod 777 /opt/lampp/htdocs/RedForce/public/uploads/businessDocuments
sudo chown -R daemon:daemon /opt/lampp/htdocs/RedForce/public/uploads/businessDocuments
```

### Supported Document Formats
- PDF (.pdf)
- JPEG/JPG (.jpg, .jpeg)
- PNG (.png)
- Maximum file size: 5MB (recommended to configure in controller)

## Updated Views

### 1. Service Request Form (`v_services.php`)
**New Fields:**
- Legal/Registered Company Name (required)
- Company Type dropdown (required)
- Business Registration Number (required)
- Registered Business Address (required, textarea)
- Primary Business Email Address (required)
- Primary Business Phone Number (required)
- Contact Person's Name (required)
- Business Registration Document upload (required)

**Validation Requirements:**
All fields are marked as required with `required` attribute. Backend validation should also be implemented.

### 2. Success Page (`v_success.php`)
Displays all submitted business information including:
- Link to view uploaded business document
- Properly formatted registered address (with line breaks)

### 3. Admin Request Views
Updated all three admin views:
- **Pending Requests** (`v_requests-pending.php`)
- **Approved Requests** (`v_requests-accepted.php`)
- **Rejected Requests** (`v_requests-rejected.php`)

**Display Features:**
- Company type shown below legal company name
- Business registration number with badge icon
- Registered address with location icon
- Document download link (opens in new tab)
- All labels updated to reflect business-focused terminology

## Backend Controller Updates Required

### Home Controller (`Home.php`)
Update the `service()` method to handle:

```php
// Add validation for new fields
$data['legal_company_name'] = trim($_POST['legal_company_name']);
$data['company_type'] = trim($_POST['company_type']);
$data['business_registration_number'] = trim($_POST['business_registration_number']);
$data['registered_address'] = trim($_POST['registered_address']);

// Validate new fields
if(empty($data['legal_company_name'])) {
    $data['legal_company_name_err'] = 'Please enter legal company name';
}

if(empty($data['company_type'])) {
    $data['company_type_err'] = 'Please select company type';
}

if(empty($data['business_registration_number'])) {
    $data['business_registration_number_err'] = 'Please enter business registration number';
}

if(empty($data['registered_address'])) {
    $data['registered_address_err'] = 'Please enter registered business address';
}

// Handle business document upload
if(isset($_FILES['business_document']) && $_FILES['business_document']['error'] === 0) {
    $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
    $filename = $_FILES['business_document']['name'];
    $filetype = pathinfo($filename, PATHINFO_EXTENSION);
    
    if(!in_array(strtolower($filetype), $allowed)) {
        $data['business_document_err'] = 'Only PDF, JPG, and PNG files are allowed';
    } else if($_FILES['business_document']['size'] > 5242880) { // 5MB
        $data['business_document_err'] = 'File size must not exceed 5MB';
    } else {
        $new_filename = 'business_doc_' . time() . '.' . $filetype;
        $upload_path = 'uploads/businessDocuments/' . $new_filename;
        
        if(move_uploaded_file($_FILES['business_document']['tmp_name'], $upload_path)) {
            $data['business_document'] = $new_filename;
        } else {
            $data['business_document_err'] = 'Failed to upload document';
        }
    }
} else {
    $data['business_document_err'] = 'Business registration document is required';
}
```

### Model Updates Required
Update the relevant model to include new fields in INSERT/UPDATE queries:

```php
// Add to client request insertion
$this->db->query('INSERT INTO client_requests 
    (legal_company_name, company_type, business_registration_number, 
     registered_address, email, phone_number, contact_person_name, 
     logo_path, business_document, created_at) 
    VALUES 
    (:legal_company_name, :company_type, :business_registration_number, 
     :registered_address, :email, :phone_number, :contact_person_name, 
     :logo_path, :business_document, NOW())');

// Bind new parameters
$this->db->bind(':legal_company_name', $data['legal_company_name']);
$this->db->bind(':company_type', $data['company_type']);
$this->db->bind(':business_registration_number', $data['business_registration_number']);
$this->db->bind(':registered_address', $data['registered_address']);
$this->db->bind(':business_document', $data['business_document']);
```

## Benefits of This Enhancement

1. **Legal Compliance**: Ensures all required business information is collected
2. **Contract Generation**: Company type affects contract templates and terms
3. **Invoicing**: Business registration number is critical for proper invoicing and tax compliance
4. **Verification**: Business documents allow for verification of legitimacy
5. **Communication**: Proper business addresses and contact information
6. **International Support**: Accommodates various registration number formats (VAT, EIN, GSTIN, etc.)

## Testing Checklist

- [x] Database migration executed successfully
- [x] Business documents directory created with proper permissions
- [ ] Form displays all new fields correctly
- [ ] All fields marked as required
- [ ] Company type dropdown shows all options
- [ ] File upload accepts PDF, JPG, PNG
- [ ] File upload rejects files over 5MB
- [ ] File upload rejects invalid file types
- [ ] Success page displays all information correctly
- [ ] Document link opens in new tab
- [ ] Admin pending requests show all fields
- [ ] Admin approved requests show all fields
- [ ] Admin rejected requests show all fields
- [ ] Document downloads work from admin views
- [ ] Backend validation prevents empty submissions
- [ ] Error messages display correctly for each field

## Security Considerations

1. **File Upload Validation**: 
   - Verify file types on server-side (don't trust client-side)
   - Check file size limits
   - Rename uploaded files to prevent directory traversal
   - Store files outside web root if possible

2. **Input Sanitization**:
   - Sanitize all text inputs
   - Use prepared statements for database queries
   - Escape output when displaying user data

3. **Access Control**:
   - Ensure only admins can access business documents
   - Implement proper authentication checks

## Future Enhancements

1. Add document preview functionality in admin views
2. Implement OCR to auto-extract registration numbers
3. Add validation against business registration databases (if available)
4. Support multiple document uploads
5. Add document expiry tracking for licenses
6. Implement document approval workflow

## Support

For questions or issues with this implementation, contact the development team.

---
**Last Updated**: February 7, 2026
**Version**: 1.0
