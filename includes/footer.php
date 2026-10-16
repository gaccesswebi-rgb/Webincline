<?php /** @var array $site @var array $channels */ ?>
<footer class="site-footer">
  <div class="container">
    <div class="site-footer__top">
      <div class="site-footer__brand">
        <a class="brand" href="/" aria-label="<?= e(SITE_NAME) ?> home">
          <img class="brand__icon" src="<?= asset('img/brand/webincline-icon.webp') ?>" alt="" width="52" height="28" loading="lazy">
          <span class="brand__word">webincline</span>
        </a>
        <p><?= e(SITE_TAGLINE) ?> Digital marketing, websites, CRM and AI automation for brands in nine markets.</p>
        <p class="badge"><span class="dot dot--live"></span>Taking new projects in 9 markets</p>
        <a class="btn btn--primary btn--sm" href="#contact"><span>Book a strategy call</span><span class="btn__icon"><?= icon('arrow-up') ?></span></a>
      </div>

      <div class="site-footer__col">
        <h3 class="mono">Services</h3>
        <ul>
          <?php foreach ($channels as $i => $c): ?>
            <li><a href="#services" data-goto-channel="<?= $i ?>"><?= e($c['title']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="site-footer__col">
        <h3 class="mono">Company</h3>
        <ul>
          <li><a href="#work">Case studies</a></li>
          <li><a href="#process">How we work</a></li>
          <li><a href="#team">Team</a></li>
          <li><a href="#insights">Insights</a></li>
          <li><a href="#faq">FAQ</a></li>
        </ul>
      </div>

      <div class="site-footer__col">
        <h3 class="mono">Contact</h3>
        <ul>
          <li class="selectable"><?= e($site['email']) ?></li>
          <?php foreach ($site['phones'] as $p): ?>
            <li><a href="tel:<?= e($p['tel']) ?>"><?= e($p['label']) ?></a></li>
          <?php endforeach; ?>
          <li class="muted"><?= e($site['address']['line1']) ?>, <?= e($site['address']['city']) ?></li>
        </ul>
        <ul class="pills mono">
          <?php foreach ($site['social'] as $label => $url): ?>
            <li><a href="<?= e($url) ?>" target="_blank" rel="noopener"><?= e($label) ?></a></li>
          <?php endforeach; ?>
          <li><a href="https://wa.me/<?= e($site['whatsapp']) ?>" target="_blank" rel="noopener">WhatsApp</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="wordmark" data-wordmark aria-hidden="true">
    <p class="wordmark__hint mono">Run your cursor over the name</p>
    <p class="wordmark__text">webincline<span class="wordmark__glow">webincline</span></p>
  </div>

  <div class="container site-footer__bottom mono">
    <p>© <?= date('Y') ?> <?= e(SITE_NAME) ?></p>
    <p><span class="dot dot--live"></span>Chandigarh <span data-clock-short>--:--</span> IST</p>
  </div>
</footer>

<a class="whatsapp-fab" href="https://wa.me/<?= e($site['whatsapp']) ?>" target="_blank" rel="noopener" aria-label="Chat on WhatsApp"><?= icon('whatsapp') ?></a>

<script src="<?= asset('js/vendor/gsap.min.js') ?>" defer></script>
<script src="<?= asset('js/vendor/ScrollTrigger.min.js') ?>" defer></script>
<script src="<?= asset('js/main.js') ?>" defer></script>
</body>
</html>
