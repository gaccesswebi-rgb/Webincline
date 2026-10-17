<?php
/**
 * Every page that exists, by URL path. Add a line here when a page is built:
 * links across the site switch from their fallback to the new page by themselves.
 */

declare(strict_types=1);

const ROUTES = [
    '/'            => 'index.php',
    '/services'    => 'pages/services.php',
    '/sitemap.xml' => 'pages/sitemap.php',
];

/* Shown for any other address. */
const NOT_FOUND = 'pages/404.php';
