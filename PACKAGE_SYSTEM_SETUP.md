# Package System Setup Instructions

## Default Packages Implementation

The system now includes **4 default packages** that are protected from deletion:

1. **Custom Package** - For clients with specific requirements (0 personnel, LKR 0)
2. **Extra Security Officer** - Add one security officer (1 officer, LKR 15,000/month)
3. **Extra Supervisor** - Add one supervisor (1 supervisor, LKR 20,000/month)
4. **Extra Caretaker** - Add one caretaker (1 caretaker, LKR 12,000/month)

## Installation Steps

### 1. Run the SQL Migration

Execute the following SQL file to add the default packages and create the `is_default` column:

```bash
# Using MySQL command line
mysql -u root -p redforce_db < dev/add_default_packages.sql

# OR using LAMPP MySQL
/opt/lampp/bin/mysql -u root redforce_db < dev/add_default_packages.sql
```

Alternatively, you can manually execute the SQL file via phpMyAdmin:
1. Open phpMyAdmin
2. Select the `redforce_db` database
3. Go to the SQL tab
4. Copy and paste the contents of `dev/add_default_packages.sql`
5. Click "Go"

### 2. Verify Installation

After running the migration, verify that:
- The `packages` table has a new column `is_default`
- Four default packages have been created
- All default packages have `is_default = 1`

You can verify with this query:
```sql
SELECT id, package_name, number_of_officers, number_of_supervisors, 
       number_of_caretakers, package_price, is_default 
FROM packages 
WHERE is_default = 1;
```

## Features

### Default Package Protection

Default packages have special restrictions:

✅ **Can Edit:**
- Package price
- Package description
- Background image

❌ **Cannot Edit:**
- Package name
- Number of officers
- Number of supervisors
- Number of caretakers

❌ **Cannot Delete:**
- Default packages show a lock icon instead of delete button
- Deletion attempts are blocked at the controller level

### User Interface Changes

1. **Package List View** (`v_packages.php`):
   - Default packages show a lock icon 🔒 instead of delete button
   - Delete button is disabled and grayed out for default packages

2. **Edit Package View** (`v_edit_package.php`):
   - Package name field is readonly for default packages
   - Personnel count fields are readonly for default packages
   - Helper text indicates fields are "Fixed" or "Cannot be changed"
   - All other fields (price, description, image) remain editable

## Default Package Details

| Package Name | Officers | Supervisors | Caretakers | Price (LKR) |
|--------------|----------|-------------|------------|-------------|
| Custom Package | 0 | 0 | 0 | 0 |
| Extra Security Officer | 1 | 0 | 0 | 15,000 |
| Extra Supervisor | 0 | 1 | 0 | 20,000 |
| Extra Caretaker | 0 | 0 | 1 | 12,000 |

## Notes

- Prices are set as defaults and can be adjusted through the admin panel
- The Custom Package is designed for clients who want to specify their own requirements
- Extra packages are add-ons that clients can combine with main packages
- All default packages are marked as `Active` status
