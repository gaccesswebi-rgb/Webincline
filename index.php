<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$page_sections = ['services', 'work', 'process', 'team', 'insights', 'faq', 'contact'];
$page_title  = 'Webincline — Digital Marketing Agency | SEO, Ads, Websites, CRM & AI';

$ring_r = ['local' => 0.3, 'regional' => 0.5, 'national' => 0.68, 'global' => 0.84];

require __DIR__ . '/includes/header.php';
?>
<main id="main">

  <!-- ===================== HERO ===================== -->
  <section class="hero sec-dark" data-hero data-chips="<?= e(json_encode($hero_chips)) ?>">
    <div class="hero__bg" aria-hidden="true">
      <img class="hero__img" src="<?= asset('img/photos/hero-crowd-1280.webp') ?>" srcset="<?= asset('img/photos/hero-crowd-828.webp') ?> 828w, <?= asset('img/photos/hero-crowd-1280.webp') ?> 1280w, <?= asset('img/photos/hero-crowd-1600.webp') ?> 1600w, <?= asset('img/photos/hero-crowd-2000.webp') ?> 2000w" sizes="100vw" alt="" width="2000" height="1330" fetchpriority="high" decoding="async">
      <canvas class="hero__gl" data-hero-gl></canvas>
    </div>
    <div class="hero__shade" aria-hidden="true"></div>
    <div class="hero__pops" data-hero-pops aria-hidden="true"></div>
    <div class="hero__cur" data-hero-cur aria-hidden="true"><i></i></div>

    <p class="hero__hint mono" aria-hidden="true"><i class="ping"></i>Move to reach · click to launch a campaign</p>

    <div class="wrap hero__in">
      <p class="note" data-hi><i class="live"></i>Taking new projects in <?= count($site['markets']) ?> markets</p>
      <h1 class="hero__h" data-hero-title><?= title('Scaling ideas.|Building *growth.*') ?></h1>
      <p class="hero__sub" data-hi>Webincline is a digital marketing agency in Chandigarh. We run SEO, paid ads, social media, websites, CRM and AI automation that put your brand in front of people ready to buy — and we report every result in plain numbers.</p>
      <div class="hero__cta" data-hi>
        <a class="btn btn--solid" href="#contact" data-mag><span class="btn__lb">Book a strategy call</span><span class="btn__ic"><?= icon('calendar') ?></span></a>
        <a class="btn btn--glass" href="#work" data-mag><span class="btn__lb">See our work</span><span class="btn__ic"><?= icon('arrow-up') ?></span></a>
      </div>
    </div>

    <div class="wrap hero__foot" data-hi>
      <div class="hero__count">
        <p class="mono"><i class="ping"></i>Brands grown with Webincline</p>
        <p class="hero__num"><span class="odo" data-odo="500">500</span><span class="hero__plus">+</span></p>
      </div>
      <ul class="hero__tags mono">
        <?php foreach ($channels as $i => $c): ?>
          <li><a href="#services" data-goto-channel="<?= $i ?>"><b><?= sprintf('%02d', $i + 1) ?></b> <?= e($c['short']) ?></a></li>
        <?php endforeach; ?>
      </ul>
      <p class="hero__meta mono">11+ years in digital<br><?= count($site['markets']) ?> markets served</p>
    </div>
  </section>

  <!-- ===================== CLIENTS RADAR ===================== -->
  <section class="sec sec-light cl" id="clients" data-radar-sec>
    <div class="wrap cl__grid">
      <div class="cl__txt">
        <p class="eb" data-rv><i></i>(00) Clients</p>
        <h2 class="h2" data-hd><?= title('500+ brands|*on our radar.*') ?></h2>
        <p class="lead" data-rv>Clinics and colleges in Chandigarh, renovation firms in Dubai, robotics stores in Australia and service companies across Canada and the USA. Each came to us to be found by more of the right people.</p>
        <div class="cl__meta mono" data-rv>
          <p>11+ years · 21 brands below · <?= count($site['markets']) ?> markets</p>
          <p><i class="live"></i>Last ping <b data-radar-out>Beauty Basket · 60× ROAS</b></p>
        </div>
      </div>

      <div class="radar" data-radar data-rv>
        <span class="radar__deg radar__deg--n mono">000°</span>
        <span class="radar__deg radar__deg--e mono">090°</span>
        <span class="radar__deg radar__deg--s mono">180°</span>
        <span class="radar__deg radar__deg--w mono">270°</span>
        <div class="radar__dial">
          <svg class="radar__ticks" viewBox="0 0 200 200" aria-hidden="true">
            <?php for ($t = 0; $t < 72; $t++): $a = deg2rad($t * 5); $l = $t % 6 === 0 ? 7 : 3.5; ?>
              <line x1="<?= round(100 + sin($a) * 99, 2) ?>" y1="<?= round(100 - cos($a) * 99, 2) ?>" x2="<?= round(100 + sin($a) * (99 - $l), 2) ?>" y2="<?= round(100 - cos($a) * (99 - $l), 2) ?>"/>
            <?php endfor; ?>
          </svg>
          <?php foreach ($ring_r as $ring => $r): ?>
            <span class="radar__ring" style="--r:<?= $r ?>"><em class="mono"><?= e(ucfirst($ring)) ?></em></span>
          <?php endforeach; ?>
          <span class="radar__sweep" data-radar-sweep></span>
          <span class="radar__core"><i></i></span>
        </div>
        <ul class="radar__blips">
          <?php foreach ($radar as $p):
            $r = $ring_r[$p['ring']];
            $x = 50 + sin(deg2rad($p['angle'])) * $r * 50;
            $y = 50 - cos(deg2rad($p['angle'])) * $r * 50; ?>
            <li class="blip<?= $x > 56 ? ' is-l' : '' ?>" style="left:<?= round($x, 2) ?>%;top:<?= round($y, 2) ?>%" data-angle="<?= (int) $p['angle'] ?>" data-label="<?= e($p['name'] . ' · ' . $p['metric']) ?>">
              <span class="blip__dot"></span>
              <span class="blip__nm"><?= e($p['name']) ?></span>
              <span class="blip__bdg"><?= e($p['metric']) ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <?php $rows = array_chunk($clients, (int) ceil(count($clients) / 2), true); ?>
    <div class="logos" data-rv>
      <?php foreach ($rows as $r => $row): ?>
        <div class="mq<?= $r ? ' mq--rev' : '' ?>">
          <div class="mq__track">
            <?php for ($i = 0; $i < 2; $i++): foreach ($row as $slug => $name): ?>
              <span class="logo"<?= $i ? ' aria-hidden="true"' : '' ?>><img src="<?= asset('img/clients/' . $slug . '.webp') ?>" alt="<?= $i ? '' : e($name) ?>" loading="lazy" width="150" height="64"></span>
            <?php endforeach; endfor; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ===================== WHY / CROWD ===================== -->
  <section class="sec sec-light why" id="why">
    <div class="wrap">
      <div class="head">
        <div>
          <p class="eb" data-rv><i></i>(01) Why Webincline</p>
          <h2 class="h2" data-hd><?= title('Everyone online is a crowd.|We find *your buyers.*') ?></h2>
        </div>
        <div class="head__side" data-rv>
          <p class="lead">Most businesses are not invisible. They are talking to the wrong people. We find the searches, feeds, lookalike audiences and AI answers where your buyers already spend time — and put you there.</p>
          <a class="btn btn--line" href="#team" data-mag><span class="btn__lb">Meet the team</span><span class="btn__ic"><?= icon('arrow-up') ?></span></a>
          <ul class="facts mono"><li>Chandigarh HQ</li><li><?= count($site['markets']) ?> markets</li><li>11+ years</li></ul>
        </div>
      </div>

      <div class="crowd sec-dark" data-crowd data-rv>
        <canvas class="crowd__cv" data-crowd-cv aria-hidden="true"></canvas>
        <p class="crowd__top mono"><i class="live"></i>Illustration · <b data-crowd-n>1,200</b> people in the crowd</p>
        <?php foreach ($crowd_clusters as $c): ?>
          <div class="crowd__tag crowd__tag--<?= e($c['key']) ?><?= in_array($c['key'], ['social', 'ai'], true) ? ' crowd__tag--up' : '' ?>" data-cluster="<?= e($c['key']) ?>">
            <b class="crowd__chip mono"><?= e($c['label']) ?></b>
            <span class="crowd__note mono"><?= e($c['note']) ?></span>
          </div>
        <?php endforeach; ?>
        <p class="crowd__big"><span class="crowd__one">1 in</span> <b class="grad" data-crowd-ratio>138</b><span class="crowd__is">is your buyer</span></p>
      </div>
    </div>
  </section>

  <!-- ===================== SERVICES ===================== -->
  <section class="sec sec-light sv" id="services" data-sv>
    <div class="wrap">
      <div class="head">
        <div>
          <p class="eb" data-rv><i></i>(02) Services</p>
          <h2 class="h2" data-hd><?= title('Seven channels.|*One* growth plan.') ?></h2>
        </div>
        <div class="head__side" data-rv>
          <p class="lead">SEO, ads, social, websites, CRM, automation and email marketing — one team, one plan and one report.</p>
          <a class="btn btn--line" href="#contact" data-mag><span class="btn__lb">Plan my channels</span><span class="btn__ic"><?= icon('arrow-up') ?></span></a>
        </div>
      </div>

      <div class="acc" data-acc data-rv>
        <?php foreach ($channels as $i => $c): $open = $i === 0; ?>
          <article class="pn pn--<?= e($c['theme']) ?><?= $open ? ' is-open' : '' ?>" data-pn>
            <button class="pn__sp" type="button" id="pn-<?= e($c['slug']) ?>-b" aria-expanded="<?= $open ? 'true' : 'false' ?>" aria-controls="pn-<?= e($c['slug']) ?>">
              <span class="pn__num mono"><?= sprintf('%02d', $i + 1) ?></span>
              <span class="pn__vt"><?= e($c['title']) ?></span>
              <span class="pn__plus" aria-hidden="true"><i></i><i></i></span>
            </button>
            <div class="pn__c" id="pn-<?= e($c['slug']) ?>" role="region" aria-labelledby="pn-<?= e($c['slug']) ?>-b">
              <div class="pn__pin">
                <div class="pn__vis"><?= channel_visual($c['visual']) ?></div>
                <div class="pn__tx">
                  <h3><?= e($c['title']) ?></h3>
                  <p class="pn__line"><?= e($c['line']) ?></p>
                  <p class="pn__body"><?= e($c['body']) ?></p>
                  <div class="pn__inc">
                    <p class="mono">What’s included</p>
                    <ul><?php foreach ($c['includes'] as $inc): ?><li><span class="ck"><?= icon('check') ?></span><?= e($inc) ?></li><?php endforeach; ?></ul>
                  </div>
                  <div class="pn__foot">
                    <span class="pn__stat mono"><i class="live"></i><?= e($c['proof']) ?></span>
                    <a class="btn btn--solid btn--sm" href="<?= e(service_link($c['pillar'])) ?>" data-mag><span class="btn__lb"><?= page_url('/services') ? 'Explore ' . e($services[$c['pillar']]['title']) : 'Talk to us' ?></span><span class="btn__ic"><?= icon('arrow-up') ?></span></a>
                  </div>
                </div>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <div class="sv__rail">
        <div class="sv__seg" aria-hidden="true"><?php foreach ($channels as $i => $c): ?><i class="<?= $i ? '' : 'is-on' ?>"></i><?php endforeach; ?></div>
        <p class="sv__now mono"><b data-sv-idx>01 / <?= sprintf('%02d', count($channels)) ?></b><span data-sv-name><?= e($channels[0]['title']) ?></span></p>
        <p class="sv__hint mono"><i class="live"></i>Hover a channel, or let it play</p>
      </div>
    </div>
  </section>

  <!-- ===================== CASE STUDIES ===================== -->
  <section class="sec sec-dark wk" id="work" data-wk>
    <div class="wrap">
      <div class="head">
        <div>
          <p class="eb" data-rv><i></i>(03) Case studies</p>
          <h2 class="h2" data-hd><?= title('Results you can|*count.*') ?></h2>
        </div>
        <div class="head__side" data-rv>
          <p class="lead">Six clients, four countries, one habit: we show the number that pays the bills — before we started and after.</p>
          <a class="btn btn--glass" href="<?= e(BLOG_BASE) ?>/seo-case-study" data-mag><span class="btn__lb">All case studies</span><span class="btn__ic"><?= icon('arrow-up') ?></span></a>
        </div>
      </div>

      <div class="wk__tabs" role="tablist" aria-label="Case studies" data-rv>
        <?php foreach ($case_studies as $i => $c): ?>
          <button class="wk__tab" role="tab" type="button" id="wk-tab-<?= $i ?>" aria-controls="wk-case-<?= $i ?>" aria-selected="<?= $i ? 'false' : 'true' ?>" <?= $i ? 'tabindex="-1"' : '' ?>>
            <img src="<?= asset('img/photos/' . $c['img'] . '-thumb.webp') ?>" alt="" width="40" height="40" loading="lazy">
            <span class="wk__tt"><b><?= e($c['short']) ?></b><span class="mono"><?= number_format($c['metric']['before']) ?> → <?= number_format($c['metric']['after']) ?></span></span>
            <span class="wk__prog" aria-hidden="true"></span>
          </button>
        <?php endforeach; ?>
      </div>

      <div class="wk__stage">
        <?php foreach ($case_studies as $i => $c):
          $m = $c['metric'];
          $img = asset('img/photos/' . $c['img'] . '.webp');
          $srcset = asset('img/photos/' . $c['img'] . '-800.webp') . ' 800w, ' . asset('img/photos/' . $c['img'] . '-1200.webp') . ' 1200w, ' . $img . ' 1600w';
          $sizes = '(max-width: 640px) 170vw, min(92vw, 1520px)';
          // month-by-month shape of the climb (illustrative between the two real numbers)
          $wd = wave_path(wave_points(wave_values($c['short'], 13), 1000, 276, 40));
          $rb = wave_rng($c['short'] . '/before');
          $bl = [];
          for ($k = 0; $k <= 12; $k++) { $bl[] = [$k / 12 * 1000, 262 + ($k ? ($rb() - .5) * 14 : 0)]; }
        ?>
          <div class="wk__case<?= $i ? '' : ' is-on' ?>" role="tabpanel" id="wk-case-<?= $i ?>" aria-labelledby="wk-tab-<?= $i ?>" <?= $i ? 'hidden' : '' ?>>
            <div class="cmp" data-cmp>
              <div class="cmp__after">
                <img src="<?= $img ?>" srcset="<?= $srcset ?>" sizes="<?= $sizes ?>" alt="<?= e($c['client']) ?>" width="1600" height="1066" loading="lazy" decoding="async">
                <svg class="cmp__ch cmp__ch--a" viewBox="0 0 1000 300" preserveAspectRatio="none" aria-hidden="true">
                  <defs>
                    <linearGradient id="cmp-ln-<?= $i ?>" x1="0" x2="1"><stop offset="0" stop-color="#FF7A00"/><stop offset=".36" stop-color="#FF3D81"/><stop offset=".7" stop-color="#7B4DFF"/><stop offset="1" stop-color="#3A8DFF"/></linearGradient>
                    <linearGradient id="cmp-ar-<?= $i ?>" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="#FF3D81" stop-opacity=".42"/><stop offset=".55" stop-color="#7B4DFF" stop-opacity=".14"/><stop offset="1" stop-color="#7B4DFF" stop-opacity="0"/></linearGradient>
                  </defs>
                  <path class="cmp__ar" d="<?= $wd ?> L1000 300 L0 300Z" fill="url(#cmp-ar-<?= $i ?>)"/>
                  <path class="cmp__ln" d="<?= $wd ?>" stroke="url(#cmp-ln-<?= $i ?>)"/>
                </svg>
                <div class="cmp__a"><span class="chip chip--grad mono"><i class="live"></i>After · <?= e($m['to']) ?></span><b data-count="<?= (int) $m['after'] ?>" data-from="<?= (int) $m['before'] ?>"><?= number_format($m['after']) ?></b></div>
              </div>
              <div class="cmp__before">
                <img src="<?= $img ?>" srcset="<?= $srcset ?>" sizes="<?= $sizes ?>" alt="" width="1600" height="1066" loading="lazy" decoding="async">
                <svg class="cmp__ch cmp__ch--b" viewBox="0 0 1000 300" preserveAspectRatio="none" aria-hidden="true"><path class="cmp__fl" d="<?= wave_line($bl) ?>"/></svg>
                <div class="cmp__b"><span class="chip mono">Before · <?= e($m['from']) ?></span><b><?= number_format($m['before']) ?></b></div>
              </div>
              <p class="cmp__chip mono"><i class="live"></i><?= e($c['short']) ?> · <?= e($c['year']) ?></p>
              <p class="cmp__lbl mono"><?= e($m['label']) ?> · trend illustrative</p>
              <span class="cmp__handle" aria-hidden="true"><?= icon('drag') ?></span>
              <span class="cmp__cur mono" aria-hidden="true">Drag</span>
              <input class="cmp__range" type="range" min="3" max="97" value="86" aria-label="Compare before and after for <?= e($c['client']) ?>">
            </div>
            <div class="wk__info">
              <div>
                <p class="mono wk__kick"><?= e($c['market']) ?> · <?= e($c['year']) ?></p>
                <h3 class="wk__title"><a href="<?= e(BLOG_BASE . $c['url']) ?>"><?= e($c['title']) ?></a></h3>
              </div>
              <div>
                <p><?= e($c['summary']) ?></p>
                <div class="wk__row">
                  <ul class="pills mono"><?php foreach ($c['service'] as $sv): ?><li><?= e($sv) ?></li><?php endforeach; ?></ul>
                  <a class="btn btn--solid btn--sm" href="<?= e(BLOG_BASE . $c['url']) ?>" data-mag><span class="btn__lb">Read the case</span><span class="btn__ic"><?= icon('arrow-up') ?></span></a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

