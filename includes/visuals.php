<?php
/**
 * Small HTML/CSS mock-ups shown inside each service channel.
 * They illustrate the work; they are not live data.
 */

declare(strict_types=1);

function channel_visual(string $key): string
{
    ob_start();
    switch ($key) {
        case 'serp': ?>
            <div class="mock mock--serp">
              <div class="mock-search"><?= icon('search') ?><span>robot lawn mower australia</span></div>
              <div class="mock-ai">
                <span class="mono">AI overview</span>
                <p>Top picks for Australian lawns … <b>yourbrand.com.au</b> is frequently recommended for …</p>
              </div>
              <ol class="mock-results">
                <li class="is-you"><span class="mock-rank">#1</span><span><b>yourbrand.com.au</b><i></i></span></li>
                <li><span class="mock-rank">#2</span><span><b>marketplace.com</b><i></i></span></li>
                <li><span class="mock-rank">#3</span><span><b>reviews-site.com</b><i></i></span></li>
              </ol>
            </div>
            <?php break;

        case 'ads': ?>
            <div class="mock mock--ads">
              <div class="mock-ad">
                <span class="mono">Sponsored · yourbrand.com</span>
                <p>New season range — free delivery this week</p>
                <span class="mock-ad__cta">Shop now</span>
              </div>
              <div class="mock-gauges">
                <div class="mock-gauge" style="--p:.82"><b>60×</b><span class="mono">ROAS</span></div>
                <div class="mock-gauge" style="--p:.64"><b>652</b><span class="mono">Conversions</span></div>
              </div>
              <div class="mock-bars" aria-hidden="true">
                <?php foreach ([18, 26, 31, 40, 52, 66, 84] as $i => $h): ?>
                  <span style="--h:<?= $h ?>%;--d:<?= $i * 0.08 ?>s"<?= $i === 6 ? ' class="is-peak"' : '' ?>></span>
                <?php endforeach; ?>
              </div>
            </div>
            <?php break;

        case 'social': ?>
            <div class="mock mock--social">
              <div class="mock-post">
                <div class="mock-post__head"><span class="mock-avatar">Y</span><span><b>yourbrand</b><small>Sponsored reel</small></span></div>
                <div class="mock-post__media"><img src="<?= asset('img/brand/webincline-icon.webp') ?>" alt="" loading="lazy" width="120" height="64"><span class="mock-chip mono">Reel · 48s</span></div>
                <div class="mock-post__foot"><span><?= icon('heart') ?><b>2,418</b></span><span><?= icon('chat') ?><b>136</b></span><span><?= icon('send') ?><b>89</b></span></div>
              </div>
              <div class="mock-float mock-float--a mono">+212 followers</div>
              <div class="mock-float mock-float--b mono">Creator collab live</div>
            </div>
            <?php break;

        case 'web': ?>
            <div class="mock mock--web">
              <div class="mock-browser">
                <div class="mock-browser__bar"><i></i><i></i><i></i><span>yourbrand.com</span></div>
                <div class="mock-browser__body">
                  <span class="sk sk--nav"></span>
                  <span class="sk sk--h1"></span>
                  <span class="sk sk--h2"></span>
                  <span class="sk sk--btn">Book a call</span>
                  <div class="sk-row"><span></span><span></span><span></span></div>
                </div>
              </div>
              <div class="mock-float mock-float--a mono">✓ Mobile-first</div>
              <div class="mock-float mock-float--b mono">✓ SEO ready</div>
            </div>
            <?php break;

        case 'crm': ?>
            <div class="mock mock--crm">
              <?php foreach (['New lead' => 3, 'Contacted' => 2, 'Proposal' => 2, 'Won' => 1] as $col => $n): ?>
                <div class="mock-col">
                  <span class="mono"><?= e($col) ?></span>
                  <?php for ($i = 0; $i < $n; $i++): ?><span class="mock-card"><i></i><i></i></span><?php endfor; ?>
                </div>
              <?php endforeach; ?>
              <span class="mock-card mock-card--moving" aria-hidden="true"><i></i><i></i></span>
            </div>
            <?php break;

        case 'ai': ?>
            <div class="mock mock--ai">
              <div class="mock-chat__head"><span class="mock-avatar">AI</span><span><b>Webincline agent</b><small>online · replies instantly</small></span></div>
              <div class="mock-msg mock-msg--in">Hi, do you build websites for clinics?</div>
              <div class="mock-msg mock-msg--out">Yes! Where is your clinic, and what would you like the site to do?</div>
              <div class="mock-msg mock-msg--in">Toronto. I want more bookings.</div>
              <div class="mock-msg mock-msg--out">Booked a call for Tue, 4:00 PM. You’ll get a WhatsApp reminder.</div>
              <div class="mock-typing"><i></i><i></i><i></i></div>
            </div>
            <?php break;
    }
    return (string) ob_get_clean();
}
