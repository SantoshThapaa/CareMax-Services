<?php
// Allow CORS for local development
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    die(json_encode(["error" => "Method Not Allowed"]));
}

// Define recipient details
define("RECIPIENT_NAME", "CareMax Services");
define("RECIPIENT_EMAIL", "dipeshpoudel98698@gmail.com");

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Read form values
$name = isset($_POST['name']) ? sanitize_input($_POST['name']) : "";
$fname = isset($_POST['fname']) ? sanitize_input($_POST['fname']) : "";
$lname = isset($_POST['lname']) ? sanitize_input($_POST['lname']) : "";
$senderEmail = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : "";
$phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : "";
$services = isset($_POST['services']) ? sanitize_input($_POST['services']) : "";
$date = isset($_POST['date']) ? sanitize_input($_POST['date']) : "";
$time = isset($_POST['time']) ? sanitize_input($_POST['time']) : "";
$subject = isset($_POST['subject']) ? sanitize_input($_POST['subject']) : "";
$website = isset($_POST['website']) ? sanitize_input($_POST['website']) : "";
$message = isset($_POST['message']) ? sanitize_input($_POST['message']) : "";

// Determine full name
$name = !empty($name) ? $name : trim($fname . ' ' . $lname);

// Validate required fields
if (empty($name) || empty($senderEmail) || !filter_var($senderEmail, FILTER_VALIDATE_EMAIL) || empty($message)) {
    http_response_code(400);
    die(json_encode(["error" => "Invalid input. Please check the required fields."]));
}

// Email subject
$mail_subject = 'Contact Request from ' . $name;

// Construct email body
$body = "Name: $name\r\n";
$body .= "Email: $senderEmail\r\n";
if (!empty($phone)) $body .= "Phone: $phone\r\n";
if (!empty($services)) $body .= "Services: $services\r\n";
if (!empty($date)) $body .= "Date: $date\r\n";
if (!empty($time)) $body .= "Time: $time\r\n";
if (!empty($subject)) $body .= "Subject: $subject\r\n";
if (!empty($website)) $body .= "Website: $website\r\n";
$body .= "Message:\r\n$message";

// Set recipient
$recipient = RECIPIENT_NAME . " <" . RECIPIENT_EMAIL . ">";

// Email headers
$headers = "From: $name <$senderEmail>\r\n";
$headers .= "Reply-To: $senderEmail\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Attempt to send email
if (mail($recipient, $mail_subject, $body, $headers)) {
    http_response_code(200);
    echo json_encode(["success" => "Thanks for contacting us. We will get back to you ASAP."]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to send email. Please try again later."]);
}
?>
