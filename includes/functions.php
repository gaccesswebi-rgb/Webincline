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
    $lines = array_map(static function (string $line, int $k): string {
        $html = preg_replace('/\*(.+?)\*/', '<span class="grad">$1</span>', e(trim($line)));
        return '<span class="ln"><span class="ln__in" style="--k:' . $k . '">' . $html . '</span></span>';
    }, $parts = explode('|', $text), array_keys($parts));
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
        'lock'      => '<rect x="5" y="10.5" width="14" height="10" rx="2.5"/><path d="M8 10.5V8a4 4 0 0 1 8 0v2.5"/>',
        'torch'     => '<path d="M8 3h8v4l-2 3v11h-4V10L8 7z"/><path d="M12 13v2"/>',
        'camera'    => '<path d="M4 8h3l2-2.5h6L17 8h3v11H4z"/><circle cx="12" cy="13" r="3.5"/>',
    ];
    $d = $paths[$name] ?? '';
    return '<svg class="' . e($class) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $d . '</svg>';
}

/* ---------- wave charts ---------- */

/** Seeded 0..1 generator (xorshift32): the same seed draws the same chart on every load. */
function wave_rng(string $seed): Closure
{
    $s = crc32($seed) ?: 0x9E3779B9;
    return static function () use (&$s): float {
        $s ^= ($s << 13) & 0xFFFFFFFF;
        $s ^= $s >> 17;
        $s ^= ($s << 5) & 0xFFFFFFFF;
        return ($s & 0xFFFFFF) / 0xFFFFFF;
    };
}

/**
 * $n values (0..1 of the climb) shaped like a real month-by-month report: a slow
 * start, a faster finish, seeded wobble and three genuine dips with recoveries.
 * The first value is 0 (the "before" number) and the last is 1 (the "after" number).
 */
function wave_values(string $seed, int $n = 13, float $curve = 1.6, float $wobble = .09): array
{
    $rnd = wave_rng($seed);
    $v = [];
    for ($i = 0; $i < $n; $i++) {
        $t = $i / ($n - 1);
        $v[] = ($i === 0 || $i === $n - 1) ? $t : $t ** $curve + ($rnd() - .5) * $wobble;
    }
    foreach ([.25, .5, .75] as $at) {
        $i = min($n - 3, max(2, (int) round($at * ($n - 1)) + ($rnd() < .5 ? 0 : 1)));
        $drop = .03 + $rnd() * .045;
        $v[$i] = min($v[$i], $v[$i - 1] - $drop);
        $v[$i + 1] = max($v[$i + 1], $v[$i - 1] + .02);
    }
    return array_map(static fn (float $u): float => max(0.0, min(1.0, $u)), $v);
}

/** Place 0..1 values in an SVG box: x from 0 to $w, y from $yLo (value 0) to $yHi (value 1). */
function wave_points(array $v, float $w, float $yLo, float $yHi): array
{
    $n = count($v);
    $pts = [];
    foreach ($v as $i => $u) {
        $pts[] = [$i / ($n - 1) * $w, $yLo - $u * ($yLo - $yHi)];
    }
    return $pts;
}

/** Smooth path through every point without overshooting (monotone cubic). */
function wave_path(array $p): string
{
    $n = count($p);
    $d = [];
    $m = [];
    for ($i = 0; $i < $n - 1; $i++) {
        $d[$i] = ($p[$i + 1][1] - $p[$i][1]) / ($p[$i + 1][0] - $p[$i][0]);
    }
    $m[0] = $d[0];
    $m[$n - 1] = $d[$n - 2];
    for ($i = 1; $i < $n - 1; $i++) {
        $m[$i] = ($d[$i - 1] * $d[$i] <= 0) ? 0 : ($d[$i - 1] + $d[$i]) / 2;
    }
    $f = static fn (float $x): string => rtrim(rtrim(number_format($x, 1, '.', ''), '0'), '.');
    $s = 'M' . $f($p[0][0]) . ' ' . $f($p[0][1]);
    for ($i = 0; $i < $n - 1; $i++) {
        $h = ($p[$i + 1][0] - $p[$i][0]) / 3;
        $s .= ' C' . $f($p[$i][0] + $h) . ' ' . $f($p[$i][1] + $m[$i] * $h)
            . ' ' . $f($p[$i + 1][0] - $h) . ' ' . $f($p[$i + 1][1] - $m[$i + 1] * $h)
            . ' ' . $f($p[$i + 1][0]) . ' ' . $f($p[$i + 1][1]);
    }
    return $s;
}

/** Straight-segment path (sparklines). */
function wave_line(array $p): string
{
    return implode(' ', array_map(static fn (array $q, int $i): string => ($i ? 'L' : 'M') . round($q[0], 1) . ' ' . round($q[1], 1), $p, array_keys($p)));
}
