<?php

declare(strict_types=1);

const ROOT_DIR = __DIR__ . '/..';

/** Escape text for HTML output. */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/** Asset URL with a file-modified stamp so browsers pick up changes. */
function asset(string $path): string
{
    $file = ROOT_DIR . '/assets/' . $path;
    $version = is_file($file) ? (string) filemtime($file) : '1';
    return '/assets/' . $path . '?v=' . $version;
}

/** Link to an internal page only once that page exists; otherwise null. */
function page_url(string $path): ?string
{
    $file = ROOT_DIR . rtrim($path, '/') . '.php';
    $index = ROOT_DIR . rtrim($path, '/') . '/index.php';
    return (is_file($file) || is_file($index)) ? $path : null;
}

function csrf_token(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/**
 * Heading markup: "|" starts a new line, *words* get the brand gradient.
 * Example: title('Six channels.|*One* growth plan.')
 */
function title(string $text): string
{
    $lines = array_map(static function (string $line): string {
        $html = preg_replace('/\*(.+?)\*/', '<span class="grad">$1</span>', e(trim($line)));
        return '<span class="ln"><span class="ln__in">' . $html . '</span></span>';
    }, explode('|', $text));
    return implode(' ', $lines);
}

/** Small inline SVG icon set. */
function icon(string $name, string $class = 'icon'): string
{
    $paths = [
        'arrow'     => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'search'    => '<circle cx="11" cy="11" r="6.5"/><path d="m20 20-4.2-4.2"/>',
        'heart'     => '<path d="M12 20s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.6-7 10-7 10z"/>',
        'chat'      => '<path d="M5 18.5V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H8z"/>',
        'send'      => '<path d="m4 12 16-8-6 16-2.5-6.5z"/>',
        'calendar'  => '<rect x="4" y="5" width="16" height="15" rx="3"/><path d="M8 3v4M16 3v4M4 10h16"/>',
        'drag'      => '<path d="M9 6 3 12l6 6M15 6l6 6-6 6"/>',
        'sound'     => '<path d="M4 9v6h4l5 4V5L8 9z"/><path d="M16.5 8.5a5 5 0 0 1 0 7M19 6a8.5 8.5 0 0 1 0 12"/>',
        'mute'      => '<path d="M4 9v6h4l5 4V5L8 9z"/><path d="m17 9 5 6M22 9l-5 6"/>',
        'star'      => '<path d="m12 3 2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1L3.2 9.5l6.1-.9z" fill="currentColor" stroke="none"/>',
        'clock'     => '<circle cx="12" cy="12" r="8"/><path d="M12 8v4l3 2"/>',
        'arrow-up'  => '<path d="M7 17 17 7M8 7h9v9"/>',
        'plus'      => '<path d="M12 5v14M5 12h14"/>',
        'check'     => '<path d="m5 12 5 5 9-10"/>',
        'play'      => '<path d="M8 5.5v13l11-6.5z" fill="currentColor" stroke="none"/>',
        'mail'      => '<rect x="3" y="5" width="18" height="14" rx="3"/><path d="m4 7 8 6 8-6"/>',
        'phone'     => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/>',
        'pin'       => '<path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/>',
        'whatsapp'  => '<path d="M4 20l1.3-4A8 8 0 1 1 8 18.7z"/><path d="M9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.5-2-1-1 .8a4 4 0 0 1-1.8-1.8l.8-1-1-2z"/>',
        'target'    => '<circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="4"/><circle cx="12" cy="12" r="1" fill="currentColor"/>',
        'users'     => '<circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0"/><path d="M16 4.5a3.5 3.5 0 0 1 0 7M21.5 20a6.5 6.5 0 0 0-4-6"/>',
        'chart'     => '<path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>',
        'menu'      => '<path d="M4 8h16M4 16h16"/>',
        'close'     => '<path d="M6 6l12 12M18 6 6 18"/>',
        'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/>',
        'facebook'  => '<path d="M14 8h3V4h-3a4 4 0 0 0-4 4v3H7v4h3v6h4v-6h3l1-4h-4V8z"/>',
        'x'         => '<path d="M4 4l16 16M20 4 4 20"/>',
    ];
    $d = $paths[$name] ?? '';
    return '<svg class="' . e($class) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $d . '</svg>';
}
