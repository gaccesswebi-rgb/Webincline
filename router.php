<?php
/**
 * Front controller for clean addresses like /services/seo.
 * Apache sends every request that is not a real file here (see .htaccess).
 * Locally: php -S 127.0.0.1:8080 router.php
 */

declare(strict_types=1);

$raw  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = rawurldecode($raw);

// Page addresses only ever use these characters; anything else (dot segments,
// backslashes, control characters, dotfiles) gets the 404 page.
$valid   = $path === '/' || (bool) preg_match('#^(/[a-z0-9][a-z0-9._-]*)+/?$#i', $path);
$private = !$valid || (bool) preg_match('#^/(includes|storage|pages)(/|$)#', $path);

// PHP's built-in server: hand back real files it may serve (assets, the form handler).
if (PHP_SAPI === 'cli-server' && !$private) {
    $file = realpath(__DIR__ . $path);
    if ($file && is_file($file) && (str_starts_with($file, __DIR__ . '/assets/') || in_array($file, [__DIR__ . '/contact-submit.php', __DIR__ . '/robots.txt'], true))) {
        return false;
    }
}

// Real folders (/assets/) have no page; on Apache, stripping their slash would loop with mod_dir.
if ($path !== '/' && is_dir(__DIR__ . $path)) {
    $private = true;
}

// One address per page: drop trailing slashes and /index.php.
if (!$private && ($path === '/index.php' || ($path !== '/' && str_ends_with($path, '/')))) {
    $to = $path === '/index.php' ? '/' : rtrim($path, '/');
    $query = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_QUERY);
    header('Location: ' . $to . ($query ? '?' . $query : ''), true, 301);
    exit;
}

require_once __DIR__ . '/includes/bootstrap.php';

$page = $private ? null : (ROUTES[$path] ?? null);
if ($page === null) {
    http_response_code(404);
    $page = NOT_FOUND;
}
require __DIR__ . '/' . $page;
