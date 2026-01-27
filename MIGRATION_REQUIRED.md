# Database Migration Required

## To enable Supervisor Assignment Feature

You need to run this SQL command in phpMyAdmin or your MySQL client:

```sql
ALTER TABLE officer_site_assignments 
MODIFY COLUMN shift_type ENUM('Day', 'Night', 'Full Time', 'Flexible', 'Supervisor') DEFAULT 'Full Time';
```

## Steps:
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Select the `redforce_db` database
3. Click on the "SQL" tab
4. Paste the above SQL command
5. Click "Go"

After running this, you'll be able to assign supervisors to sites.
