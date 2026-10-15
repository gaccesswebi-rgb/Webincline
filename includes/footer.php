<?php /** @var array $site @var array $services */ ?>
<footer class="site-footer">
  <div class="container">
    <div class="site-footer__top">
      <div class="site-footer__brand">
        <a class="brand" href="/" aria-label="<?= e(SITE_NAME) ?> home">
          <img class="brand__icon" src="<?= asset('img/brand/webincline-icon.webp') ?>" alt="" width="64" height="34" loading="lazy">
          <span class="brand__word">webincline</span>
        </a>
        <p><?= e(SITE_TAGLINE) ?> SEO, ads, social, websites, CRM and AI automation for brands in nine markets.</p>
        <ul class="social">
          <?php foreach ($site['social'] as $label => $url): ?>
            <li><a href="<?= e($url) ?>" target="_blank" rel="noopener" aria-label="<?= e($label) ?>"><?= icon(strtolower($label)) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="site-footer__col">
        <h3>Services</h3>
        <ul>
          <?php foreach (array_slice($services, 0, 8) as $s): ?>
            <li><a href="#services"><?= e($s['title']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="site-footer__col">
        <h3>Company</h3>
        <ul>
          <li><a href="#work">Case studies</a></li>
          <li><a href="#process">How we work</a></li>
          <li><a href="#team">Team</a></li>
          <li><a href="#insights">Insights</a></li>
          <li><a href="#faq">FAQ</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </div>

      <div class="site-footer__col">
        <h3>Get in touch</h3>
        <ul class="contact-list">
          <li><?= icon('mail') ?><a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a></li>
          <?php foreach ($site['phones'] as $p): ?>
            <li><?= icon('phone') ?><a href="tel:<?= e($p['tel']) ?>"><?= e($p['label']) ?></a></li>
          <?php endforeach; ?>
          <li><?= icon('pin') ?><span><?= e($site['address']['line1']) ?>, <?= e($site['address']['city']) ?>, <?= e($site['address']['country']) ?></span></li>
        </ul>
      </div>
    </div>

    <p class="site-footer__wordmark" aria-hidden="true">webincline</p>

    <div class="site-footer__bottom">
      <p>© <?= date('Y') ?> <?= e(SITE_NAME) ?>. All rights reserved.</p>
      <p class="site-footer__markets"><?= e(implode(' · ', $site['markets'])) ?></p>
    </div>
  </div>
</footer>

<a class="whatsapp-fab" href="https://wa.me/<?= e($site['whatsapp']) ?>" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
  <?= icon('whatsapp') ?>
</a>

<script src="<?= asset('js/vendor/gsap.min.js') ?>" defer></script>
<script src="<?= asset('js/vendor/ScrollTrigger.min.js') ?>" defer></script>
<script src="<?= asset('js/vendor/MotionPathPlugin.min.js') ?>" defer></script>
<script src="<?= asset('js/main.js') ?>" defer></script>
</body>
</html>
