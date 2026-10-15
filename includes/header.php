<?php
/** @var array $site @var array $nav @var string $page_title @var string $page_description */
$page_title       = $page_title ?? SITE_NAME . ' — ' . SITE_TAGLINE;
$page_description = $page_description ?? 'Webincline is a digital marketing agency in Chandigarh running SEO, Google & Meta ads, social media, websites, CRM and AI automation for brands in India, Canada, the USA, the UK, Australia and beyond.';
$canonical        = SITE_URL . strtok($_SERVER['REQUEST_URI'] ?? '/', '?');

$org_schema = [
    '@context' => 'https://schema.org',
    '@type'    => 'ProfessionalService',
    'name'     => SITE_NAME,
    'slogan'   => SITE_TAGLINE,
    'url'      => SITE_URL,
    'logo'     => SITE_URL . '/assets/img/brand/icon-512.png',
    'email'    => $site['email'],
    'telephone' => array_column($site['phones'], 'tel'),
    'address'  => [
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
<meta name="theme-color" content="#120814">

<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
<meta property="og:title" content="<?= e($page_title) ?>">
<meta property="og:description" content="<?= e($page_description) ?>">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:image" content="<?= e(SITE_URL) ?>/assets/img/brand/icon-512.png">
<meta name="twitter:card" content="summary">

<link rel="icon" type="image/png" sizes="32x32" href="<?= asset('img/brand/favicon-32.png') ?>">
<link rel="apple-touch-icon" href="<?= asset('img/brand/apple-touch-icon.png') ?>">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="<?= asset('css/main.css') ?>">

<script>
  (function (root) {
    root.classList.replace('no-js', 'js');
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) root.classList.add('motion');
    try { if (sessionStorage.getItem('wi-intro')) root.classList.add('intro-seen'); } catch (e) {}
    // If the animation scripts never start, show everything anyway.
    setTimeout(function () { if (!window.wiReady) root.classList.remove('motion'), root.classList.add('intro-seen'); }, 4000);
  })(document.documentElement);
</script>
<script type="application/ld+json"><?= json_encode($org_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>

<div class="intro" aria-hidden="true">
  <div class="intro__blob intro__blob--orange"></div>
  <div class="intro__blob intro__blob--violet"></div>
  <div class="intro__inner">
    <img class="intro__logo" src="<?= asset('img/brand/webincline-icon.webp') ?>" alt="" width="160" height="85">
    <p class="intro__tagline"><?= e(SITE_TAGLINE) ?></p>
    <div class="intro__bar"><span></span></div>
  </div>
</div>

<div class="site-bg" aria-hidden="true"></div>

<header class="site-header" data-header>
  <div class="container site-header__inner">
    <a class="brand" href="/" aria-label="<?= e(SITE_NAME) ?> home">
      <img class="brand__icon" src="<?= asset('img/brand/webincline-icon.webp') ?>" alt="" width="64" height="34">
      <span class="brand__word">webincline</span>
    </a>

    <nav class="site-nav" aria-label="Main">
      <ul>
        <?php foreach ($nav as $item): ?>
          <li><a href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <a class="btn btn--primary btn--sm site-header__cta" href="#contact">
      <span>Let’s talk</span><?= icon('arrow') ?>
    </a>

    <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="mobile-menu" data-menu-toggle>
      <span class="sr-only">Open menu</span>
      <?= icon('menu', 'icon icon--open') ?><?= icon('close', 'icon icon--close') ?>
    </button>
  </div>

  <div class="mobile-menu" id="mobile-menu" data-mobile-menu hidden>
    <ul>
      <?php foreach ($nav as $item): ?>
        <li><a href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a></li>
      <?php endforeach; ?>
      <li><a href="#contact">Contact</a></li>
    </ul>
    <div class="mobile-menu__contact">
      <a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a>
      <a href="tel:<?= e($site['phones'][0]['tel']) ?>"><?= e($site['phones'][0]['label']) ?></a>
    </div>
  </div>
</header>
