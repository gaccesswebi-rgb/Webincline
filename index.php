<?php
declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/data.php';
require __DIR__ . '/includes/visuals.php';

$csrf        = csrf_token();
$form_status = $_GET['status'] ?? '';
$page_title  = 'Webincline — Digital Marketing Agency | SEO, Ads, Websites, CRM & AI';

$ring_radius = ['local' => 0.28, 'regional' => 0.5, 'national' => 0.7, 'global' => 0.88];

require __DIR__ . '/includes/header.php';
?>
<main id="main">

  <!-- ============ HERO ============ -->
  <section class="hero" data-hero>
    <canvas class="hero__crowd" data-crowd aria-hidden="true"></canvas>
    <div class="hero__shade" aria-hidden="true"></div>
    <p class="hero__hint mono" aria-hidden="true"><span class="dot"></span>Move to scan the crowd · click to launch a campaign</p>
    <div class="hero__pops" data-pops aria-hidden="true"></div>

    <div class="container hero__content">
      <p class="badge" data-hero-item><span class="dot dot--live"></span>Taking new projects in 9 markets</p>
      <h1 class="hero__title">
        <span class="line"><span class="line__inner">Scaling ideas.</span></span>
        <span class="line"><span class="line__inner">Building <mark class="hl">growth.</mark></span></span>
      </h1>
      <p class="hero__lead" data-hero-item>Webincline is a digital marketing agency in Chandigarh. We run SEO, paid ads, social media, websites, CRM and AI automation that put your brand in front of people ready to buy — and we report every result in plain numbers.</p>
      <div class="hero__actions" data-hero-item>
        <a class="btn btn--primary" href="#contact"><span>Book a strategy call</span><span class="btn__icon"><?= icon('arrow-up') ?></span></a>
        <a class="btn btn--ghost" href="#work"><span>See our results</span><span class="btn__icon"><?= icon('arrow-up') ?></span></a>
      </div>
    </div>

    <div class="container hero__bar" data-hero-item>
      <div class="hero__count">
        <p class="mono"><span class="dot"></span>Brands grown with Webincline</p>
        <p class="odometer" data-odometer="500" aria-label="500 plus"><span class="odometer__digits">500</span><span class="odometer__plus">+</span></p>
      </div>
      <ul class="hero__channels mono">
        <?php foreach ($channels as $i => $c): ?>
          <li><a href="#services" data-goto-channel="<?= $i ?>"><b><?= sprintf('%02d', $i + 1) ?></b> <?= e($c['short']) ?></a></li>
        <?php endforeach; ?>
      </ul>
      <p class="hero__meta mono">8+ years<br>9 markets</p>
    </div>
  </section>

  <!-- ============ CLIENTS RADAR ============ -->
  <section class="section radar-sec" id="clients">
    <div class="container radar-sec__grid">
      <div class="radar-sec__text">
        <p class="tag" data-reveal><span class="dot"></span>(00) Clients</p>
        <h2 class="h2" data-reveal>500+ brands. <mark class="hl">Nine markets.</mark></h2>
        <p class="lead" data-reveal>Clinics and colleges in Chandigarh, renovation firms in Dubai, robotics stores in Australia, service companies in Canada and the USA. Each came to us to be found by more of the right people.</p>
        <div class="radar-sec__meta mono" data-reveal>
          <p>8+ years · 21 brands below</p>
          <p><span class="dot dot--live"></span>Latest ping: <span data-radar-last>Beauty Basket · 60× ROAS</span></p>
        </div>
      </div>

      <div class="radar" data-radar data-reveal>
        <div class="radar__dial" aria-hidden="true">
          <span class="radar__deg radar__deg--n mono">000°</span>
          <span class="radar__deg radar__deg--e mono">090°</span>
          <span class="radar__deg radar__deg--s mono">180°</span>
          <span class="radar__deg radar__deg--w mono">270°</span>
          <?php foreach ($ring_radius as $ring => $r): ?>
            <span class="radar__ring" style="--r:<?= $r ?>"><em class="mono"><?= e(ucfirst($ring)) ?></em></span>
          <?php endforeach; ?>
          <span class="radar__sweep" data-radar-sweep></span>
          <span class="radar__core"></span>
        </div>
        <ul class="radar__dots">
          <?php foreach ($radar as $p):
            $r = $ring_radius[$p['ring']];
            $x = 50 + sin(deg2rad($p['angle'])) * $r * 50;
            $y = 50 - cos(deg2rad($p['angle'])) * $r * 50; ?>
            <li class="radar__dot" style="left:<?= round($x, 2) ?>%;top:<?= round($y, 2) ?>%" data-angle="<?= (int) $p['angle'] ?>" data-label="<?= e($p['name'] . ' · ' . $p['metric']) ?>">
              <span class="radar__name"><?= e($p['name']) ?></span>
              <span class="radar__metric"><?= e($p['metric']) ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <?php $rows = array_chunk($clients, (int) ceil(count($clients) / 2), true); ?>
    <div class="logo-rows" data-reveal>
      <?php foreach ($rows as $r => $row): ?>
        <div class="marquee<?= $r ? ' marquee--reverse' : '' ?>">
          <div class="marquee__track">
            <?php for ($i = 0; $i < 2; $i++): foreach ($row as $slug => $name): ?>
              <span class="logo-tile"<?= $i ? ' aria-hidden="true"' : '' ?>><img src="<?= asset('img/clients/' . $slug . '.webp') ?>" alt="<?= $i ? '' : e($name) ?>" loading="lazy" width="160" height="72"></span>
            <?php endforeach; endfor; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ============ WHY / CROWD ============ -->
  <section class="section why" id="why">
    <div class="container">
      <div class="head">
        <div>
          <p class="tag" data-reveal><span class="dot"></span>(01) Why Webincline</p>
          <h2 class="h2" data-reveal>Your buyers are already online. <mark class="hl">We find them.</mark></h2>
        </div>
        <div class="head__side" data-reveal>
          <p class="lead">Most businesses are not invisible. They are just in front of the wrong people. We find the searches, feeds, lookalike audiences and AI answers where your buyers already spend time — and put you there.</p>
          <ul class="facts mono"><li><span class="dot"></span>Chandigarh HQ</li><li><span class="dot"></span>9 markets</li><li><span class="dot"></span>8+ years</li></ul>
        </div>
      </div>

      <div class="crowd" data-crowd-panel data-reveal>
        <canvas class="crowd__canvas" data-crowd2 aria-hidden="true"></canvas>
        <p class="crowd__top mono"><span class="dot"></span>Everyone online is a crowd</p>
        <?php foreach ($crowd_clusters as $c): ?>
          <div class="crowd__label crowd__label--<?= e($c['key']) ?>" data-cluster="<?= e($c['key']) ?>">
            <span class="chip mono"><?= e($c['label']) ?></span>
            <span class="mono"><?= e($c['note']) ?></span>
          </div>
        <?php endforeach; ?>
        <p class="crowd__big">Most are browsing.<br><span class="text-grad">A few are buying.</span></p>
      </div>
    </div>
  </section>

  <!-- ============ SERVICES / CHANNELS ============ -->
  <section class="section services" id="services">
    <div class="container">
      <div class="head">
        <div>
          <p class="tag" data-reveal><span class="dot"></span>(02) Services</p>
          <h2 class="h2" data-reveal>Twelve services. <mark class="hl">One team.</mark></h2>
        </div>
        <div class="head__side" data-reveal>
          <p class="lead">SEO, ads, social, websites, CRM and AI automation, grouped into six channels and run by one team with one report.</p>
          <a class="btn btn--ghost btn--sm" href="#contact"><span>Plan my channels</span><span class="btn__icon"><?= icon('arrow-up') ?></span></a>
        </div>
      </div>
    </div>

    <div class="channels-pin" data-channels-pin>
      <div class="container">
        <div class="channels" data-channels>
          <?php foreach ($channels as $i => $c): $open = $i === 0; ?>
            <article class="channel<?= $open ? ' is-open' : '' ?>" data-channel>
              <button class="channel__tab" type="button" aria-expanded="<?= $open ? 'true' : 'false' ?>" aria-controls="channel-<?= e($c['slug']) ?>">
                <span class="channel__num mono"><?= sprintf('%02d', $i + 1) ?></span>
                <span class="channel__name"><?= e($c['title']) ?></span>
                <span class="channel__plus"><?= icon('plus') ?></span>
              </button>
              <div class="channel__panel" id="channel-<?= e($c['slug']) ?>">
                <div class="channel__visual"><?= channel_visual($c['visual']) ?></div>
                <div class="channel__info">
                  <h3><?= e($c['title']) ?></h3>
                  <p class="channel__line"><?= e($c['line']) ?></p>
                  <p class="channel__body"><?= e($c['body']) ?></p>
                  <p class="mono channel__label">What’s included</p>
                  <ul class="checklist">
                    <?php foreach ($c['includes'] as $inc): ?><li><?= icon('check') ?><?= e($inc) ?></li><?php endforeach; ?>
                  </ul>
                  <div class="channel__foot">
                    <span class="chip chip--proof mono"><span class="dot dot--live"></span><?= e($c['proof']) ?></span>
                    <a class="btn btn--primary btn--sm" href="#contact"><span>Talk to us</span><span class="btn__icon"><?= icon('arrow-up') ?></span></a>
                  </div>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>

        <div class="channels__status">
          <div class="channels__progress" aria-hidden="true">
            <?php foreach ($channels as $i => $c): ?><span class="<?= $i === 0 ? 'is-on' : '' ?>"></span><?php endforeach; ?>
          </div>
          <p class="mono"><b data-channel-index>01</b> / <?= sprintf('%02d', count($channels)) ?> <span data-channel-name><?= e($channels[0]['title']) ?></span></p>
          <p class="mono channels__hint"><span class="dot"></span>Scroll or tap to switch channels</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ CASE STUDIES ============ -->
  <section class="section work" id="work">
    <div class="container">
      <div class="head">
        <div>
          <p class="tag" data-reveal><span class="dot"></span>(03) Case studies</p>
          <h2 class="h2" data-reveal>Before and after, <mark class="hl">in numbers.</mark></h2>
        </div>
        <div class="head__side" data-reveal>
          <p class="lead">Six clients, four countries, one rule: we show the number that matters to the business, before we started and after.</p>
          <a class="btn btn--ghost btn--sm" href="<?= e(BLOG_BASE) ?>/seo-case-study"><span>All case studies</span><span class="btn__icon"><?= icon('arrow-up') ?></span></a>
        </div>
      </div>

      <div class="case-tabs" role="tablist" aria-label="Case studies" data-reveal>
        <?php foreach ($case_studies as $i => $c): ?>
          <button class="case-tab" role="tab" type="button" id="case-tab-<?= $i ?>" aria-controls="case-<?= $i ?>" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>" <?= $i ? 'tabindex="-1"' : '' ?>>
            <span class="case-tab__mark"><?= e(mb_substr($c['short'], 0, 1)) ?></span>
            <span class="case-tab__text"><b><?= e($c['short']) ?></b><span class="mono"><?= number_format($c['metric']['before']) ?> → <?= number_format($c['metric']['after']) ?></span></span>
          </button>
        <?php endforeach; ?>
      </div>

      <?php foreach ($case_studies as $i => $c):
        $m = $c['metric'];
        $yb = 200 - ($m['before'] / $m['after']) * 160; ?>
        <div class="case" role="tabpanel" id="case-<?= $i ?>" aria-labelledby="case-tab-<?= $i ?>" <?= $i ? 'hidden' : '' ?>>
          <div class="compare" data-compare style="--pos:42%">
            <svg class="compare__chart" viewBox="0 0 600 220" preserveAspectRatio="none" aria-hidden="true">
              <defs>
                <linearGradient id="cfill-<?= $i ?>" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#FF3D81" stop-opacity=".35"/><stop offset="1" stop-color="#FF3D81" stop-opacity="0"/></linearGradient>
                <linearGradient id="cline-<?= $i ?>" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="#FF7A00"/><stop offset=".5" stop-color="#FF3D81"/><stop offset="1" stop-color="#7B4DFF"/></linearGradient>
              </defs>
              <?php for ($g = 1; $g < 5; $g++): ?><line x1="0" x2="600" y1="<?= $g * 44 ?>" y2="<?= $g * 44 ?>" class="compare__grid"/><?php endfor; ?>
              <path d="M0 <?= round($yb, 1) ?> L600 40 L600 220 L0 220 Z" fill="url(#cfill-<?= $i ?>)"/>
              <path d="M0 <?= round($yb, 1) ?> L600 40" stroke="url(#cline-<?= $i ?>)" stroke-width="3" fill="none" vector-effect="non-scaling-stroke"/>
            </svg>
            <div class="compare__side compare__side--before">
              <span class="chip mono">Before · <?= e($m['from']) ?></span>
              <b class="compare__num compare__num--before"><?= number_format($m['before']) ?></b>
            </div>
            <div class="compare__side compare__side--after">
              <span class="chip chip--grad mono">After · <?= e($m['to']) ?></span>
              <b class="compare__num"><span data-count="<?= (int) $m['after'] ?>"><?= number_format($m['after']) ?></span></b>
            </div>
            <p class="compare__label mono"><?= e($m['label']) ?></p>
            <p class="compare__client mono"><span class="dot dot--live"></span><?= e($c['client']) ?> · <?= e($c['year']) ?></p>
            <span class="compare__handle" aria-hidden="true"><?= icon('arrow') ?></span>
            <input class="compare__range" type="range" min="5" max="95" value="42" aria-label="Drag to compare before and after for <?= e($c['client']) ?>">
          </div>
          <div class="case__info">
            <div>
              <p class="mono case__meta"><?= e(implode(' · ', $c['service'])) ?> · <?= e($c['market']) ?></p>
              <h3><?= e($c['title']) ?></h3>
            </div>
            <div>
              <p><?= e($c['summary']) ?></p>
              <a class="link-arrow" href="<?= e(BLOG_BASE . $c['url']) ?>">Read the case <?= icon('arrow-up') ?></a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ============ PROCESS + FUNNEL ============ -->
  <section class="section process" id="process" data-process>
    <div class="container process__grid">
      <div>
        <p class="tag" data-reveal><span class="dot"></span>(04) How we work</p>
        <h2 class="h2" data-reveal>From first call to <mark class="hl">steady growth.</mark></h2>
        <p class="lead" data-reveal>Every project runs the same five steps. You see where we are, and what each step adds, in every report.</p>
        <ol class="steps">
          <?php foreach ($process as $i => $s): ?>
            <li class="step<?= $i === 0 ? ' is-active' : '' ?>" data-step>
              <span class="step__num mono"><?= sprintf('%02d', $i + 1) ?></span>
              <div>
                <h3><?= e($s['title']) ?> <span class="chip mono"><?= e($s['when']) ?></span></h3>
                <p><?= e($s['body']) ?></p>
              </div>
            </li>
          <?php endforeach; ?>
        </ol>
      </div>

      <div class="funnel" data-reveal>
        <?php foreach ($funnel as $i => $f): ?>
          <div class="funnel__stage" data-stage style="--i:<?= $i ?>">
            <span class="funnel__fill"></span>
            <span class="chip mono"><?= sprintf('%02d', $i + 1) ?> <?= e($f['stage']) ?></span>
            <b><?= e($f['tools']) ?></b>
          </div>
        <?php endforeach; ?>
        <p class="funnel__note mono"><span class="dot"></span>How the channels hand buyers to each other</p>
      </div>
    </div>
  </section>

  <!-- ============ RESULTS DASHBOARD ============ -->
  <section class="section results" id="results">
    <div class="container">
      <div class="head">
        <div>
          <p class="tag" data-reveal><span class="dot"></span>(05) Results</p>
          <h2 class="h2" data-reveal>What the work <mark class="hl">added up to.</mark></h2>
        </div>
        <p class="lead head__side" data-reveal>Figures from our published case studies, not averages or projections. Bars show before and after, drawn to scale.</p>
      </div>

      <div class="board" data-board data-reveal>
        <div class="board__bar">
          <p class="mono"><span class="dot dot--live"></span>Webincline · case study results</p>
          <div class="board__tabs" role="tablist" aria-label="Result type">
            <button type="button" role="tab" class="is-on" aria-selected="true" data-board-tab="seo">SEO</button>
            <button type="button" role="tab" aria-selected="false" data-board-tab="ads">Paid ads</button>
          </div>
          <p class="mono board__src">2016 – 2023</p>
        </div>
        <?php foreach ($dashboard as $key => $tiles): ?>
          <div class="board__grid" data-board-panel="<?= e($key) ?>" <?= $key !== 'seo' ? 'hidden' : '' ?>>
            <?php foreach ($tiles as $i => $t): $max = max($t['bars']); ?>
              <div class="tile">
                <p class="mono tile__label"><?= sprintf('%02d', $i + 1) ?> <?= e($t['label']) ?></p>
                <p class="tile__value"><?= e($t['value']) ?></p>
                <span class="chip mono"><?= e($t['client']) ?></span>
                <div class="tile__bars" aria-label="Before <?= e((string) $t['bars'][0]) ?>, after <?= e((string) $t['bars'][1]) ?>">
                  <span style="--h:<?= round($t['bars'][0] / $max * 100, 2) ?>%"><em class="mono">Before</em></span>
                  <span class="is-after" style="--h:100%"><em class="mono">After</em></span>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="ticker marquee mono" aria-label="Recent wins">
      <div class="marquee__track">
        <?php for ($i = 0; $i < 2; $i++): foreach ($wins as $w): ?>
          <span<?= $i ? ' aria-hidden="true"' : '' ?>><span class="dot dot--live"></span><?= e($w) ?></span>
        <?php endforeach; endfor; ?>
      </div>
    </div>
  </section>

  <!-- ============ CLIENT STORIES ============ -->
  <section class="section stories" id="stories">
    <div class="container">
      <div class="head">
        <div>
          <p class="tag" data-reveal><span class="dot"></span>(06) Client stories</p>
          <h2 class="h2" data-reveal>Straight from <mark class="hl">our clients.</mark></h2>
        </div>
        <p class="lead head__side" data-reveal>Business owners, on camera, on what changed after working with us. Tap a story to play it with sound.</p>
      </div>
      <div class="reels">
        <?php foreach ($testimonials as $i => $t): ?>
          <figure class="reel" data-reveal>
            <video preload="none" playsinline poster="<?= asset('video/' . $t['video'] . '.jpg') ?>" data-video>
              <source src="<?= asset('video/' . $t['video'] . '.mp4') ?>" type="video/mp4">
            </video>
            <div class="reel__top"><span class="reel__avatar"><?= e(mb_substr(trim($t['company'], '[]'), 0, 1)) ?></span><span><b><?= e($t['company']) ?></b><small class="mono">Client story <?= sprintf('%02d', $i + 1) ?></small></span></div>
            <button class="reel__play" type="button" data-video-play aria-label="Play story from <?= e($t['company']) ?>"><?= icon('play') ?></button>
            <figcaption><b><?= e($t['name']) ?></b><span><?= e($t['company']) ?></span></figcaption>
          </figure>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============ TEAM ============ -->
  <section class="section team" id="team">
    <div class="container">
      <div class="head">
        <div>
          <p class="tag" data-reveal><span class="dot"></span>(07) Team</p>
          <h2 class="h2" data-reveal>One specialist <mark class="hl">per channel.</mark></h2>
        </div>
        <div class="head__side" data-reveal>
          <p class="lead">Every account gets a senior lead for each channel it uses. The people who plan your work are the people who do it.</p>
          <p class="mono team__count"><span class="dot dot--live"></span>Specialists met <b data-team-count>01</b>/<?= sprintf('%02d', count($team)) ?></p>
        </div>
      </div>
      <ul class="team-row">
        <?php foreach ($team as $i => $m): ?>
          <li class="member" data-member data-reveal>
            <div class="member__photo">
              <span class="chip mono"><?= e($m['focus']) ?></span>
              <span class="member__num mono"><?= sprintf('%02d', $i + 1) ?></span>
              <img src="<?= asset('img/brand/webincline-icon.webp') ?>" alt="" loading="lazy" width="80" height="43">
            </div>
            <h3><?= e($m['name']) ?></h3>
            <p><?= e($m['role']) ?></p>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <!-- ============ FAQ ============ -->
  <section class="section faq" id="faq">
    <div class="container faq__grid">
      <div class="faq__side">
        <p class="tag" data-reveal><span class="dot"></span>(08) FAQ</p>
        <h2 class="h2" data-reveal>Good to know <mark class="hl">before we start.</mark></h2>
        <div class="ask" data-reveal>
          <p class="mono"><span class="dot dot--live"></span>Still deciding?</p>
          <h3>Talk it through with us on a call.</h3>
          <p>We look at your website, ads and socials with you and suggest the quickest wins — you keep the advice either way.</p>
          <a class="btn btn--primary btn--sm" href="#contact"><span>Book a call</span><span class="btn__icon"><?= icon('arrow-up') ?></span></a>
          <p class="ask__mail">Or write to <span class="selectable"><?= e($site['email']) ?></span></p>
        </div>
      </div>
      <div class="faq__list" data-faq>
        <span class="faq__rail" aria-hidden="true"><span data-faq-rail></span></span>
        <?php foreach ($faqs as $i => $f): ?>
          <details class="qa" <?= $i === 0 ? 'open' : '' ?>>
            <summary>
              <span class="mono qa__num"><?= sprintf('%02d', $i + 1) ?></span>
              <span class="qa__q"><?= e($f['q']) ?></span>
              <span class="chip mono qa__tag"><?= e($f['tag']) ?></span>
              <span class="qa__icon"><?= icon('plus') ?></span>
            </summary>
            <p class="qa__a"><?= e($f['a']) ?></p>
          </details>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============ INSIGHTS ============ -->
  <section class="section insights" id="insights">
    <div class="container">
      <div class="head">
        <div>
          <p class="tag" data-reveal><span class="dot"></span>(09) Insights</p>
          <h2 class="h2" data-reveal>Notes from <mark class="hl">the work.</mark></h2>
        </div>
        <div class="head__side" data-reveal>
          <p class="lead">What we learn running campaigns every week, written up so you can use it too.</p>
          <a class="btn btn--ghost btn--sm" href="<?= e(BLOG_BASE) ?>/blogs"><span>All articles</span><span class="btn__icon"><?= icon('arrow-up') ?></span></a>
        </div>
      </div>
      <div class="posts">
        <?php foreach ($posts as $i => $p): ?>
          <article class="post<?= $i === 0 ? ' post--lead' : '' ?>" data-reveal>
            <a href="<?= e(BLOG_BASE . $p['path']) ?>">
              <div class="post__cover post__cover--<?= $i ?>" aria-hidden="true">
                <span class="post__word"><?= e($p['category']) ?></span>
                <img src="<?= asset('img/brand/webincline-icon.webp') ?>" alt="" loading="lazy" width="80" height="43">
              </div>
              <div class="post__body">
                <p class="mono post__meta"><span class="chip"><?= e($p['category']) ?></span><?= e($p['date']) ?> · <?= e($p['read']) ?></p>
                <h3><?= e($p['title']) ?></h3>
                <p><?= e($p['excerpt']) ?></p>
              </div>
              <span class="post__arrow"><?= icon('arrow-up') ?></span>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============ CONTACT ============ -->
  <section class="contact" id="contact">
    <div class="contact__rings" aria-hidden="true"></div>
    <div class="container contact__grid">
      <div>
        <p class="tag" data-reveal><span class="dot"></span>(10) Let’s talk</p>
        <h2 class="contact__title" data-reveal>Ready to <mark class="hl">grow?</mark></h2>
        <p class="lead" data-reveal>Tell us about your business. We look at your website, ads and socials and come back with the quickest wins.</p>

        <form class="form" action="/contact-submit.php" method="post" novalidate data-contact-form data-reveal>
          <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
          <input type="hidden" name="ts" value="<?= time() ?>">
          <div class="hp" aria-hidden="true"><label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

          <div class="form__row">
            <div class="field"><label for="f-name">Name *</label><input id="f-name" name="name" type="text" autocomplete="name" required maxlength="100" placeholder="Your name"></div>
            <div class="field"><label for="f-email">Work email *</label><input id="f-email" name="email" type="email" autocomplete="email" required maxlength="150" placeholder="you@company.com"></div>
          </div>
          <div class="form__row">
            <div class="field"><label for="f-phone">Phone / WhatsApp</label><input id="f-phone" name="phone" type="tel" autocomplete="tel" maxlength="30" placeholder="+91 …"></div>
            <div class="field">
              <label for="f-need">What do you need?</label>
              <select id="f-need" name="services[]">
                <option value="">Choose a channel</option>
                <?php foreach ($channels as $c): ?><option><?= e($c['title']) ?></option><?php endforeach; ?>
                <option>Not sure yet</option>
              </select>
            </div>
          </div>
          <div class="field">
            <label for="f-country">Country</label>
            <select id="f-country" name="country">
              <option value="">Select country</option>
              <?php foreach ($site['markets'] as $m): ?><option><?= e($m) ?></option><?php endforeach; ?>
              <option>Other</option>
            </select>
          </div>
          <div class="field"><label for="f-message">About your business *</label><textarea id="f-message" name="message" rows="3" required maxlength="3000" placeholder="What do you sell, and what would you like to grow?"></textarea></div>
          <div class="form__foot">
            <button class="btn btn--primary" type="submit"><span>Send message</span><span class="btn__icon"><?= icon('arrow-up') ?></span></button>
            <p class="form__status mono" role="status" aria-live="polite" data-form-status><?php
              if ($form_status === 'sent') echo 'Thanks — your message is on its way.';
              elseif ($form_status === 'error') echo 'Something went wrong. Please email ' . e($site['email']) . '.';
              else echo 'No spam. We reply by email or WhatsApp.';
            ?></p>
          </div>
        </form>
      </div>

      <aside class="office" data-reveal>
        <p class="mono"><span class="dot dot--live"></span>Our time · Chandigarh</p>
        <p class="office__clock" data-clock>--:--:--</p>
        <ul class="office__list">
          <li><?= icon('mail') ?><span class="selectable"><?= e($site['email']) ?></span></li>
          <?php foreach ($site['phones'] as $p): ?>
            <li><?= icon('phone') ?><a href="tel:<?= e($p['tel']) ?>"><?= e($p['label']) ?></a></li>
          <?php endforeach; ?>
          <li><?= icon('whatsapp') ?><a href="https://wa.me/<?= e($site['whatsapp']) ?>" target="_blank" rel="noopener">Chat on WhatsApp</a></li>
          <li><?= icon('pin') ?><span><?= e($site['address']['line1']) ?>, <?= e($site['address']['city']) ?>, <?= e($site['address']['country']) ?></span></li>
        </ul>
        <p class="mono office__markets"><?= e(implode(' · ', $site['markets'])) ?></p>
      </aside>
    </div>
  </section>

</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
