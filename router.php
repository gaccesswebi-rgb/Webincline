<?php
/**
 * Front controller for clean addresses like /services/seo.
 * Apache sends every request that is not a real file here (see .htaccess).
 * Locally: php -S 127.0.0.1:8080 router.php
 */

declare(strict_types=1);

$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$private = (bool) preg_match('#^/(includes|storage|pages)(/|$)#', $path);

// PHP's built-in server: hand real files (CSS, images, the form handler) back to it.
if (PHP_SAPI === 'cli-server' && !in_array($path, ['/', '/index.php', '/router.php'], true) && !$private && is_file(__DIR__ . $path)) {
    return false;
}

// One address per page: drop trailing slashes and /index.php.
if ($path === '/index.php' || ($path !== '/' && str_ends_with($path, '/'))) {
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
