<?php
/**
 * Site-wide settings. Change contact details, links and stats here —
 * every page reads from this file.
 */

declare(strict_types=1);

const SITE_NAME    = 'Webincline';
const SITE_TAGLINE = 'Scaling ideas. Building growth.';
const SITE_URL     = 'https://www.webincline.com';

// Where contact-form leads are emailed.
const LEADS_EMAIL  = 'webincline@gmail.com';

// Blog posts still live on the current site. Switch to '' once the new blog pages exist.
const BLOG_BASE    = 'https://www.webincline.com';

$site = [
    'email'   => 'webincline@gmail.com',
    'phones'  => [
        ['label' => '+91 98889 39280', 'tel' => '+919888939280'],
        ['label' => '+91 99888 87039', 'tel' => '+919988887039'],
    ],
    'whatsapp' => '919888939280',
    'address'  => [
        'line1'   => 'SCO 387, Sector 13',
        'city'    => 'Chandigarh',
        'country' => 'India',
    ],
    'social' => [
        'Instagram' => 'https://www.instagram.com/webincline/',
        'Facebook'  => 'https://www.facebook.com/webincline',
        'X'         => 'https://twitter.com/webincline',
    ],
    // Figures carried over from the current website — confirm before launch.
    'stats' => [
        ['value' => 8,   'suffix' => '+', 'label' => 'Years in digital marketing'],
        ['value' => 500, 'suffix' => '+', 'label' => 'Clients served'],
        ['value' => 9,   'suffix' => '',  'label' => 'Markets we work in'],
        ['value' => 6,   'suffix' => ' mo', 'label' => 'Free support on every website'],
    ],
    'markets' => ['India', 'Canada', 'USA', 'UK', 'Australia', 'Europe', 'Singapore', 'South Africa', 'Africa'],
];

$nav = [
    ['label' => 'Services', 'href' => '#services'],
    ['label' => 'Work',     'href' => '#work'],
    ['label' => 'Process',  'href' => '#process'],
    ['label' => 'Team',     'href' => '#team'],
    ['label' => 'Insights', 'href' => '#insights'],
];
