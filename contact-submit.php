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

// Send plain form posts back to the page they came from (a local path only).
$back = is_string($_POST['back'] ?? null) ? $_POST['back'] : '/';
if (!preg_match('#^/[a-z0-9/-]*$#', $back) || str_starts_with($back, '//')) {
    $back = '/';
}

/** $status names the message a plain form post shows on its way back (see partials/contact.php). */
function respond(bool $ok, string $message, bool $json, string $status = ''): never
{
    if ($json) {
        http_response_code($ok ? 200 : 422);
        header('Content-Type: application/json');
        echo json_encode(['ok' => $ok, 'message' => $message]);
    } else {
        header('Location: ' . $GLOBALS['back'] . '?status=' . ($status ?: ($ok ? 'sent' : 'error')) . '#form-status', true, 303);
    }
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: /#contact', true, 303);
    exit;
}

$token = (string) ($_POST['csrf'] ?? '');
if ($token === '' || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $token)) {
    respond(false, 'Your session expired. Please refresh the page and try again.', $wants_json, 'expired');
}

// Bots fill the hidden field or submit within seconds of loading the page.
$started = (int) ($_POST['ts'] ?? 0);
if (!empty($_POST['website']) || time() - $started < 3) {
    respond(true, 'Thanks — your message is on its way.', $wants_json);
}

// One message per 30 seconds per session.
if (isset($_SESSION['last_submit']) && time() - $_SESSION['last_submit'] < 30) {
    respond(false, 'Please wait 30 seconds before sending another message.', $wants_json, 'wait');
}

$text  = static fn(string $key): string => is_string($_POST[$key] ?? null) ? $_POST[$key] : '';
$clean = static fn(string $key, int $max): string =>
    mb_substr(trim(str_replace(["\r", "\n"], ' ', $text($key))), 0, $max);

$name    = $clean('name', 100);
$email   = $clean('email', 150);
$phone   = $clean('phone', 30);
$country = $clean('country', 50);
$message = mb_substr(trim($text('message')), 0, 3000);
$picked  = array_map(
    static fn($s) => mb_substr(trim(is_string($s) ? $s : ''), 0, 40),
    is_array($_POST['services'] ?? null) ? $_POST['services'] : []
);

if ($name === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, 'Please add your name, a valid email and a short message.', $wants_json, 'invalid');
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
    @file_put_contents($dir . '/.htaccess', "Require all denied\n");
}
// A cell starting with = + - @ would run as a formula when the file is opened in Excel or Sheets.
$cell = static fn(string $v): string => preg_match('/^[=+\-@\t\r]/', $v) ? "'" . $v : $v;
$log = @fopen($dir . '/leads.csv', 'a');
if ($log) {
    // the phone keeps its leading + (digits and + ( ) - only, so it cannot hold a formula)
    $phone_cell = preg_replace('/[^0-9+()\- ]/', '', $phone);
    fputcsv($log, [date('c'), $cell($name), $cell($email), $phone_cell, $cell($country), $cell(implode('; ', $picked)), $cell($message), $sent ? 'mailed' : 'mail-failed']);
    fclose($log);
}

$_SESSION['last_submit'] = time();

if (!$sent && !$log) {
    respond(false, 'We could not send your message. Please email us at ' . LEADS_EMAIL . '.', $wants_json);
}
respond(true, 'Thanks — your message is on its way. We will reply soon.', $wants_json);
