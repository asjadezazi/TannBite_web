<?php
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

function sanitize_text(string $value): string
{
    $value = trim($value);
    $value = strip_tags($value);
    return preg_replace('/\s+/', ' ', $value) ?? '';
}

$honeypot = trim((string)($_POST['company'] ?? ''));
if ($honeypot !== '') {
    header('Location: /thank-you', true, 303);
    exit;
}

$name = sanitize_text((string)($_POST['name'] ?? ''));
$restaurant = sanitize_text((string)($_POST['restaurant'] ?? ''));
$email = filter_var(trim((string)($_POST['email'] ?? '')), FILTER_SANITIZE_EMAIL);
$phone = sanitize_text((string)($_POST['phone'] ?? ''));
$city = sanitize_text((string)($_POST['city'] ?? ''));
$message = sanitize_text((string)($_POST['message'] ?? ''));

$errors = [];
if ($name === '') {
    $errors[] = 'Name is required.';
}
if ($restaurant === '') {
    $errors[] = 'Restaurant name is required.';
}
if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $errors[] = 'A valid email is required.';
}
if ($phone === '') {
    $errors[] = 'Phone number is required.';
}

if (!empty($errors)) {
    http_response_code(422);
    echo '<h1>Submission failed</h1><p>Please go back and complete the required fields.</p>';
    exit;
}

$autoloadPath = __DIR__ . '/vendor/autoload.php';
$phpmailerCore = __DIR__ . '/lib/PHPMailer/PHPMailer.php';

if (is_file($autoloadPath)) {
    require $autoloadPath;
} elseif (is_file($phpmailerCore)) {
    require_once __DIR__ . '/lib/PHPMailer/Exception.php';
    require_once __DIR__ . '/lib/PHPMailer/PHPMailer.php';
    require_once __DIR__ . '/lib/PHPMailer/SMTP.php';
} else {
    error_log('submit-form.php: PHPMailer missing (install Composer deps or keep lib/PHPMailer/).');
    http_response_code(500);
    echo '<h1>Temporary issue</h1><p>Form is temporarily unavailable. Please try again shortly.</p>';
    exit;
}

$smtpCfg = [];
$smtpConfigPath = __DIR__ . '/smtp-config.php';
if (is_file($smtpConfigPath)) {
    $loaded = require $smtpConfigPath;
    if (is_array($loaded)) {
        $smtpCfg = $loaded;
    }
}

$smtpHost = getenv('SMTP_HOST') ?: ($smtpCfg['host'] ?? 'smtp.hostinger.com');
$smtpPort = (int)(getenv('SMTP_PORT') ?: ($smtpCfg['port'] ?? 465));
$smtpUser = getenv('SMTP_USERNAME') ?: ($smtpCfg['username'] ?? 'contact@tapnbite.com');
$smtpPass = getenv('SMTP_PASSWORD') ?: ($smtpCfg['password'] ?? '');
$smtpEncryption = getenv('SMTP_ENCRYPTION') ?: ($smtpCfg['encryption'] ?? \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS);

if ($smtpPass === '' || $smtpPass === 'YOUR_MAILBOX_PASSWORD') {
    error_log('submit-form.php: Set SMTP credentials (env SMTP_PASSWORD or smtp-config.php).');
    http_response_code(500);
    echo '<h1>Form not configured</h1><p>Please add <code>smtp-config.php</code> on the server (copy from <code>smtp-config.example.php</code>) with your Hostinger mailbox password, or set the <code>SMTP_PASSWORD</code> environment variable.</p>';
    exit;
}

try {
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = $smtpHost;
    $mail->Port = $smtpPort;
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUser;
    $mail->Password = $smtpPass;
    $mail->SMTPSecure = $smtpEncryption;

    $mail->setFrom($smtpUser, 'Tap N Bite Website');
    $mail->addAddress('contact@tapnbite.com', 'Tap N Bite');
    $mail->addReplyTo($email, $name);

    $subjectRestaurant = $restaurant !== '' ? $restaurant : 'Unknown Restaurant';
    $mail->Subject = 'New website lead: ' . $subjectRestaurant;

    $bodyLines = [
        'New lead submitted from tapnbite.com:',
        '',
        'Name: ' . $name,
        'Restaurant: ' . $restaurant,
        'Email: ' . $email,
        'Phone: ' . $phone,
        'City: ' . ($city !== '' ? $city : '-'),
        'Message: ' . ($message !== '' ? $message : '-'),
        '',
        'Submitted at: ' . gmdate('Y-m-d H:i:s') . ' UTC',
        'IP: ' . ((string)($_SERVER['REMOTE_ADDR'] ?? 'unknown')),
    ];
    $mail->Body = implode("\n", $bodyLines);
    $mail->AltBody = $mail->Body;

    $mail->send();

    header('Location: /thank-you', true, 303);
    exit;
} catch (\Throwable $e) {
    error_log('submit-form.php mail error: ' . $e->getMessage());
    http_response_code(500);
    echo '<h1>Temporary issue</h1><p>We could not submit your request right now. Please try again shortly.</p>';
    exit;
}
