#!/bin/bash
# Quick check script for package_requests payment status
# Usage: ./check_package_payments.sh

echo "======================================"
echo "Package Requests Payment Status Check"
echo "======================================"
echo ""

# Check if columns exist
echo "1. Checking if payment columns exist..."
/opt/lampp/bin/mysql -u root -e "DESCRIBE redforce_db.package_requests" 2>/dev/null | grep -E "payment_status|payment_id" 
if [ $? -eq 0 ]; then
    echo "✓ Payment columns exist"
else
    echo "✗ Payment columns NOT found!"
fi

echo ""
echo "2. Package requests with payment status:"
echo "----------------------------------------"
/opt/lampp/bin/mysql -u root -e "
SELECT 
    pr.id,
    pr.package_name,
    pr.site_name,
    pr.status,
    pr.payment_status,
    pr.payment_id,
    COALESCE(p.transaction_reference, 'N/A') as payhere_transaction_id,
    pr.package_price,
    DATE_FORMAT(pr.submitted_date, '%Y-%m-%d %H:%i') as submitted,
    DATE_FORMAT(pr.updated_at, '%Y-%m-%d %H:%i') as last_updated
FROM redforce_db.package_requests pr
LEFT JOIN redforce_db.payments p ON pr.payment_id = p.id
ORDER BY pr.id DESC
LIMIT 10;
" 2>/dev/null

echo ""
echo "3. Summary by payment status:"
echo "-----------------------------"
/opt/lampp/bin/mysql -u root -e "
SELECT 
    payment_status,
    COUNT(*) as count,
    SUM(package_price) as total_amount
FROM redforce_db.package_requests
GROUP BY payment_status;
" 2>/dev/null

echo ""
echo "4. Recent error logs (last 20 payment-related entries):"
echo "--------------------------------------------------------"
if [ -f /opt/lampp/logs/php_error_log ]; then
    grep "PayHere Notify" /opt/lampp/logs/php_error_log | tail -20
else
    echo "Log file not found at /opt/lampp/logs/php_error_log"
fi

echo ""
echo "======================================"
echo "To watch logs in real-time, run:"
echo "  tail -f /opt/lampp/logs/php_error_log | grep 'PayHere'"
echo "======================================"
