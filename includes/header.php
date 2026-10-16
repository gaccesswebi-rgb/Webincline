<?php
/** @var array $site @var array $nav @var string $page_title @var string $page_description */
$page_title       = $page_title ?? SITE_NAME . ' — ' . SITE_TAGLINE;
$page_description = $page_description ?? 'Webincline is a digital marketing agency in Chandigarh running SEO, Google & Meta ads, social media, websites, CRM and AI automation for brands in India, Canada, the USA, the UK, Australia and beyond.';
$canonical        = SITE_URL . strtok($_SERVER['REQUEST_URI'] ?? '/', '?');

$org_schema = [
    '@context'   => 'https://schema.org',
    '@type'      => 'ProfessionalService',
    'name'       => SITE_NAME,
    'slogan'     => SITE_TAGLINE,
    'url'        => SITE_URL,
    'logo'       => SITE_URL . '/assets/img/brand/icon-512.png',
    'email'      => $site['email'],
    'telephone'  => array_column($site['phones'], 'tel'),
    'address'    => [
        '@type'           => 'PostalAddress',
        'streetAddress'   => $site['address']['line1'],
        'addressLocality' => $site['address']['city'],
        'addressCountry'  => 'IN',
    ],
    'areaServed' => $site['markets'],
    'sameAs'     => array_values($site['social']),
];
?><!doctype html>
<html lang="en" class="no-js">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($page_title) ?></title>
<meta name="description" content="<?= e($page_description) ?>">
<link rel="canonical" href="<?= e($canonical) ?>">
<meta name="theme-color" content="#111111">

<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
<meta property="og:title" content="<?= e($page_title) ?>">
<meta property="og:description" content="<?= e($page_description) ?>">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:image" content="<?= e(SITE_URL) ?>/assets/img/brand/icon-512.png">
<meta name="twitter:card" content="summary">

<link rel="icon" type="image/png" sizes="32x32" href="<?= asset('img/brand/favicon-32.png') ?>">
<link rel="apple-touch-icon" href="<?= asset('img/brand/apple-touch-icon.png') ?>">
<link rel="preload" as="image" href="<?= asset('img/photos/hero-crowd.webp') ?>">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@400;500&family=Poppins:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="<?= asset('css/main.css') ?>">

<script>
  (function (root) {
    root.classList.replace('no-js', 'js');
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) root.classList.add('motion');
    try { if (sessionStorage.getItem('wi-intro')) root.classList.add('intro-seen'); } catch (e) {}
    // If the animation scripts never start, show everything anyway.
    setTimeout(function () { if (!window.wiReady) { root.classList.remove('motion'); root.classList.add('intro-seen'); } }, 4000);
  })(document.documentElement);
</script>
<script type="application/ld+json"><?= json_encode($org_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>

<div class="intro" aria-hidden="true">
  <div class="intro__ring"></div>
  <div class="intro__inner">
    <img class="intro__logo" src="<?= asset('img/brand/webincline-icon.webp') ?>" alt="" width="120" height="64">
    <p class="intro__count"><span data-intro-count>0</span><small>%</small></p>
    <p class="intro__label mono"><?= e(SITE_TAGLINE) ?></p>
  </div>
  <p class="intro__skip mono">Click or press Esc to skip</p>
</div>

<header class="hd" data-header>
  <div class="hd__bar">
    <a class="hd__logo" href="/" aria-label="<?= e(SITE_NAME) ?> home">
      <img class="hd__mark" src="<?= asset('img/brand/webincline-icon.webp') ?>" alt="" width="46" height="25">
      <span>webincline</span>
    </a>

    <nav class="hd__nav" aria-label="Main">
      <ul class="hd__links">
        <?php foreach ($nav as $item): ?>
          <li><a href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="hd__act">
      <a class="btn btn--solid btn--sm hd__cta" href="#contact" data-mag>
        <span class="btn__lb">Book a call</span><span class="btn__ic"><?= icon('calendar') ?></span>
      </a>
      <button class="hd__menu" type="button" aria-expanded="false" aria-controls="mobile-menu" data-menu-toggle>
        <span class="sr-only">Open menu</span><i></i><i></i>
      </button>
    </div>
  </div>

  <div class="mm" id="mobile-menu" data-mobile-menu hidden>
    <ul class="mm__links">
      <?php foreach ($nav as $i => $item): ?>
        <li><a href="<?= e($item['href']) ?>"><span class="mono"><?= sprintf('%02d', $i + 1) ?></span><?= e($item['label']) ?></a></li>
      <?php endforeach; ?>
      <li><a href="#contact"><span class="mono"><?= sprintf('%02d', count($nav) + 1) ?></span>Contact</a></li>
    </ul>
    <div class="mm__foot">
      <p class="mono">Say hello</p>
      <p class="selectable"><?= e($site['email']) ?></p>
      <p><?= e($site['phones'][0]['label']) ?></p>
    </div>
  </div>
</header>
