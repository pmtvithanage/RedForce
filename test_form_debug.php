<?php
// Simple debug script to test form submission
echo "<h2>Form Submission Debug</h2>";
echo "<pre>";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "=== POST Data ===\n";
    print_r($_POST);
    
    echo "\n\n=== FILES Data ===\n";
    print_r($_FILES);
    
    echo "\n\n=== Missing Fields Check ===\n";
    $required = ['company_name', 'legal_company_name', 'company_type', 'business_registration_number', 
                 'registered_address', 'email', 'phone_number', 'contact_person_name'];
    
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            echo "MISSING: $field\n";
        } else {
            echo "OK: $field = " . $_POST[$field] . "\n";
        }
    }
    
    echo "\n\n=== File Upload Check ===\n";
    if (empty($_FILES['image']['name'])) {
        echo "MISSING: Company logo\n";
    } else {
        echo "Logo: " . $_FILES['image']['name'] . " (" . $_FILES['image']['size'] . " bytes)\n";
    }
    
    if (empty($_FILES['business_document']['name'])) {
        echo "MISSING: Business document\n";
    } else {
        echo "Business Doc: " . $_FILES['business_document']['name'] . " (" . $_FILES['business_document']['size'] . " bytes)\n";
    }
} else {
    echo "No form submitted yet. Submit the form to see debug info.\n";
}

echo "</pre>";

echo '<br><a href="/RedForce/home/service">Back to Service Form</a>';
?>
