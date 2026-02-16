#!/bin/bash

# Get the latest pending order from payments table
ORDER_INFO=$(sudo /opt/lampp/bin/mysql -u root redforce_db -N -e "
    SELECT DISTINCT SUBSTRING_INDEX(transaction_reference, '-', 1) as order_id, 
           SUM(amount) as total
    FROM payments 
    WHERE status = 'pending' 
      AND transaction_reference LIKE 'ORD_%'
    GROUP BY SUBSTRING_INDEX(transaction_reference, '-', 1)
    ORDER BY created_at DESC 
    LIMIT 1
")

if [ -z "$ORDER_INFO" ]; then
    echo "No pending payments found in payments table"
    exit 1
fi

ORDER_ID=$(echo $ORDER_INFO | awk '{print $1}')
AMOUNT=$(echo $ORDER_INFO | awk '{print $2}')

echo "Testing payment for Order: $ORDER_ID"
echo "Amount: LKR $AMOUNT"
echo ""

# PayHere credentials
MERCHANT_ID="1233889"
MERCHANT_SECRET="MTU5OTAxODE4MTE4NDM2NjgwMzExOTQxODIzMzYzNjUxMDkyOTA4"
CURRENCY="LKR"
STATUS_CODE="2"
PAYMENT_ID="TEST_$(date +%s)"

# Format amount
PAYHERE_AMOUNT=$(printf "%.2f" $AMOUNT)

# Generate MD5 signature
MERCHANT_SECRET_MD5=$(echo -n "$MERCHANT_SECRET" | md5sum | awk '{print toupper($1)}')
MD5_STRING="${MERCHANT_ID}${ORDER_ID}${PAYHERE_AMOUNT}${CURRENCY}${STATUS_CODE}${MERCHANT_SECRET_MD5}"
MD5SIG=$(echo -n "$MD5_STRING" | md5sum | awk '{print toupper($1)}')

echo "Sending notify request..."
echo "POST Data:"
echo "  merchant_id: $MERCHANT_ID"
echo "  order_id: $ORDER_ID"
echo "  payhere_amount: $PAYHERE_AMOUNT"
echo "  payhere_currency: $CURRENCY"
echo "  status_code: $STATUS_CODE"
echo "  payment_id: $PAYMENT_ID"
echo "  md5sig: $MD5SIG"
echo ""

# Send POST request
RESPONSE=$(curl -s -w "\nHTTP_CODE:%{http_code}" -X POST http://localhost/RedForce/payment/notify \
  -d "merchant_id=$MERCHANT_ID" \
  -d "order_id=$ORDER_ID" \
  -d "payhere_amount=$PAYHERE_AMOUNT" \
  -d "payhere_currency=$CURRENCY" \
  -d "status_code=$STATUS_CODE" \
  -d "md5sig=$MD5SIG" \
  -d "payment_id=$PAYMENT_ID")

HTTP_CODE=$(echo "$RESPONSE" | grep "HTTP_CODE" | cut -d':' -f2)
BODY=$(echo "$RESPONSE" | sed '/HTTP_CODE/d')

echo "Response Code: $HTTP_CODE"
echo "Response Body: $BODY"
echo ""

echo "Checking all payments for this order..."
sudo /opt/lampp/bin/mysql -u root redforce_db -e "SELECT id, invoice_number, site_id, package_request_id, amount, status, transaction_reference, created_at FROM payments WHERE transaction_reference LIKE '${ORDER_ID}%' ORDER BY created_at DESC;"

echo ""
echo "Summary by type..."
sudo /opt/lampp/bin/mysql -u root redforce_db -e "
SELECT 
    CASE 
        WHEN package_request_id IS NOT NULL THEN 'Package Request'
        ELSE 'Site Payment'
    END as payment_type,
    COUNT(*) as count,
    SUM(amount) as total_amount,
    status
FROM payments 
WHERE transaction_reference LIKE '${ORDER_ID}%'
GROUP BY payment_type, status
ORDER BY payment_type, status;"

echo ""
echo "Checking error log..."
sudo tail -30 /opt/lampp/logs/error_log | grep -i "payhere\|payment" || echo "No recent payment logs found"
