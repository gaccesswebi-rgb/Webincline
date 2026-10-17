<?php
/**
 * @var array $site @var array $nav @var array $services
 * Optional per page: $page_title, $page_description, $noindex,
 * $breadcrumbs ([[name, path], ...]) and $page_schema (extra JSON-LD objects).
 */
$page_title       = $page_title ?? SITE_NAME . ' — ' . SITE_TAGLINE;
$page_description = $page_description ?? 'Webincline is a digital marketing agency in Chandigarh running SEO, Google & Meta ads, social media, websites, CRM and AI automation for brands in India, Canada, the USA, the UK, Australia and beyond.';
$canonical        = SITE_URL . current_path();
$is_home          = current_path() === '/';
$book_href        = link_or('/contact/book-a-call', anchor('contact'));
$csrf             = csrf_token();   // the contact form needs the session before any output

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

$schemas = [$org_schema];
if (!empty($breadcrumbs)) {
    $schemas[] = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => array_map(static fn(array $c, int $i): array => [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $c[0],
            'item'     => SITE_URL . $c[1],
        ], $breadcrumbs, array_keys($breadcrumbs)),
    ];
}
$schemas = array_merge($schemas, $page_schema ?? []);
?><!doctype html>
<html lang="en" class="no-js">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($page_title) ?></title>
<meta name="description" content="<?= e($page_description) ?>">
<?php if (!empty($noindex)): ?><meta name="robots" content="noindex">
<?php endif; ?>
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
<?php if ($is_home): ?>
<link rel="preload" as="image" href="<?= asset('img/photos/hero-crowd-1280.webp') ?>" imagesrcset="<?= asset('img/photos/hero-crowd-828.webp') ?> 828w, <?= asset('img/photos/hero-crowd-1280.webp') ?> 1280w, <?= asset('img/photos/hero-crowd-1600.webp') ?> 1600w, <?= asset('img/photos/hero-crowd-2000.webp') ?> 2000w" imagesizes="100vw" fetchpriority="high">
<?php endif; ?>
<!-- fonts are self-hosted (assets/fonts); the two above-the-fold weights load first -->
<link rel="preload" as="font" type="font/woff2" href="/assets/fonts/poppins-600.woff2" crossorigin>
<link rel="preload" as="font" type="font/woff2" href="/assets/fonts/poppins-400.woff2" crossorigin>

<link rel="stylesheet" href="<?= asset('css/main.css') ?>">

<script>
  (function (root) {
    root.classList.remove('no-js'); root.classList.add('js');
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) root.classList.add('motion');
    // If the animation scripts never start, show everything anyway.
    setTimeout(function () { if (!window.wiReady) root.classList.remove('motion'); }, 4000);
  })(document.documentElement);
</script>
<?php foreach ($schemas as $schema): ?>
<script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
<?php endforeach; ?>
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>

<header class="hd" data-header>
  <div class="hd__bar">
    <a class="hd__logo" href="/" aria-label="<?= e(SITE_NAME) ?> home">
      <img class="hd__mark" src="<?= asset('img/brand/webincline-icon.webp') ?>" alt="" width="46" height="25">
      <span>webincline</span>
    </a>

    <nav class="hd__nav" aria-label="Main">
      <ul class="hd__links">
        <?php foreach ($nav as $item):
          $href = link_or($item['path'], anchor($item['anchor']));
          $act  = page_url($item['path']) && (current_path() === $item['path'] || str_starts_with(current_path(), $item['path'] . '/'));
          $mega = $item['path'] === '/services'; ?>
          <li<?= $mega ? ' class="hd__has" data-mega' : '' ?>>
            <a href="<?= e($href) ?>"<?= $act ? ' class="is-act" aria-current="page"' : '' ?>><?= e($item['label']) ?></a>
            <?php if ($mega): ?>
              <button class="hd__more" type="button" aria-expanded="false" aria-controls="mega" data-mega-toggle><span class="sr-only">All services</span><?= icon('chev') ?></button>
              <div class="mega" id="mega">
                <div class="mega__in">
                  <?php foreach ($services as $slug => $s): $hub = link_or('/services', anchor('services')) . (page_url('/services') ? '#' . $slug : ''); ?>
                    <div class="mega__col">
                      <a class="mega__h" href="<?= e(link_or(service_path($slug), $hub)) ?>"><span class="mega__dot mega__dot--<?= e($s['theme']) ?>"></span><?= e($s['name']) ?></a>
                      <ul>
                        <?php foreach ($s['subs'] as $sub => $x): ?>
                          <li><a href="<?= e(link_or(service_path($slug, $sub), $hub)) ?>"><?= e($x['title']) ?></a></li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                  <?php endforeach; ?>
                  <div class="mega__cta sec-dark">
                    <p class="mono"><i class="live"></i>Not sure where to start?</p>
                    <p class="mega__ct">Get a free audit of your website, ads and socials.</p>
                    <a class="btn btn--solid btn--sm" href="<?= e($book_href) ?>"><span class="btn__lb">Book a call</span><span class="btn__ic"><?= icon('calendar') ?></span></a>
                    <a class="mega__all mono" href="<?= e(link_or('/services', anchor('services'))) ?>">All services <?= icon('arrow') ?></a>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="hd__act">
      <a class="btn btn--solid btn--sm hd__cta" href="<?= e($book_href) ?>" data-mag>
        <span class="btn__lb">Book a call</span><span class="btn__ic"><?= icon('calendar') ?></span>
      </a>
      <button class="hd__menu" type="button" aria-expanded="false" aria-controls="mobile-menu" data-menu-toggle>
        <span class="sr-only">Open menu</span><i></i><i></i>
      </button>
    </div>
  </div>

  <div class="mm" id="mobile-menu" data-mobile-menu data-lenis-prevent hidden>
    <ul class="mm__links">
      <?php foreach ($nav as $i => $item): ?>
        <li><a href="<?= e(link_or($item['path'], anchor($item['anchor']))) ?>"><span class="mono"><?= sprintf('%02d', $i + 1) ?></span><?= e($item['label']) ?></a>
          <?php if ($item['path'] === '/services' && page_url('/services')): ?>
            <ul class="mm__sub">
              <?php foreach ($services as $slug => $s): ?><li><a href="<?= e(link_or(service_path($slug), '/services#' . $slug)) ?>"><?= e($s['name']) ?></a></li><?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
      <li><a href="<?= e(link_or('/contact', anchor('contact'))) ?>"><span class="mono"><?= sprintf('%02d', count($nav) + 1) ?></span>Contact</a></li>
    </ul>
    <div class="mm__foot">
      <p class="mono">Say hello</p>
      <p class="selectable"><?= e($site['email']) ?></p>
      <p><?= e($site['phones'][0]['label']) ?></p>
    </div>
  </div>
</header>