<?php require __DIR__ . '/includes/partials/funnel.php'; ?>

  <!-- ===================== RESULTS BAND ===================== -->
  <section class="sec sec-grad nm" id="results" data-nm>
    <div class="wrap">
      <div class="head">
        <div>
          <p class="eb eb--ink" data-rv><i></i>(05) The numbers</p>
          <h2 class="h2" data-hd><?= title('What the work|*added up to.*') ?></h2>
        </div>
        <p class="lead head__side" data-rv>Figures from our published case studies — not averages, not projections. Every client gets the same plain-number report.</p>
      </div>

      <div class="board sec-dark" data-board data-rv>
        <div class="board__bar">
          <p class="mono"><i class="live"></i>Client results <span class="tag-live mono">Case studies</span></p>
          <div class="board__tabs mono" role="tablist" aria-label="Result type">
            <button type="button" role="tab" class="is-act" aria-selected="true" data-board-tab="seo">SEO</button>
            <button type="button" role="tab" aria-selected="false" data-board-tab="ads">Paid ads</button>
          </div>
          <p class="mono board__src">2016 – 2023 · curves illustrative</p>
        </div>
        <?php foreach ($dashboard as $key => $tiles): ?>
          <div class="board__grid" data-board-panel="<?= e($key) ?>" <?= $key === 'seo' ? '' : 'hidden' ?>>
            <?php foreach ($tiles as $i => $t):
              $wv = wave_values($t['client'] . $t['label'], 12, 1.35, .08);
              $pts = wave_points($wv, 300, 74, 8);
              $d = wave_line($pts);
              $gid = 'tl-' . $key . '-' . $i;
            ?>
              <div class="tile">
                <p class="mono tile__l"><b><?= sprintf('%02d', $i + 1) ?></b> <?= e($t['label']) ?></p>
                <p class="tile__v"><?= e($t['value']) ?></p>
                <span class="chip mono"><i class="live"></i><?= e($t['client']) ?></span>
                <div class="tile__sp" style="--i:<?= $i ?>" data-from="<?= e($t['from']) ?>" data-to="<?= e($t['to']) ?>" role="img" aria-label="From <?= e($t['from']) ?> to <?= e($t['to']) ?>">
                  <svg viewBox="0 -4 300 88" preserveAspectRatio="none" aria-hidden="true">
                    <defs><linearGradient id="<?= $gid ?>" x1="0" x2="1"><stop offset="0" stop-color="#FF7A00"/><stop offset=".5" stop-color="#FF3D81"/><stop offset="1" stop-color="#7B4DFF"/></linearGradient></defs>
                    <path class="tile__area" d="<?= $d ?> L300 84 L0 84Z"/>
                    <path class="tile__ln" d="<?= $d ?>" style="stroke:url(#<?= $gid ?>)"/>
                  </svg>
                  <i class="tile__end" style="left:100%;top:<?= round((end($pts)[1] + 4) / 88 * 100, 2) ?>%"></i>
                  <i class="tile__pt" hidden></i><span class="tile__tt mono" hidden></span>
                </div>
                <p class="mono tile__ax"><span>Before</span><span>After</span></p>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="ticker mono" aria-label="Recent wins">
      <div class="ticker__in">
        <?php for ($i = 0; $i < 2; $i++): foreach ($wins as $w): ?>
          <span<?= $i ? ' aria-hidden="true"' : '' ?>><i></i><?= e($w) ?></span>
        <?php endforeach; endfor; ?>
      </div>
    </div>
  </section>

  <!-- ===================== CONTENT FEED ===================== -->
  <section class="sec sec-dark fd" id="content">
    <div class="wrap">
      <div class="head">
        <div>
          <p class="eb" data-rv><i></i>(06) Content</p>
          <h2 class="h2" data-hd><?= title('Content that|*stops the scroll.*') ?></h2>
        </div>
        <div class="head__side" data-rv>
          <p class="lead">We plan, shoot, edit and post every week — reels, carousels, stories and ad creatives made for how each platform works.</p>
          <ul class="pills mono"><li><i class="live"></i>Reels</li><li>Carousels</li><li>Stories</li><li>Ad creatives</li></ul>
          <a class="btn btn--glass" href="#services" data-goto-channel="2" data-mag><span class="btn__lb">Social media service</span><span class="btn__ic"><?= icon('arrow-up') ?></span></a>
        </div>
      </div>
    </div>
    <div class="feed" data-rv>
      <?php $cols = 5; for ($c = 0; $c < $cols; $c++): $items = array_merge(array_slice($feed, ($c * 2) % count($feed)), array_slice($feed, 0, ($c * 2) % count($feed))); $items = array_slice($items, 0, 4); ?>
        <div class="feed__col<?= $c % 2 ? ' is-down' : '' ?>" style="--d:<?= 38 + $c * 6 ?>s">
          <div class="feed__track">
            <?php for ($k = 0; $k < 2; $k++): foreach ($items as $j => $it): ?>
              <figure class="post"<?= $k ? ' aria-hidden="true"' : '' ?>>
                <img src="<?= asset('img/photos/' . $it['img'] . '.webp') ?>" alt="" loading="lazy" width="427" height="640">
                <span class="post__tag mono"><?= e($it['format']) ?></span>
                <figcaption class="mono"><span class="post__av"><?= e(mb_substr($it['niche'], 0, 1)) ?></span><?= e($it['niche']) ?></figcaption>
              </figure>
            <?php endforeach; endfor; ?>
          </div>
        </div>
      <?php endfor; ?>
    </div>
    <p class="wrap fd__note mono">Sample formats · photos are placeholders</p>
  </section>

  <!-- ===================== TEAM ===================== -->
  <section class="sec sec-light tm" id="team" data-tm>
    <div class="wrap">
      <div class="head">
        <div>
          <p class="eb" data-rv><i></i>(07) Team</p>
          <h2 class="h2" data-hd><?= title('Specialists,|*not generalists.*') ?></h2>
        </div>
        <div class="head__side" data-rv>
          <p class="lead">Every account gets a senior lead for each channel it uses. No juniors learning on your budget, no hand-offs.</p>
          <p class="tm__count mono"><i class="ping"></i>Specialists met <b data-tm-count>01</b>/<?= sprintf('%02d', count($team)) ?></p>
        </div>
      </div>
      <div class="tm__row" data-tm-row>
        <span class="tm__ring" data-tm-ring aria-hidden="true"><i></i><i></i><i></i></span>
        <?php foreach ($team as $i => $m): ?>
          <article class="mb<?= $i ? '' : ' is-lit' ?>" data-mb data-rv>
            <div class="mb__ph">
              <span class="mb__img" aria-hidden="true"><img src="<?= asset('img/brand/webincline-icon.webp') ?>" alt="" width="120" height="64" loading="lazy"></span>
              <span class="mb__focus mono"><span class="mb__ck"><?= icon('check') ?></span><?= e($m['focus']) ?></span>
              <span class="mb__n mono"><?= sprintf('%02d', $i + 1) ?></span>
            </div>
            <h3><?= e($m['name']) ?></h3>
            <p><?= e($m['role']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ===================== CLIENT STORIES ===================== -->
  <section class="sec sec-light rv" id="stories" data-stories>
    <div class="wrap">
      <div class="rv__box">
        <div class="rv__l">
          <p class="eb" data-rv><i></i>Client stories</p>
          <div class="rv__score" data-rv style="--d:120ms">
            <p class="rv__big">500<span>+</span></p>
            <p class="mono rv__sub">Clients since we started<br>in <?= count($site['markets']) ?> markets</p>
          </div>
          <h2 class="h2" data-hd><?= title('Clients who|*stay.*') ?></h2>
          <p class="lead" data-rv style="--d:480ms">What our clients say about working with us, in their own words, next to the results from their case studies.</p>
          <a class="btn btn--line" href="#work" data-mag data-rv style="--d:560ms"><span class="btn__lb">Read case studies</span><span class="btn__ic"><?= icon('arrow-up') ?></span></a>
        </div>

        <div class="lk" data-reviews data-rv style="--d:200ms;--ty:40px">
          <span class="lk__rings" aria-hidden="true"><i></i><i></i><i></i></span>
          <div class="lk__status mono" aria-hidden="true"><span data-lk-time>09:41</span><span class="lk__island"></span><span class="lk__bat"><i></i></span></div>
          <div class="lk__time" aria-hidden="true">
            <?= icon('lock') ?>
            <p class="mono" data-lk-date>Monday, 1 January</p>
            <p class="lk__clock" data-lk-clock>09:41</p>
          </div>
          <p class="lk__count mono"><b data-lk-n><?= count($reviews) ?></b> <span data-lk-label>New messages</span></p>
          <ol class="lk__stack" data-lk-stack>
            <?php foreach ($reviews as $i => $r): $q = $r['type'] === 'quote'; ?>
              <li class="tn" data-tn tabindex="0">
                <p class="tn__app mono"><span class="tn__ic"><img src="<?= asset('img/brand/webincline-icon.webp') ?>" alt="" width="40" height="22" loading="lazy"></span>Webincline<?= $q ? '' : ' · Case study' ?><span class="tn__t" data-tn-t>now</span></p>
                <div class="tn__who">
                  <span class="tn__av<?= $r['logo'] ? '' : ' tn__av--txt' ?>">
                    <?php if ($r['logo']): ?><img src="<?= asset('img/clients/' . $r['logo'] . '.webp') ?>" alt="" width="36" height="36" loading="lazy"><?php else: ?><?= e(mb_substr($r['name'], 0, 1)) ?><?php endif; ?>
                  </span>
                  <span class="tn__nm"><b><?= e($r['name']) ?></b><small><?= e($r['role']) ?></small></span>
                  <span class="tn__chip mono"><?= e($r['chip']) ?></span>
                </div>
                <p class="tn__q<?= $q ? '' : ' tn__q--res' ?>"><?= $q ? '“' . e($r['text']) . '”' : e($r['text']) ?></p>
                <?php if (!empty($r['note'])): ?><p class="tn__src mono"><?= e($r['note']) ?></p><?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ol>
          <div class="lk__foot" aria-hidden="true"><span><?= icon('torch') ?></span><i></i><span><?= icon('camera') ?></span></div>
        </div>
      </div>
    </div>
  </section>

<?php $faq_list = $faqs; require __DIR__ . '/includes/partials/faq.php'; ?>

  <!-- ===================== VIDEO STORIES (3D) ===================== -->
  <section class="sec sec-dark vs" id="videos" data-vs>
    <div class="wrap">
      <div class="head">
        <div>
          <p class="eb" data-rv><i></i>On camera</p>
          <h2 class="h2" data-hd><?= title('Hear it from|*our clients.*') ?></h2>
        </div>
        <div class="head__side" data-rv>
          <p class="lead">Clients who have worked with us for years, in their own words, on camera. Drag or swipe through the videos and tap one to hear it.</p>
          <div class="vs__ctl">
            <button class="vs__arw vs__arw--p" type="button" aria-label="Previous video" data-vs-prev><?= icon('arrow') ?></button>
            <p class="vs__idx mono"><b data-vs-idx>01</b> / <?= sprintf('%02d', count($testimonials)) ?></p>
            <button class="vs__arw" type="button" aria-label="Next video" data-vs-next><?= icon('arrow') ?></button>
          </div>
        </div>
      </div>
    </div>
    <div class="vs__stage" data-vs-stage data-rv>
      <div class="vs__ring" data-vs-ring>
        <?php foreach ($testimonials as $i => $t): ?>
          <article class="vc<?= $i ? '' : ' is-on' ?>" data-vc>
            <video class="vc__v" muted playsinline preload="none" poster="<?= asset('video/' . $t['video'] . '.webp') ?>">
              <source src="<?= asset('video/' . $t['video'] . '.mp4') ?>" type="video/mp4">
              <track kind="captions" srclang="en" label="<?= e($t['captions']) ?>" src="<?= asset('video/' . $t['video'] . '.vtt') ?>" default>
            </video>
            <span class="vc__dim" aria-hidden="true"></span>
            <div class="vc__ui">
              <span class="vc__bar" aria-hidden="true"><b></b></span>
              <p class="vc__top mono"><span class="vc__av"><img src="<?= asset('img/brand/webincline-icon.webp') ?>" alt="" width="40" height="22" loading="lazy"></span>Client story <?= sprintf('%02d', $i + 1) ?></p>
              <div class="vc__bot">
                <h3><?= e($t['company']) ?></h3>
                <p class="mono"><?= e($t['name']) ?></p>
              </div>
            </div>
            <span class="vc__play" aria-hidden="true"><?= icon('play') ?></span>
            <button class="vc__hit" type="button" aria-label="Video <?= $i + 1 ?>: <?= e($t['company']) ?>" data-vc-hit></button>
            <button class="vc__snd" type="button" aria-label="Turn sound on" data-vc-sound><?= icon('mute', 'icon icon--m') ?><?= icon('sound', 'icon icon--s') ?></button>
          </article>
        <?php endforeach; ?>
      </div>
      <span class="vs__floor" aria-hidden="true"></span>
    </div>
  </section>

  <!-- ===================== INSIGHTS ===================== -->
  <section class="sec sec-light jr" id="insights">
    <div class="wrap">
      <div class="head">
        <div>
          <p class="eb" data-rv><i></i>(09) Insights</p>
          <h2 class="h2" data-hd><?= title('Ideas we|*test first.*') ?></h2>
        </div>
        <div class="head__side" data-rv>
          <p class="lead">What we learn running campaigns every week, written up so you can use it too.</p>
          <a class="btn btn--line" href="<?= e(BLOG_BASE) ?>/blogs" data-mag><span class="btn__lb">All articles</span><span class="btn__ic"><?= icon('arrow-up') ?></span></a>
        </div>
      </div>
      <div class="jr__grid">
        <?php foreach ($posts as $i => $p): ?>
          <article class="art<?= $i ? '' : ' art--lead' ?>" data-rv>
            <a href="<?= e(BLOG_BASE . $p['path']) ?>">
              <span class="art__img"><img src="<?= asset('img/photos/' . $p['img'] . '.webp') ?>" alt="" loading="lazy" width="1200" height="800"></span>
              <span class="art__tx">
                <span class="art__meta mono"><span class="chip">#<?= e($p['category']) ?></span><?= e($p['date']) ?> · <?= e($p['read']) ?></span>
                <span class="art__h"><?= e($p['title']) ?></span>
                <span class="art__p"><?= e($p['excerpt']) ?></span>
              </span>
              <span class="art__go" aria-hidden="true"><?= icon('arrow-up') ?></span>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

<?php require __DIR__ . '/includes/partials/contact.php'; ?>

</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
