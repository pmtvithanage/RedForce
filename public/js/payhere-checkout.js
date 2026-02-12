// public/js/payhere-checkout.js

function initPayHerePayment() {
    // 1. Get product details from the HTML spans
    var name = document.getElementById("product-name").innerText;
    var price = document.getElementById("product-price").innerText;

    // 2. Prepare data to send to YOUR MVC Controller
    var formData = new FormData();
    formData.append("amount", price);
    formData.append("item_name", name);

    // 3. AJAX call to YOUR backend (MVC Controller)
    fetch('/payment/generatePaymentHash', { // Adjust URL to your routing
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // 4. Define PayHere event handlers
        payhere.onCompleted = function onCompleted(orderId) {
            console.log("Payment completed. OrderID:" + orderId);
            // Redirect to success page
            window.location.href = '/payment/success';
        };

        payhere.onDismissed = function onDismissed() {
            console.log("Payment dismissed");
            alert('Payment cancelled by user');
        };

        payhere.onError = function onError(error) {
            console.log("Error:" + error);
            alert('Payment error occurred');
        };

        // 5. Payment Object (Sandbox = true)
        var payment = {
            sandbox: true, // IMPORTANT for testing
            merchant_id: data.merchant_id,
            return_url: undefined, // Use undefined for popup mode
            cancel_url: undefined, // Use undefined for popup mode
            notify_url: 'https://yourdomain.com/payment/notify', // MUST be full URL
            order_id: data.order_id,
            items: data.item_name,
            amount: data.amount,
            currency: data.currency,
            hash: data.hash, // Generated securely by your Model
            first_name: "Saman", // Replace with dynamic customer data
            last_name: "Perera",
            email: "samanp@gmail.com",
            phone: "0771234567",
            address: "No.1, Galle Road",
            city: "Colombo",
            country: "Sri Lanka",
        };

        // 6. Launch PayHere Popup
        payhere.startPayment(payment);
    })
    .catch(error => {
        console.error('Error fetching payment hash:', error);
    });
}