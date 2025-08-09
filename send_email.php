<?php
// send_email.php
// Basic contact form handler that sends form submissions to stimsonpushparaj@gmail.com
// IMPORTANT:
// - Your hosting provider must allow PHP mail() for this to work.
// - For better deliverability, configure SMTP (not covered here).
// - Save this file on your server and point your form's action to this file.

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // If accessed directly, show a simple message (or you can redirect).
    http_response_code(405);
    echo 'Method Not Allowed. Please submit the form.';
    exit;
}

// Helper to get trimmed POST values
function post($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : '';
}

$name = strip_tags(post('name'));
$email = filter_var(post('email'), FILTER_SANITIZE_EMAIL);
$message = strip_tags(post('message'));

// Basic validation
$errors = [];
if (empty($name)) {
    $errors[] = 'Name is required.';
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'A valid email is required.';
}
if (empty($message)) {
    $errors[] = 'Message is required.';
}

if (!empty($errors)) {
    // Simple error display — in production you might redirect back with errors.
    http_response_code(400);
    foreach ($errors as $err) {
        echo htmlspecialchars($err) . "\n";
    }
    exit;
}

$to = 'stimsonpushparaj@gmail.com';
$subject = 'New contact form submission';
$body = "You have a new message from your website contact form:\n\n";
$body .= "Name: " . $name . "\n";
$body .= "Email: " . $email . "\n\n";
$body .= "Message:\n" . $message . "\n";

// Recommended headers
$headers = [];
$headers[] = 'From: ' . $name . ' <' . $email . '>';
$headers[] = 'Reply-To: ' . $email;
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';

$headers_string = implode("\r\n", $headers);

// Send the email
$sent = mail($to, $subject, $body, $headers_string);

if ($sent) {
    // Redirect to thank you page (adjust path as needed)
    header('Location: thankyou.html');
    exit;
} else {
    http_response_code(500);
    echo 'Failed to send message. Please try again later.';
    exit;
}
?>