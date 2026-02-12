# Payments System Implementation

## Overview
Complete payment history and management system for clients to view, track, and manage their payment transactions.

## Files Created/Modified

### 1. View File
- **File**: `/app/views/client/history/v_payments.php`
- **Description**: Modern, responsive payments dashboard with statistics and transaction history
- **Features**:
  - Statistics cards showing total paid, paid bills, pending, and overdue counts
  - Searchable and filterable payments table
  - Status badges (Paid, Pending, Overdue)
  - View and download receipt actions
  - Real-time filtering by status and month

### 2. Controller
- **File**: `/app/controllers/Client.php`
- **Methods Added**:
  - `payments()` - Display payment history and statistics
  - `viewPayment($payment_id)` - View individual payment details
  - `downloadReceipt($payment_id)` - Download payment receipt (PDF)

### 3. Model
- **File**: `/app/models/M_payment.php`
- **Methods**:
  - `getClientPayments($client_id)` - Get all payments for a client
  - `getPaymentDetails($payment_id, $client_id)` - Get specific payment details
  - `getPaymentStats($client_id)` - Get payment statistics
  - `getPaymentsByStatus($client_id, $status)` - Filter by status
  - `getSitePayments($site_id, $client_id)` - Get payments for a site
  - `createPayment($data)` - Create new payment record
  - `updatePaymentStatus($payment_id, $status)` - Update payment status
  - `recordPaymentTransaction($payment_id, $data)` - Record payment transaction
  - `getOverduePayments($client_id)` - Get overdue payments
  - `getMonthlyPaymentTotal($client_id, $year, $month)` - Get monthly totals

### 4. Database
- **File**: `/dev/create_payments_table.sql`
- **Table**: `payments`
- **Columns**:
  - `id` - Primary key
  - `client_id` - Foreign key to users table
  - `site_id` - Foreign key to sites table (optional)
  - `invoice_number` - Unique invoice identifier
  - `amount` - Payment amount
  - `description` - Payment description
  - `payment_date` - Date payment was made
  - `due_date` - Payment due date
  - `status` - Payment status (pending, paid, overdue, cancelled)
  - `payment_method` - Method used (bank_transfer, cash, card, online)
  - `transaction_reference` - Transaction reference number
  - `created_at` - Record creation timestamp
  - `updated_at` - Last update timestamp

### 5. Installation Script
- **File**: `/install_payments_table.php`
- **Purpose**: Automated database table creation

## Installation Steps

### Step 1: Run Database Migration
Navigate to your browser and access:
```
http://localhost/RedForce/install_payments_table.php
```

Or run manually via phpMyAdmin:
```sql
-- Execute the SQL in: /dev/create_payments_table.sql
```

### Step 2: Access the Payments Page
The payments page is automatically linked in the client sidebar:
```
http://localhost/RedForce/client/payments
```

## Features

### 1. Dashboard Statistics
- **Total Paid**: Sum of all successful payments
- **Paid Bills**: Count of completed payments
- **Pending**: Count of pending payments
- **Overdue**: Count of overdue payments

### 2. Payments Table
- Displays all payment transactions
- Columns: Invoice #, Date, Site, Description, Amount, Status, Actions
- Responsive design for mobile and tablet devices

### 3. Search & Filter
- **Search**: Filter by invoice number, site name, or description
- **Status Filter**: Filter by payment status (All, Paid, Pending, Overdue)
- **Month Filter**: Filter by payment month (January - December)
- Real-time filtering without page reload

### 4. Status Badges
- **Paid**: Green badge with checkmark icon
- **Pending**: Orange badge with pending icon
- **Overdue**: Red badge with warning icon

### 5. Actions
- **View**: View detailed payment information
- **Download Receipt**: Download PDF receipt (available for paid invoices only)

## Usage Examples

### Create a New Payment
```php
$paymentModel = new M_payment();
$data = [
    'client_id' => 3,
    'site_id' => 1,
    'invoice_number' => 'INV-2026-005',
    'amount' => 50000.00,
    'description' => 'Security Services - March 2026',
    'due_date' => '2026-03-31',
    'status' => 'pending'
];
$paymentModel->createPayment($data);
```

### Record a Payment
```php
$paymentModel->recordPaymentTransaction($payment_id, [
    'payment_date' => date('Y-m-d'),
    'payment_method' => 'bank_transfer',
    'transaction_reference' => 'TXN-XYZ789'
]);
```

### Get Payment Statistics
```php
$stats = $paymentModel->getPaymentStats($client_id);
echo "Total Paid: " . $stats->total_paid;
echo "Pending Count: " . $stats->pending_count;
```

## Customization

### Modify Status Options
Edit the enum in the database table:
```sql
ALTER TABLE payments 
MODIFY COLUMN status ENUM('pending', 'paid', 'overdue', 'cancelled', 'refunded') 
DEFAULT 'pending';
```

### Add Custom Payment Methods
Update the `payment_method` column to include your methods:
```sql
-- Already allows any varchar(50), no changes needed
```

### Customize Colors
Edit the CSS variables in `v_payments.php`:
```css
:root {
    --accent: #a40000;        /* Main accent color */
    --accent-light: #c41e1e;  /* Light accent */
    --shadow: 0 6px 18px rgba(20,20,40,0.06);
    --radius: 12px;
}
```

## Security Features

1. **Access Control**: 
   - Only authenticated clients can access payments
   - Clients can only see their own payments
   - `requireAuth('client')` enforced in controller

2. **SQL Injection Prevention**:
   - All queries use prepared statements
   - Parameter binding for all user inputs

3. **Data Validation**:
   - Client ID verification on all queries
   - Payment ownership verification before display

## Future Enhancements

### Recommended Features
1. **PDF Receipt Generation**: Implement actual PDF generation for receipts
2. **Payment Gateway Integration**: Add online payment processing (PayPal, Stripe, etc.)
3. **Email Notifications**: Send payment reminders and receipts via email
4. **Recurring Payments**: Automatic monthly bill generation
5. **Payment Analytics**: Charts and graphs for payment trends
6. **Export Options**: Export payment history to Excel/CSV
7. **Payment Disputes**: Allow clients to dispute payments
8. **Partial Payments**: Support for partial payment recording

## Troubleshooting

### Issue: "Payment not found"
- Verify payment_id exists in database
- Check that client_id matches the logged-in user
- Ensure foreign key constraints are properly set

### Issue: Statistics not showing
- Check that payments table has data
- Verify status values match enum ('paid', 'pending', 'overdue')
- Check client_id in payments matches user id

### Issue: Filters not working
- Clear browser cache
- Check JavaScript console for errors
- Verify table has tbody element with id="paymentsTable"

## Support

For issues or questions:
1. Check the database table exists: `SHOW TABLES LIKE 'payments';`
2. Verify data: `SELECT * FROM payments WHERE client_id = YOUR_CLIENT_ID;`
3. Check error logs in PHP error log file

## Version History

- **v1.0.0** (Feb 12, 2026)
  - Initial implementation
  - Basic payment history display
  - Search and filter functionality
  - Statistics dashboard
  - Payment status management
