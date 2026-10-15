<?php
/**
 * Contact form handler. Emails the lead to LEADS_EMAIL and keeps a CSV copy
 * in /storage so no enquiry is lost if the server's mail() is not configured.
 * Answers JSON to fetch() requests and redirects for plain form posts.
 */

declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/functions.php';

session_start();

$wants_json = ($_SERVER['HTTP_ACCEPT'] ?? '') === 'application/json';

function respond(bool $ok, string $message, bool $json): never
{
    if ($json) {
        http_response_code($ok ? 200 : 422);
        header('Content-Type: application/json');
        echo json_encode(['ok' => $ok, 'message' => $message]);
    } else {
        header('Location: /?status=' . ($ok ? 'sent' : 'error') . '#contact', true, 303);
    }
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: /#contact', true, 303);
    exit;
}

$token = (string) ($_POST['csrf'] ?? '');
if ($token === '' || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $token)) {
    respond(false, 'Your session expired. Please refresh the page and try again.', $wants_json);
}

// Bots fill the hidden field or submit within seconds of loading the page.
$started = (int) ($_POST['ts'] ?? 0);
if (!empty($_POST['website']) || time() - $started < 3) {
    respond(true, 'Thanks — your message is on its way.', $wants_json);
}

// One message per 30 seconds per session.
if (isset($_SESSION['last_submit']) && time() - $_SESSION['last_submit'] < 30) {
    respond(false, 'Please wait a few seconds before sending another message.', $wants_json);
}

$clean = static fn(string $key, int $max): string =>
    mb_substr(trim(str_replace(["\r", "\n"], ' ', (string) ($_POST[$key] ?? ''))), 0, $max);

$name    = $clean('name', 100);
$email   = $clean('email', 150);
$phone   = $clean('phone', 30);
$country = $clean('country', 50);
$message = mb_substr(trim((string) ($_POST['message'] ?? '')), 0, 3000);
$picked  = array_map(
    static fn($s) => mb_substr(trim((string) $s), 0, 40),
    is_array($_POST['services'] ?? null) ? $_POST['services'] : []
);

if ($name === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, 'Please add your name, a valid email and a short message.', $wants_json);
}

$body = "New enquiry from the website\n\n"
    . "Name: {$name}\n"
    . "Email: {$email}\n"
    . 'Phone: ' . ($phone ?: '-') . "\n"
    . 'Country: ' . ($country ?: '-') . "\n"
    . 'Services: ' . ($picked ? implode(', ', $picked) : '-') . "\n\n"
    . "Message:\n{$message}\n";

$headers = [
    'From: ' . SITE_NAME . ' Website <no-reply@webincline.com>',
    'Reply-To: ' . $email,
    'Content-Type: text/plain; charset=UTF-8',
];
$sent = @mail(LEADS_EMAIL, 'New enquiry: ' . $name, $body, implode("\r\n", $headers));

$dir = __DIR__ . '/storage';
if (!is_dir($dir)) {
    @mkdir($dir, 0750, true);
}
$log = @fopen($dir . '/leads.csv', 'a');
if ($log) {
    fputcsv($log, [date('c'), $name, $email, $phone, $country, implode('; ', $picked), $message, $sent ? 'mailed' : 'mail-failed']);
    fclose($log);
}

$_SESSION['last_submit'] = time();

if (!$sent && !$log) {
    respond(false, 'We could not send your message. Please email us at ' . LEADS_EMAIL . '.', $wants_json);
}
respond(true, 'Thanks — your message is on its way. We will reply soon.', $wants_json);
