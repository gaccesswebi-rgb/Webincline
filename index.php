<?php
declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/data.php';

$csrf = csrf_token();
$form_status = $_GET['status'] ?? '';

$page_title = 'Webincline — Digital Marketing Agency | SEO, Ads, Websites, CRM & AI';

require __DIR__ . '/includes/header.php';
?>
<main id="main">

  <!-- ============ HERO ============ -->
  <section class="hero" data-hero>
    <div class="hero__glow" aria-hidden="true">
      <span class="glow glow--orange"></span>
      <span class="glow glow--violet"></span>
      <span class="glow glow--pink"></span>
      <span class="glow glow--blue"></span>
    </div>
    <canvas class="hero__particles" data-particles aria-hidden="true"></canvas>

    <svg class="hero__flight" viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
      <defs>
        <linearGradient id="trail-grad" x1="0" y1="1" x2="1" y2="0">
          <stop offset="0" stop-color="#FF7A00"/>
          <stop offset=".4" stop-color="#FF3D81"/>
          <stop offset=".75" stop-color="#7B4DFF"/>
          <stop offset="1" stop-color="#3A8DFF"/>
        </linearGradient>
        <mask id="trail-mask" maskUnits="userSpaceOnUse">
          <path class="flight-reveal" data-flight-reveal d="M-80 780 C 200 770, 380 700, 540 600 C 720 488, 780 372, 676 326 C 566 278, 512 436, 650 480 C 830 540, 1040 330, 1180 205 C 1270 125, 1350 70, 1530 10" stroke="#fff" stroke-width="8" fill="none"/>
        </mask>
      </defs>
      <path class="flight-path" data-flight-path d="M-80 780 C 200 770, 380 700, 540 600 C 720 488, 780 372, 676 326 C 566 278, 512 436, 650 480 C 830 540, 1040 330, 1180 205 C 1270 125, 1350 70, 1530 10" fill="none"/>
      <path class="flight-trail" mask="url(#trail-mask)" d="M-80 780 C 200 770, 380 700, 540 600 C 720 488, 780 372, 676 326 C 566 278, 512 436, 650 480 C 830 540, 1040 330, 1180 205 C 1270 125, 1350 70, 1530 10" fill="none" stroke="url(#trail-grad)"/>
      <g class="flight-plane" data-flight-plane>
        <path d="M-16 -11 L18 0 L-16 11 L-9 0 Z" fill="#3A8DFF"/>
        <path d="M-9 0 L18 0 L-16 11 Z" fill="#2a6fd1"/>
      </g>
    </svg>

    <div class="container hero__content">
      <p class="eyebrow-chip" data-hero-item>
        <span class="pulse-dot" aria-hidden="true"></span>
        Digital marketing agency · Chandigarh to the world
      </p>

      <h1 class="hero__title">
        <span class="line"><span class="line__inner">Scaling ideas.</span></span>
        <span class="line"><span class="line__inner">Building <span class="text-grad">growth.</span></span></span>
      </h1>

      <p class="hero__lead" data-hero-item>
        We run SEO, Google &amp; Meta ads, social media, websites, CRM and AI automation for brands in India, Canada, the USA, the UK, Australia and beyond — and report every result in plain numbers.
      </p>

      <div class="hero__actions" data-hero-item>
        <a class="btn btn--primary" href="#contact"><span>Book a strategy call</span><?= icon('arrow') ?></a>
        <a class="btn btn--ghost" href="#work"><span>See our results</span></a>
      </div>

      <ul class="hero__stats" data-hero-item>
        <?php foreach ($site['stats'] as $stat): ?>
          <li>
            <strong><span data-count="<?= (int) $stat['value'] ?>"><?= (int) $stat['value'] ?></span><?= e($stat['suffix']) ?></strong>
            <span class="hero__stats__label"><?= e($stat['label']) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <a class="scroll-cue" href="#clients" aria-label="Scroll to next section"><span></span></a>
  </section>

  <!-- ============ SERVICE MARQUEE ============ -->
  <div class="marquee marquee--band" aria-hidden="true">
    <div class="marquee__track">
      <?php for ($i = 0; $i < 2; $i++): ?>
        <?php foreach ($services as $s): ?>
          <span class="marquee__item"><?= e($s['title']) ?></span><span class="marquee__dot"></span>
        <?php endforeach; ?>
      <?php endfor; ?>
    </div>
  </div>

  <!-- ============ CLIENTS ============ -->
  <section class="section clients" id="clients">
    <div class="container">
      <div class="section-head section-head--split">
        <div>
          <p class="eyebrow" data-reveal><span>01</span> Clients</p>
          <h2 class="section-title" data-split>Brands that grow with us.</h2>
        </div>
        <p class="section-lead" data-reveal>From hospitals and colleges in Chandigarh to renovation firms in Dubai and robotics brands in Australia — <?= e((string) $site['stats'][1]['value']) ?>+ businesses have trusted us to get them found by the right people.</p>
      </div>
    </div>

    <?php $client_rows = array_chunk($clients, (int) ceil(count($clients) / 2), true); ?>
    <div class="logo-marquee" data-reveal>
      <?php foreach ($client_rows as $r => $row): ?>
        <div class="marquee <?= $r ? 'marquee--reverse' : '' ?>">
          <div class="marquee__track">
            <?php for ($i = 0; $i < 2; $i++): ?>
              <?php foreach ($row as $slug => $name): ?>
                <span class="logo-tile" <?= $i ? 'aria-hidden="true"' : '' ?>>
                  <img src="<?= asset('img/clients/' . $slug . '.webp') ?>" alt="<?= $i ? '' : e($name) ?>" loading="lazy" width="180" height="80">
                </span>
              <?php endforeach; ?>
            <?php endfor; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="container">
      <div class="markets" data-reveal>
        <span class="markets__label">Clients in</span>
        <ul>
          <?php foreach ($site['markets'] as $m): ?>
            <li><?= e($m) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </section>

  <!-- ============ WHY ============ -->
  <section class="section why" id="why">
    <div class="container">
      <p class="eyebrow" data-reveal><span>02</span> Why Webincline</p>
      <p class="why__statement" data-scrub-words>Like having your own digital marketing branch — without the headaches and costs of hiring in-house. Strategy, content, ads, websites and automation under one roof, with one team that owns the numbers.</p>

      <div class="why__grid">
        <article class="feature-card" data-reveal>
          <span class="feature-card__icon"><?= icon('target') ?></span>
          <h3>Custom strategy</h3>
          <p>Built on an audit of your business, market and competitors. You get every channel you need — and nothing you don’t.</p>
        </article>
        <article class="feature-card" data-reveal>
          <span class="feature-card__icon"><?= icon('users') ?></span>
          <h3>A dedicated team</h3>
          <p>Specialists for each channel, an account manager and a senior strategist — the same people every month.</p>
        </article>
        <article class="feature-card" data-reveal>
          <span class="feature-card__icon"><?= icon('chart') ?></span>
          <h3>Driven by ROI</h3>
          <p>We measure leads, sales and cost per result, so every decision is made on revenue, not vanity metrics.</p>
        </article>
      </div>

      <div class="industries" data-reveal>
        <span class="industries__label">Industries we know best</span>
        <ul>
          <?php foreach ($industries as $ind): ?>
            <li><?= e($ind) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </section>

  <!-- ============ SERVICES ============ -->
  <section class="section services" id="services">
    <div class="container">
      <div class="section-head section-head--split">
        <div>
          <p class="eyebrow" data-reveal><span>03</span> Services</p>
          <h2 class="section-title" data-split>Every channel. One growth plan.</h2>
        </div>
        <p class="section-lead" data-reveal>Twelve services across four goals. Start with one, or let one team run them all and report everything in one place.</p>
      </div>

      <div class="tabs" data-tabs>
        <div class="tabs__list" role="tablist" aria-label="Service groups">
          <?php $first = true; foreach ($service_groups as $key => $label): ?>
            <?php $count = count(array_filter($services, fn($s) => $s['group'] === $key)); ?>
            <button class="tabs__tab" role="tab" type="button" id="tab-<?= e($key) ?>" aria-controls="panel-<?= e($key) ?>" aria-selected="<?= $first ? 'true' : 'false' ?>" <?= $first ? '' : 'tabindex="-1"' ?>>
              <?= e($label) ?> <span class="tabs__count"><?= $count ?></span>
            </button>
          <?php $first = false; endforeach; ?>
          <span class="tabs__indicator" aria-hidden="true"></span>
        </div>

        <?php $n = 0; $first = true; foreach ($service_groups as $key => $label): ?>
          <div class="tabs__panel" role="tabpanel" id="panel-<?= e($key) ?>" aria-labelledby="tab-<?= e($key) ?>" <?= $first ? '' : 'hidden' ?>>
            <div class="service-grid">
              <?php foreach ($services as $s): if ($s['group'] !== $key) continue; $n++; ?>
                <article class="service-card">
                  <div class="service-card__top">
                    <span class="service-card__num"><?= sprintf('%02d', $n) ?></span>
                    <span class="service-card__proof"><?= e($s['proof']) ?></span>
                  </div>
                  <h3><?= e($s['title']) ?></h3>
                  <p class="service-card__line"><?= e($s['line']) ?></p>
                  <p class="service-card__body"><?= e($s['body']) ?></p>
                  <?php if (!empty($s['platforms'])): ?>
                    <ul class="platforms">
                      <?php foreach ($s['platforms'] as $p): ?><li><?= e($p) ?></li><?php endforeach; ?>
                    </ul>
                  <?php endif; ?>
                  <ul class="checklist">
                    <?php foreach ($s['includes'] as $inc): ?>
                      <li><?= icon('check') ?><?= e($inc) ?></li>
                    <?php endforeach; ?>
                  </ul>
                  <?php $url = page_url('/services/' . $s['slug']); ?>
                  <a class="service-card__link" href="<?= e($url ?? '#contact') ?>">
                    <?= $url ? 'Explore ' . e($s['title']) : 'Talk to us about this' ?><?= icon('arrow-up') ?>
                  </a>
                </article>
              <?php endforeach; ?>
              <?php if (count(array_filter($services, fn($s) => $s['group'] === $key)) % 3 === 2): ?>
                <article class="service-card service-card--cta">
                  <div>
                    <h3>Not sure where to start?</h3>
                    <p>Tell us your goal. We will suggest the two or three channels that will move your numbers fastest.</p>
                  </div>
                  <a class="btn btn--primary btn--sm" href="#contact"><span>Get a recommendation</span><?= icon('arrow') ?></a>
                </article>
              <?php endif; ?>
            </div>
          </div>
        <?php $first = false; endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============ CASE STUDIES ============ -->
  <section class="section work" id="work">
    <div class="container">
      <div class="section-head section-head--split">
        <div>
          <p class="eyebrow" data-reveal><span>04</span> Case studies</p>
          <h2 class="section-title" data-split>Results you can count.</h2>
        </div>
        <p class="section-lead" data-reveal>Real clients, real dashboards. Every number below comes from campaigns we ran — before and after.</p>
      </div>

      <div class="filters" role="group" aria-label="Filter case studies" data-filters>
        <button type="button" class="filters__btn is-active" data-filter="all" aria-pressed="true">All work</button>
        <button type="button" class="filters__btn" data-filter="SEO" aria-pressed="false">SEO</button>
        <button type="button" class="filters__btn" data-filter="Paid ads" aria-pressed="false">Paid ads</button>
      </div>

      <div class="case-grid" data-case-grid>
        <?php foreach ($case_studies as $c): $m = $c['metric']; $single = !empty($m['single']); ?>
          <article class="case-card" data-service="<?= e($c['service']) ?>">
            <div class="case-card__tags">
              <span class="tag tag--<?= $c['service'] === 'SEO' ? 'orange' : 'pink' ?>"><?= e($c['service']) ?></span>
              <span class="case-card__market"><?= e($c['market']) ?></span>
            </div>
            <p class="case-card__client"><?= e($c['client']) ?></p>
            <h3 class="case-card__title"><?= e($c['title']) ?></h3>

            <div class="case-card__metric">
              <?php if (!$single): ?>
                <span class="case-card__before"><?= number_format($m['before']) ?></span>
                <span class="case-card__arrow" aria-hidden="true"><?= icon('arrow') ?></span>
              <?php endif; ?>
              <span class="case-card__after"><span data-count="<?= (int) $m['after'] ?>"><?= number_format($m['after']) ?></span><?= e($m['suffix'] ?? '') ?></span>
            </div>
            <p class="case-card__label"><?= e($m['label']) ?></p>

            <div class="case-card__foot">
              <span><?= e($c['extra']) ?></span>
              <span><?= e($c['period']) ?></span>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============ PROCESS ============ -->
  <section class="section process" id="process" data-process>
    <div class="container">
      <div class="section-head section-head--split">
        <div>
          <p class="eyebrow" data-reveal><span>05</span> How we work</p>
          <h2 class="section-title" data-split>From research to results.</h2>
        </div>
        <p class="section-lead" data-reveal>Every project runs the same five steps, so you always know what is happening and what comes next.</p>
      </div>

      <div class="process__rail" aria-hidden="true"><span class="process__fill" data-process-fill></span></div>

      <ol class="process__steps">
        <?php foreach ($process as $i => $step): ?>
          <li class="process-step" data-process-step>
            <span class="process-step__num"><?= sprintf('%02d', $i + 1) ?></span>
            <h3><?= e($step['title']) ?></h3>
            <p><?= e($step['body']) ?></p>
            <ul>
              <?php foreach ($step['tags'] as $t): ?><li><?= e($t) ?></li><?php endforeach; ?>
            </ul>
          </li>
        <?php endforeach; ?>
      </ol>
    </div>
  </section>

  <!-- ============ TESTIMONIALS ============ -->
  <section class="section testimonials" id="testimonials">
    <div class="container">
      <div class="section-head section-head--split">
        <div>
          <p class="eyebrow" data-reveal><span>06</span> Client stories</p>
          <h2 class="section-title" data-split>Hear it from our clients.</h2>
        </div>
        <p class="section-lead" data-reveal>No scripts, no actors — business owners on what changed after working with us.</p>
      </div>

      <div class="video-grid">
        <?php foreach ($testimonials as $t): ?>
          <figure class="video-card" data-reveal>
            <div class="video-card__media">
              <video preload="none" playsinline poster="<?= asset('video/' . $t['video'] . '.jpg') ?>" data-video>
                <source src="<?= asset('video/' . $t['video'] . '.mp4') ?>" type="video/mp4">
              </video>
              <button class="video-card__play" type="button" data-video-play aria-label="Play testimonial from <?= e($t['company']) ?>">
                <?= icon('play') ?>
              </button>
            </div>
            <figcaption>
              <strong><?= e($t['name']) ?></strong>
              <span><?= e($t['company']) ?></span>
            </figcaption>
          </figure>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============ TEAM ============ -->
  <section class="section team" id="team">
    <div class="container">
      <div class="section-head section-head--split">
        <div>
          <p class="eyebrow" data-reveal><span>07</span> Team</p>
          <h2 class="section-title" data-split>Specialists, not generalists.</h2>
        </div>
        <p class="section-lead" data-reveal>A senior lead for every channel you work with us on. No hand-offs, no juniors learning on your budget.</p>
      </div>

      <ul class="team-grid">
        <?php foreach ($team as $i => $m): ?>
          <li class="team-card" data-reveal>
            <div class="team-card__photo">
              <span class="team-card__focus"><?= e($m['focus']) ?></span>
              <span class="team-card__initial" aria-hidden="true"><?= sprintf('%02d', $i + 1) ?></span>
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
    <div class="container faq__layout">
      <div class="faq__intro">
        <p class="eyebrow" data-reveal><span>08</span> FAQ</p>
        <h2 class="section-title" data-split>Questions, answered.</h2>
        <div class="faq__help" data-reveal>
          <p>Still unsure? Ask us directly.</p>
          <a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a>
          <a href="https://wa.me/<?= e($site['whatsapp']) ?>" target="_blank" rel="noopener">WhatsApp us</a>
        </div>
      </div>
      <div class="faq__list">
        <?php foreach ($faqs as $i => $f): ?>
          <details class="faq-item" data-reveal <?= $i === 0 ? 'open' : '' ?>>
            <summary>
              <span class="faq-item__num"><?= sprintf('%02d', $i + 1) ?></span>
              <span class="faq-item__q"><?= e($f['q']) ?></span>
              <span class="faq-item__icon"><?= icon('plus') ?></span>
            </summary>
            <div class="faq-item__a"><p><?= e($f['a']) ?></p></div>
          </details>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============ INSIGHTS ============ -->
  <section class="section insights" id="insights">
    <div class="container">
      <div class="section-head section-head--split">
        <div>
          <p class="eyebrow" data-reveal><span>09</span> Insights</p>
          <h2 class="section-title" data-split>Ideas we test first.</h2>
        </div>
        <a class="btn btn--ghost btn--sm" href="<?= e(BLOG_BASE) ?>/blogs" data-reveal><span>All articles</span><?= icon('arrow') ?></a>
      </div>

      <div class="post-grid">
        <?php foreach ($posts as $p): ?>
          <article class="post-card" data-reveal>
            <a href="<?= e(BLOG_BASE . $p['path']) ?>">
              <div class="post-card__meta">
                <span class="tag"><?= e($p['category']) ?></span>
                <span><?= e($p['date']) ?> · <?= e($p['read']) ?></span>
              </div>
              <h3><?= e($p['title']) ?></h3>
              <p><?= e($p['excerpt']) ?></p>
              <span class="post-card__more">Read article <?= icon('arrow-up') ?></span>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============ CONTACT ============ -->
  <section class="section contact" id="contact">
    <div class="container">
      <div class="contact__card">
        <div class="contact__intro">
          <p class="eyebrow" data-reveal><span>10</span> Let’s talk</p>
          <h2 class="section-title" data-split>Ready to scale your idea?</h2>
          <p class="section-lead" data-reveal>Tell us where you are and where you want to be. We will look at your website, ads and socials and come back with the quickest wins.</p>

          <ul class="contact-list contact-list--lg" data-reveal>
            <li><?= icon('mail') ?><a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a></li>
            <li><?= icon('phone') ?>
              <span>
                <?php foreach ($site['phones'] as $i => $p): ?>
                  <a href="tel:<?= e($p['tel']) ?>"><?= e($p['label']) ?></a><?= $i < count($site['phones']) - 1 ? '<br>' : '' ?>
                <?php endforeach; ?>
              </span>
            </li>
            <li><?= icon('whatsapp') ?><a href="https://wa.me/<?= e($site['whatsapp']) ?>" target="_blank" rel="noopener">Chat on WhatsApp</a></li>
            <li><?= icon('pin') ?><span><?= e($site['address']['line1']) ?>,<br><?= e($site['address']['city']) ?>, <?= e($site['address']['country']) ?></span></li>
          </ul>
        </div>

        <form class="contact-form" action="/contact-submit.php" method="post" novalidate data-contact-form>
          <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
          <input type="hidden" name="ts" value="<?= time() ?>">
          <div class="hp" aria-hidden="true">
            <label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
          </div>

          <div class="field-row">
            <div class="field">
              <label for="f-name">Your name *</label>
              <input id="f-name" name="name" type="text" autocomplete="name" required maxlength="100">
            </div>
            <div class="field">
              <label for="f-email">Work email *</label>
              <input id="f-email" name="email" type="email" autocomplete="email" required maxlength="150">
            </div>
          </div>
          <div class="field-row">
            <div class="field">
              <label for="f-phone">Phone / WhatsApp</label>
              <input id="f-phone" name="phone" type="tel" autocomplete="tel" maxlength="30">
            </div>
            <div class="field">
              <label for="f-country">Country</label>
              <select id="f-country" name="country">
                <option value="">Select country</option>
                <?php foreach ($site['markets'] as $m): ?><option><?= e($m) ?></option><?php endforeach; ?>
                <option>Other</option>
              </select>
            </div>
          </div>
          <fieldset class="field field--chips">
            <legend>What do you need?</legend>
            <div class="chips">
              <?php foreach ($service_groups as $key => $label): ?>
                <label class="chip"><input type="checkbox" name="services[]" value="<?= e($label) ?>"><span><?= e($label) ?></span></label>
              <?php endforeach; ?>
              <label class="chip"><input type="checkbox" name="services[]" value="Not sure yet"><span>Not sure yet</span></label>
            </div>
          </fieldset>
          <div class="field">
            <label for="f-message">Tell us about your business *</label>
            <textarea id="f-message" name="message" rows="4" required maxlength="3000"></textarea>
          </div>

          <button class="btn btn--primary btn--block" type="submit"><span>Send message</span><?= icon('arrow') ?></button>
          <p class="form-status" role="status" aria-live="polite" data-form-status><?php
            if ($form_status === 'sent') echo 'Thanks — your message is on its way. We will reply soon.';
            elseif ($form_status === 'error') echo 'Something went wrong. Please email us at ' . e($site['email']) . '.';
          ?></p>
        </form>
      </div>
    </div>
  </section>

</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
