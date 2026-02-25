<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuration
$to = 'hello@salemsteamer.com';
$subject = 'New Contact Form Submission - Salem Steamer';

// Get form data
$firstName = isset($_POST['firstName']) ? sanitize($_POST['firstName']) : '';
$lastName = isset($_POST['lastName']) ? sanitize($_POST['lastName']) : '';
$address = isset($_POST['address']) ? sanitize($_POST['address']) : '';
$phone = isset($_POST['phone']) ? sanitize($_POST['phone']) : '';
$email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
$service = isset($_POST['service']) ? sanitize($_POST['service']) : '';
$urgency = isset($_POST['urgency']) ? sanitize($_POST['urgency']) : '';
$message = isset($_POST['message']) ? sanitize($_POST['message']) : '';
$referral = isset($_POST['referral']) ? sanitize($_POST['referral']) : '';

// Validate required fields
if (empty($firstName) || empty($lastName) || empty($phone) || empty($email)) {
    echo 'error: Please fill in all required fields.';
    exit;
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'error: Please enter a valid email address.';
    exit;
}

// Build email message
$emailMessage = "New Contact Form Submission\n";
$emailMessage .= "===========================\n\n";
$emailMessage .= "Name: $firstName $lastName\n";
$emailMessage .= "Email: $email\n";
$emailMessage .= "Phone: $phone\n";

if (!empty($address)) {
    $emailMessage .= "Property Address: $address\n";
}

if (!empty($service)) {
    $serviceLabels = [
        'water-damage' => 'Water Damage Restoration',
        'mold' => 'Mold Testing & Remediation',
        'carpet' => 'Carpet & Upholstery Cleaning',
        'air-duct' => 'Air Duct & Vent Cleaning',
        'fire' => 'Fire / Smoke Damage',
        'discretion' => 'Discretion Premium Inquiry',
        'other' => 'Other / Not Sure'
    ];
    $emailMessage .= "Service Type: " . ($serviceLabels[$service] ?? $service) . "\n";
}

$urgencyLabels = [
    'emergency' => 'Emergency (Call immediately)',
    '24h' => 'Within 24 Hours',
    '3days' => 'Within 3 Days',
    'research' => 'Just researching / Planning ahead'
];
$emailMessage .= "Urgency: " . ($urgencyLabels[$urgency] ?? $urgency) . "\n";

if (!empty($message)) {
    $emailMessage .= "\nMessage:\n$message\n";
}

if (!empty($referral)) {
    $referralLabels = [
        'google' => 'Google Search',
        'referral' => 'Referral',
        'review' => 'Online Review',
        'social' => 'Social Media',
        'other' => 'Other'
    ];
    $emailMessage .= "How They Heard: " . ($referralLabels[$referral] ?? $referral) . "\n";
}

$emailMessage .= "\n---------------------------\n";
$emailMessage .= "Submitted: " . date('Y-m-d H:i:s') . "\n";

// Email headers
$headers = "From: noreply@salemsteamer.com\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Send email
if (mail($to, $subject, $emailMessage, $headers)) {
    // Also send confirmation email to customer
    $confirmSubject = 'Thank you for contacting Salem Steamer';
    $confirmMessage = "Dear $firstName,\n\n";
    $confirmMessage .= "Thank you for contacting Salem Steamer. We have received your inquiry and our team will respond within one business hour.\n\n";
    $confirmMessage .= "For urgent matters, please call us directly at 571-344-3837.\n\n";
    $confirmMessage .= "Best regards,\n";
    $confirmMessage .= "The Salem Steamer Team\n\n";
    $confirmMessage .= "---\n";
    $confirmMessage .= "Salem Steamer\n";
    $confirmMessage .= "Premium Restoration Services\n";
    $confirmMessage .= "McLean, VA\n";
    $confirmMessage .= "571-344-3837\n";
    $confirmMessage .= "hello@salemsteamer.com";
    
    $confirmHeaders = "From: Salem Steamer <hello@salemsteamer.com>\r\n";
    $confirmHeaders .= "MIME-Version: 1.0\r\n";
    $confirmHeaders .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    @mail($email, $confirmSubject, $confirmMessage, $confirmHeaders);
    
    echo 'success: Thank you for your inquiry. We will contact you within one business hour.';
} else {
    echo 'error: There was a problem sending your message. Please call us directly at 571-344-3837.';
}

// Sanitize function
function sanitize($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}
?>
