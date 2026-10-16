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
            <div class="ui ui--serp" data-serp>
              <div class="ui-q"><?= icon('search') ?><span>robot lawn mower australia</span></div>
              <p class="ui-meta mono">About 2,140,000 results · 0.38 s</p>
              <div class="ui-res">
                <?php foreach (['marketplace.com', 'reviews-site.com', 'mowerhub.com.au', 'bigstore.com', 'yourbrand.com.au'] as $i => $site): ?>
                  <div class="ui-r<?= $i === 4 ? ' is-you' : '' ?>" data-row style="--i:<?= $i ?>">
                    <span class="ui-fav"></span>
                    <span class="ui-rt"><b><?= e($site) ?></b><i></i></span>
                    <?php if ($i === 4): ?><span class="ui-rank mono" data-rank>#5</span><?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
              <p class="ui-toast" data-toast><span class="ui-toast__i"><?= icon('check') ?></span>Cited in AI answers</p>
            </div>
            <?php break;

        case 'ads': ?>
            <div class="ui ui--ads">
              <div class="ui-ad">
                <p class="mono">Sponsored · yourbrand.com</p>
                <p class="ui-ad__h">New season range — free delivery this week</p>
                <span class="ui-ad__cta">Shop now</span>
                <span class="ui-ad__glow" aria-hidden="true"></span>
              </div>
              <div class="ui-gauges">
                <div class="ui-g" style="--p:.62"><span class="ui-g__arc"></span><b>4.8%</b><span class="mono">CTR</span></div>
                <div class="ui-g" style="--p:.86"><span class="ui-g__arc"></span><b>60×</b><span class="mono">ROAS</span></div>
              </div>
              <div class="ui-bars" aria-hidden="true">
                <?php foreach ([22, 30, 34, 46, 55, 70, 92] as $i => $h): ?>
                  <i style="--h:<?= $h ?>%;--d:<?= $i * 0.08 ?>s"<?= $i === 6 ? ' class="is-peak"' : '' ?>></i>
                <?php endforeach; ?>
              </div>
              <p class="ui-toast ui-toast--b"><span class="ui-toast__i"><?= icon('arrow-up') ?></span>Sale · 2 min ago</p>
            </div>
            <?php break;

        case 'social': ?>
            <div class="ui ui--social">
              <div class="ui-phone">
                <div class="ui-phone__head"><span class="ui-av"></span><span><b>yourbrand</b><small>Sponsored reel</small></span></div>
                <div class="ui-phone__media">
                  <img src="<?= asset('img/photos/feed-12.webp') ?>" alt="" loading="lazy" width="427" height="640">
                  <span class="ui-hearts" aria-hidden="true"><i></i><i></i><i></i></span>
                </div>
                <div class="ui-phone__foot"><span><?= icon('heart') ?><b>2,418</b></span><span><?= icon('chat') ?><b>136</b></span><span><?= icon('send') ?><b>89</b></span></div>
              </div>
              <p class="ui-toast ui-toast--a"><span class="ui-toast__i">+</span>212 new followers</p>
              <p class="ui-toast ui-toast--b"><span class="ui-toast__i"><?= icon('check') ?></span>Creator collab live</p>
            </div>
            <?php break;

        case 'web': ?>
            <div class="ui ui--web">
              <div class="ui-browser">
                <div class="ui-browser__bar"><i></i><i></i><i></i><span class="mono">yourbrand.com</span></div>
                <div class="ui-browser__body">
                  <span class="sk sk--nav"></span>
                  <span class="sk sk--h1"></span>
                  <span class="sk sk--h2"></span>
                  <span class="sk sk--btn">Book a call</span>
                  <span class="sk sk--img"></span>
                  <div class="sk-row"><span></span><span></span><span></span></div>
                </div>
              </div>
              <p class="ui-toast ui-toast--b"><span class="ui-toast__i"><?= icon('arrow-up') ?></span>Enquiries up this month</p>
            </div>
            <?php break;

        case 'crm': ?>
            <div class="ui ui--crm">
              <div class="ui-crm__head"><span class="mono">Pipeline</span><span class="ui-crm__tools mono">Zoho · HubSpot · GoHighLevel</span></div>
              <div class="ui-crm__cols">
                <?php foreach (['New lead' => 3, 'Contacted' => 2, 'Proposal' => 2, 'Won' => 1] as $col => $n): ?>
                  <div class="ui-col">
                    <span class="mono"><?= e($col) ?></span>
                    <?php for ($i = 0; $i < $n; $i++): ?><span class="ui-card"><i></i><i></i></span><?php endfor; ?>
                  </div>
                <?php endforeach; ?>
                <span class="ui-card ui-card--moving" aria-hidden="true"><i></i><i></i></span>
              </div>
              <p class="ui-toast ui-toast--b"><span class="ui-toast__i"><?= icon('check') ?></span>Deal won · auto follow-up sent</p>
            </div>
            <?php break;

        case 'ai': ?>
            <div class="ui ui--ai">
              <div class="ui-chat">
                <div class="ui-chat__head"><span class="ui-av ui-av--ai">AI</span><span><b>Webincline agent</b><small>online · replies instantly</small></span></div>
                <div class="ui-msg ui-msg--in">Hi, do you build websites for clinics?</div>
                <div class="ui-msg ui-msg--out">Yes! Where is your clinic, and what should the site do?</div>
                <div class="ui-msg ui-msg--in">Toronto. I want more bookings.</div>
                <div class="ui-msg ui-msg--out">Booked a call for Tue, 4:00 PM. You’ll get a WhatsApp reminder.</div>
                <div class="ui-typing"><i></i><i></i><i></i></div>
              </div>
            </div>
            <?php break;
    }
    return (string) ob_get_clean();
}
