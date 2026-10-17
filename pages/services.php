<?php
/**
 * /services — the services hub: all seven main services and their sub-services.
 * Content lives in includes/services.php; shared sections in includes/partials.
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$page_title       = 'Digital Marketing Services: SEO, Ads, Web & CRM | Webincline';
$page_description = 'SEO, paid ads, social media, websites, CRM, automation and email marketing from one Chandigarh team, planned together and reported in plain numbers.';
$breadcrumbs      = [['Home', '/'], ['Services', '/services']];
$page_sections    = ['process', 'faq', 'contact'];   // sections this page has, so header and footer links stay here

$page_schema = [[
    '@context'        => 'https://schema.org',
    '@type'           => 'ItemList',
    'name'            => 'Webincline services',
    'itemListElement' => array_map(static fn(string $slug, array $s, int $i): array => [
        '@type'    => 'ListItem',
        'position' => $i + 1,
        'item'     => [
            '@type'       => 'Service',
            'name'        => $s['name'],
            'description' => $s['body'],
            'url'         => SITE_URL . service_link($slug),
            'provider'    => ['@id' => SITE_URL . '/#org'],
        ],
    ], array_keys($services), $services, array_keys(array_keys($services))),
]];

$service_faqs = [
    ['q' => 'Which service should I start with?', 'tag' => 'Getting started', 'a' => 'The one closest to a sale. If people already search for what you sell, start with Google Ads and SEO. If they need to discover you first, start with Meta ads and social media. On the first call we look at your numbers and suggest one starting channel, not all seven.'],
    ['q' => 'Can I hire you for just one service?', 'tag' => 'Getting started', 'a' => 'Yes. Most clients start with one channel and add more once it pays for itself. Each channel is reported on its own, so you can see what every one of them returns.'],
    ['q' => 'Do you lock clients into long contracts?', 'tag' => 'Working together', 'a' => 'No. We would rather keep you with results than with paperwork. We agree the scope and notice period with you before we start.'],
    ['q' => 'Do you work with businesses outside India?', 'tag' => 'Working together', 'a' => 'Yes. Our team is in Chandigarh and works with clients in India, Canada, the USA, the UK, the UAE, Australia, Singapore, South Africa, the rest of Africa and Europe, on calls, WhatsApp and email that fit your working hours.'],
    ['q' => 'What do you need from us to start?', 'tag' => 'Getting started', 'a' => 'Access to your website, ad accounts and analytics, a short call about your customers and margins, and one person who can approve the work. We take it from there.'],
    ['q' => 'Can you take over an existing ad account or website?', 'tag' => 'Working together', 'a' => 'Yes. We start with an audit of the account or site as it is, keep what works, and agree the fixes with you before we change anything.'],
    ['q' => 'Which platforms do you work with?', 'tag' => 'Services', 'a' => 'Google, Meta, Amazon, ChatGPT, LinkedIn, TikTok and YouTube for ads. Shopify, WordPress, Wix, Framer, Squarespace and Webflow for websites. Zoho, GoHighLevel, HubSpot, Salesforce and Microsoft Dynamics for CRM. Zapier, Make, ManyChat and n8n for automation. Klaviyo, Brevo, Mailchimp, HubSpot and Zoho Campaigns for email.'],
    ['q' => 'Can you work alongside our in-house team or another agency?', 'tag' => 'Working together', 'a' => 'Yes. Many clients keep some channels in-house. We agree who owns which channel and keep the numbers in one shared report.'],
];

$book = link_or('/contact/book-a-call', '#contact');
$work = link_or('/work', '/#work');

require __DIR__ . '/../includes/header.php';
?>
<main id="main">

  <!-- ===================== PAGE HERO ===================== -->
  <section class="sec sec-light ph" data-ph>
    <div class="ph__dots" aria-hidden="true"></div>
    <div class="wrap ph__grid">
      <div class="ph__txt">
        <nav class="crumbs mono" aria-label="Breadcrumb">
          <ol>
            <li><a href="/">Home</a></li>
            <li><span aria-current="page">Services</span></li>
          </ol>
        </nav>
        <p class="eb"><i></i>(01) Services</p>
        <h1 class="ph__h"><?= title('Seven channels.|*One* growth plan.') ?></h1>
        <p class="ph__lead">SEO, paid ads, social media, websites, CRM, automation and email marketing from one team in Chandigarh, planned together and reported in plain numbers.</p>
        <div class="ph__cta">
          <a class="btn btn--solid" href="<?= e($book) ?>" data-mag><span class="btn__lb">Book a strategy call</span><span class="btn__ic"><?= icon('calendar') ?></span></a>
          <a class="btn btn--line" href="<?= e($work) ?>" data-mag><span class="btn__lb">See our work</span><span class="btn__ic"><?= icon('arrow-up') ?></span></a>
        </div>
      </div>

      <div class="ph__card is-done" data-ph-serp aria-hidden="true">
        <div class="phs__top mono"><span><i class="live"></i>Live · Google search</span><span data-ph-step>Search 1 of 3</span></div>
        <div class="ui-q"><?= icon('search') ?><span data-ph-q>plumber near me</span><i class="phs__caret"></i></div>
        <p class="ui-meta mono">About 2,140,000 results · 0.42 s</p>
        <div class="phs__res">
          <?php foreach (['homeadvisor.com', 'yelp.com › plumbers', 'angi.com', 'reddit.com › r/homeowners'] as $i => $d): ?>
            <div class="ui-r" data-ph-row style="--i:<?= $i + 1 ?>"><span class="ui-fav"></span><span class="ui-rt"><b data-ph-d><?= e($d) ?></b><i></i></span></div>
          <?php endforeach; ?>
          <div class="ui-r is-you" data-ph-row data-ph-you style="--i:0"><span class="ui-fav"></span><span class="ui-rt"><b>yourbrand.com</b><i></i></span><span class="ui-rank mono" data-ph-rank>#1</span></div>
        </div>
        <p class="ui-toast phs__toast" data-ph-toast><span class="ui-toast__i"><?= icon('check') ?></span>Also cited in AI answers</p>
      </div>
    </div>

    <div class="wrap ph__meta">
      <ul class="ph__idx mono">
        <?php $n = 0; foreach ($services as $slug => $s): ?>
          <li><a href="#<?= e($slug) ?>"><b><?= sprintf('%02d', ++$n) ?></b> <?= e($s['title']) ?></a></li>
        <?php endforeach; ?>
      </ul>
      <p class="ph__scroll mono" aria-hidden="true">Scroll <i></i></p>
    </div>
  </section>

  <!-- ===================== ALL SERVICES ===================== -->
  <section class="sec sec-light sg" id="all" data-sg>
    <div class="wrap">
      <div class="head">
        <div>
          <p class="eb" data-rv><i></i>(02) All services</p>
          <h2 class="h2" data-hd><?= title('Pick one channel,|or *all seven.*') ?></h2>
        </div>
        <div class="head__side" data-rv>
          <p class="lead">Each channel is run by a specialist, planned around one goal (more customers) and reported in one place. Start with one and add more as you grow.</p>
          <a class="btn btn--line" href="#contact" data-mag><span class="btn__lb">Plan my channels</span><span class="btn__ic"><?= icon('arrow-up') ?></span></a>
        </div>
      </div>

      <div class="sg__grid">
        <?php $n = 0; foreach ($services as $slug => $s): $n++; $page = page_url(service_path($slug)); ?>
          <article class="sc pn--<?= e($s['theme']) ?>" id="<?= e($slug) ?>" aria-labelledby="sc-<?= e($slug) ?>" data-sc>
            <div class="sc__vis">
              <p class="sc__tag mono"><i class="live"></i>Live · <?= e($s['title']) ?></p>
              <span class="sc__n mono"><?= sprintf('%02d', $n) ?></span>
              <div class="sc__ui" aria-hidden="true"><?= channel_visual($s['visual']) ?></div>
            </div>
            <div class="sc__body">
              <div class="sc__top">
                <h3 class="sc__t" id="sc-<?= e($slug) ?>"><?= e($s['name']) ?></h3>
                <p class="sc__proof mono"><i class="live"></i><?= e($s['proof']) ?></p>
              </div>
              <p class="sc__line"><?= e($s['line']) ?></p>
              <p class="sc__txt"><?= e($s['body']) ?></p>
              <ul class="sc__subs" aria-label="<?= e($s['name']) ?> services">
                <?php foreach ($s['subs'] as $sub => $x): $sp = page_url(service_path($slug, $sub)); ?>
                  <li><?php if ($sp): ?><a href="<?= e($sp) ?>"><?php else: ?><span><?php endif; ?><span class="ck"><?= icon('check') ?></span><?= e($x['long'] ?? $x['title']) ?><?= $sp ? '</a>' : '</span>' ?></li>
                <?php endforeach; ?>
              </ul>
              <div class="sc__foot">
                <p class="sc__by mono"><?= count($s['subs']) ?> services · <?= e($s['proof_by']) ?></p>
                <?php if ($page): ?>
                  <a class="btn btn--solid btn--sm" href="<?= e($page) ?>" data-mag><span class="btn__lb">Explore <?= e($s['title']) ?></span><span class="btn__ic"><?= icon('arrow-up') ?></span></a>
                <?php else: ?>
                  <a class="btn btn--solid btn--sm" href="#contact" data-pick="<?= e($s['name']) ?>" data-mag><span class="btn__lb">Ask about <?= e($s['title']) ?></span><span class="btn__ic"><?= icon('arrow-up') ?></span></a>
                <?php endif; ?>
              </div>
            </div>
          </article>
        <?php endforeach; ?>

        <aside class="sc sc--ask sec-dark" aria-label="Free audit">
          <div class="sc__ask">
            <p class="mono"><i class="ping"></i>Not sure where to start?</p>
            <p class="sc__askh"><?= title('Get a free|*growth audit.*') ?></p>
            <p>We review your website, ads and socials on a 30-minute call and tell you which channel to start with. You keep the plan either way.</p>
            <div class="sc__askb">
              <a class="btn btn--solid" href="<?= e($book) ?>" data-mag><span class="btn__lb">Book my audit</span><span class="btn__ic"><?= icon('calendar') ?></span></a>
              <a class="btn btn--glass" href="https://wa.me/<?= e($site['whatsapp']) ?>" target="_blank" rel="noopener" data-mag><span class="btn__lb">WhatsApp us</span><span class="btn__ic"><?= icon('whatsapp') ?></span></a>
            </div>
          </div>
        </aside>
      </div>
      <p class="sg__hint mono"><i class="live"></i>Hover a card to replay it</p>
    </div>
  </section>

  <?php
  $fn_eb    = '(03) How we work';
  $fn_lead  = 'Whichever channels you pick, the work runs the same five steps, and you see what each step added in every report.';
  require __DIR__ . '/../includes/partials/funnel.php';
  ?>

  <!-- ===================== WHO WE WORK WITH ===================== -->
  <section class="sec sec-light ww" id="industries">
    <div class="wrap">
      <div class="head">
        <div>
          <p class="eb" data-rv><i></i>(04) Who we work with</p>
          <h2 class="h2" data-hd><?= title('Your industry.|*Your market.*') ?></h2>
        </div>
        <div class="head__side" data-rv>
          <p class="lead">Clinics, colleges, contractors and online stores across four continents. The playbook changes with the buyer; the reporting stays the same.</p>
        </div>
      </div>

      <div class="ww__grid">
        <div class="ww__ind" data-rv>
          <p class="ww__lbl mono">Industries · with clients from our case studies</p>
          <ul class="ww__list">
            <?php foreach ($industries as $ind): ?>
              <li class="ind">
                <span class="ind__ic"><?= icon($ind['icon']) ?></span>
                <span class="ind__tx"><b><?= e($ind['name']) ?></b><small><?= e($ind['clients']) ?></small></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div class="ww__mk sec-dark" data-rv data-mk>
          <div class="ww__mkh">
            <p class="mono"><i class="live"></i>Markets · their time now</p>
            <p class="mono ww__ist">Chandigarh <b data-mk-home>--:--</b></p>
          </div>
          <ul class="mk__list">
            <?php foreach ($markets as $m): ?>
              <li class="mk" data-tz="<?= e($m['tz']) ?>">
                <span class="mk__c mono"><?= e($m['code']) ?></span>
                <span class="mk__n"><b><?= e($m['name']) ?></b><?php if ($m['clients']): ?><small><?= e($m['clients']) ?></small><?php endif; ?></span>
                <span class="mk__t mono"><i class="mk__dot" aria-hidden="true"></i><span data-mk-t>--:--</span><span class="sr-only" data-mk-st></span></span>
              </li>
            <?php endforeach; ?>
          </ul>
          <p class="ww__note mono">A lit dot means it is office hours there right now.</p>
        </div>
      </div>
    </div>
  </section>

  <?php
  $faq_list = $service_faqs;
  $faq_eb   = '(05) FAQ';
  require __DIR__ . '/../includes/partials/faq.php';

  $ct_eb    = '(06) Free audit';
  $ct_title = 'Ready to be|*found?*';
  $ct_lead  = 'Tell us what you sell and where. We review your website, ads and socials and send back the quickest wins, whether we work together or not.';
  require __DIR__ . '/../includes/partials/contact.php';
  ?>

</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
