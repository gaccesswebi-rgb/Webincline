<?php /** @var array $site @var array $services */ $book_href = link_or('/contact/book-a-call', anchor('contact')); ?>
<footer class="ft sec-ink" data-ft>
  <div class="wrap ft__top">
    <div class="ft__brand">
      <a class="hd__logo" href="/" aria-label="<?= e(SITE_NAME) ?> home">
        <img class="hd__mark" src="<?= asset('img/brand/webincline-icon.webp') ?>" alt="" width="46" height="25" loading="lazy">
        <span>webincline</span>
      </a>
      <p><?= e(SITE_TAGLINE) ?> SEO, ads, social, websites, CRM and AI automation that bring the right people to your door.</p>
      <p class="note"><i class="live"></i>Taking new projects in 9 markets</p>
      <a class="btn btn--solid btn--sm" href="<?= e($book_href) ?>" data-mag><span class="btn__lb">Book a strategy call</span><span class="btn__ic"><?= icon('calendar') ?></span></a>
    </div>

    <nav class="ft__col" aria-label="Services">
      <p class="mono">Services</p>
      <ul><?php foreach ($services as $slug => $s): ?><li><a href="<?= e(link_or(service_path($slug), link_or('/services', anchor('services')) . (page_url('/services') ? '#' . $slug : ''))) ?>"><?= e($s['name']) ?></a></li><?php endforeach; ?></ul>
    </nav>
    <nav class="ft__col" aria-label="Company">
      <p class="mono">Company</p>
      <ul>
        <li><a href="<?= e(link_or('/work', anchor('work'))) ?>">Case studies</a></li>
        <li><a href="<?= e(anchor('process')) ?>">How we work</a></li>
        <li><a href="<?= e(link_or('/about', anchor('team'))) ?>">About us</a></li>
        <li><a href="<?= e(link_or('/blog', anchor('insights'))) ?>">Blog</a></li>
        <?php if (page_url('/careers')): ?><li><a href="/careers">Careers</a></li><?php endif; ?>
        <li><a href="<?= e(link_or('/contact', anchor('contact'))) ?>">Contact</a></li>
      </ul>
    </nav>
    <div class="ft__col">
      <p class="mono">Contact</p>
      <ul>
        <li class="selectable"><?= e($site['email']) ?></li>
        <?php foreach ($site['phones'] as $p): ?><li><a href="tel:<?= e($p['tel']) ?>"><?= e($p['label']) ?></a></li><?php endforeach; ?>
        <li class="ft__mut"><?= e($site['address']['line1']) ?>, <?= e($site['address']['city']) ?></li>
      </ul>
      <ul class="ft__soc mono">
        <?php foreach ($site['social'] as $label => $url): ?><li><a href="<?= e($url) ?>" target="_blank" rel="noopener"><?= e($label) ?></a></li><?php endforeach; ?>
        <li><a href="https://wa.me/<?= e($site['whatsapp']) ?>" target="_blank" rel="noopener">WhatsApp</a></li>
      </ul>
    </div>
  </div>

  <div class="wm" data-wm aria-hidden="true">
    <p class="wm__hint mono">Move over the name</p>
    <p class="wm__t">webincline<span class="wm__l">webincline</span></p>
  </div>

  <div class="wrap ft__bot mono">
    <p>© <?= date('Y') ?> <?= e(SITE_NAME) ?> · All rights reserved</p>
    <p><i class="live"></i>Local time <span data-clock-short>--:--</span> · Chandigarh</p>
  </div>
</footer>

<a class="wa-fab" href="https://wa.me/<?= e($site['whatsapp']) ?>" target="_blank" rel="noopener" aria-label="Chat on WhatsApp"><?= icon('whatsapp') ?></a>

<script src="<?= asset('js/vendor/gsap.min.js') ?>" defer></script>
<script src="<?= asset('js/vendor/ScrollTrigger.min.js') ?>" defer></script>
<script src="<?= asset('js/vendor/lenis.min.js') ?>" defer></script>
<script src="<?= asset('js/main.js') ?>" defer></script>
</body>
</html>
