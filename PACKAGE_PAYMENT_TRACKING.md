# Package Request Payment Tracking - Implementation Summary

## Overview
When a payment is completed through PayHere, the `package_requests` table is automatically updated with payment status and payment ID.

## Database Schema

### package_requests table columns:
- `payment_status`: ENUM('unpaid', 'paid', 'refunded') DEFAULT 'unpaid'
- `payment_id`: INT(11) - References `payments.id` (internal payment record ID)

## Payment Flow

1. **Client submits payment** → PayHere processes payment
2. **PayHere sends notification** → `Payment::notify()` receives callback
3. **Update payments table** → Set status = 'paid', store PayHere transaction ID
4. **Update package_requests table** → Set payment_status = 'paid', payment_id = payments.id
5. **Admin approval** → Admin reviews and approves the paid request

## Error Logging

The system logs detailed information at each step:

### Log Messages You'll See:

#### 1. Payment Table Update
```
PayHere Notify - Updated payments table: payment_id=123, status=paid, payhere_transaction_id=320012345
```

#### 2. Before Package Request Update
```
PayHere Notify - BEFORE UPDATE: Updating package_request_id=45 with internal_payment_id=123, payment_status=paid
PayHere Notify - Current package_request state: ID=45, status=Pending, payment_status=unpaid, payment_id=NULL
```

#### 3. After Package Request Update
```
PayHere Notify - SUCCESS: Package request 45 updated successfully. Rows affected: 1
PayHere Notify - AFTER UPDATE: package_request state: ID=45, status=Pending, payment_status=paid, payment_id=123
```

#### 4. Verification
```
PayHere Notify - ✓ VERIFICATION PASSED: Payment data correctly saved to package_requests table (payment_id=123 links to payments.id=123)
PayHere Notify - ✓ Package request 45 payment completed successfully!
PayHere Notify - Payment details: Internal Payment ID=123, PayHere Transaction ID=320012345, Status=paid
PayHere Notify - Package request status: payment_status=paid, awaiting admin approval
```

#### 5. Summary
```
PayHere Notify - Updated 2 payment records, 1 package requests marked as paid
```

## How to Monitor Logs

### On Linux (LAMPP/XAMPP):
```bash
# Watch logs in real-time
tail -f /opt/lampp/logs/php_error_log

# Search for payment-related logs
grep "PayHere Notify" /opt/lampp/logs/php_error_log

# Search for specific package request
grep "package_request_id=45" /opt/lampp/logs/php_error_log
```

### On macOS (XAMPP):
```bash
tail -f /Applications/XAMPP/xamppfiles/logs/php_error_log
```

### On Windows (XAMPP):
```cmd
type C:\xampp\php\logs\php_error_log
```

## Verify Database Updates

### Check package request payment status:
```sql
SELECT id, client_id, package_name, status, payment_status, payment_id, updated_at 
FROM package_requests 
WHERE id = 45;
```

### Check payment record:
```sql
SELECT p.id, p.invoice_number, p.amount, p.status, p.transaction_reference,
       pr.id as package_request_id, pr.payment_status
FROM payments p
LEFT JOIN package_requests pr ON p.package_request_id = pr.id
WHERE p.id = 123;
```

### Check all paid package requests:
```sql
SELECT pr.id, pr.package_name, pr.site_name, pr.status, pr.payment_status, 
       pr.payment_id, p.transaction_reference, p.amount
FROM package_requests pr
LEFT JOIN payments p ON pr.payment_id = p.id
WHERE pr.payment_status = 'paid'
ORDER BY pr.updated_at DESC;
```

## Testing

### Test the payment flow:
1. Create a package request as a client
2. Go to payment page and initiate payment
3. Complete payment on PayHere sandbox
4. Check error logs for the detailed logging sequence
5. Verify database shows payment_status='paid' and payment_id is set

### Expected Error Log Sequence:
```
PayHere Notify Called - POST data: Array(...)
PayHere Notify - Order: ORD_xxx, Status: 2, Payment ID: 320012345, MD5 Match: YES
PayHere Notify - Payment Success for Order: ORD_xxx
PayHere Notify - Updated payments table: payment_id=123, status=paid, payhere_transaction_id=320012345
PayHere Notify - BEFORE UPDATE: Updating package_request_id=45 with internal_payment_id=123, payment_status=paid
PayHere Notify - Current package_request state: ID=45, status=Pending, payment_status=unpaid, payment_id=NULL
PayHere Notify - SUCCESS: Package request 45 updated successfully. Rows affected: 1
PayHere Notify - AFTER UPDATE: package_request state: ID=45, status=Pending, payment_status=paid, payment_id=123
PayHere Notify - ✓ VERIFICATION PASSED: Payment data correctly saved to package_requests table (payment_id=123 links to payments.id=123)
PayHere Notify - ✓ Package request 45 payment completed successfully!
PayHere Notify - Payment details: Internal Payment ID=123, PayHere Transaction ID=320012345, Status=paid
PayHere Notify - Package request status: payment_status=paid, awaiting admin approval
PayHere Notify - Updated 1 payment records, 1 package requests marked as paid
Payment processed successfully - Order: ORD_xxx
```

## Troubleshooting

### If payment_status is not updating:

1. **Check error logs** for any error messages
2. **Verify columns exist**:
   ```bash
   /opt/lampp/bin/mysql -u root -e "DESCRIBE redforce_db.package_requests" | grep payment
   ```
3. **Check PayHere notification URL** is correctly configured
4. **Verify merchant secret** matches PayHere settings

### If you see "VERIFICATION FAILED":
- Check if payment_id is being stored correctly
- Verify the data types match (INT for payment_id)
- Check database constraints

### If no logs appear:
- Verify PHP error logging is enabled
- Check `php.ini` for `error_log` setting
- Ensure PayHere notification URL is accessible

## Key Points

✅ **payment_id stores internal ID**: The `payment_id` in `package_requests` references `payments.id` (INT), NOT PayHere's payment_id
✅ **PayHere transaction ID**: Stored in `payments.transaction_reference` field
✅ **Comprehensive logging**: Every step is logged with detailed information
✅ **Automatic verification**: System verifies the update was successful
✅ **Status unchanged**: Package request `status` remains 'Pending' until admin approval
✅ **Payment status separate**: `payment_status='paid'` indicates payment completed, admin still needs to approve request

## Files Modified
- `/opt/lampp/htdocs/RedForce/app/controllers/Payment.php` - Enhanced payment notification handler with detailed logging
