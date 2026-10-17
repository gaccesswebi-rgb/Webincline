<?php
/**
 * Questions and answers, with FAQPage schema for Google.
 * @var array $site
 * Required: $faq_list ([['q', 'tag', 'a'], ...]).
 * Optional: $faq_eb (eyebrow, e.g. "(08) FAQ").
 */
$faq_eb   = $faq_eb ?? '(08) FAQ';
$faq_book = link_or('/contact/book-a-call', anchor('contact'));
$faq_schema = [
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => array_map(static fn(array $f): array => [
        '@type'          => 'Question',
        'name'           => $f['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
    ], $faq_list),
];
?>
  <!-- ===================== FAQ ===================== -->
  <section class="sec sec-light fq" id="faq">
    <div class="wrap fq__grid">
      <div class="fq__side">
        <p class="eb" data-rv><i></i><?= e($faq_eb) ?></p>
        <h2 class="h2" data-hd><?= title('Questions,|*answered.*') ?></h2>
        <div class="ask sec-dark" data-rv>
          <p class="mono"><i class="ping"></i>Still unsure?</p>
          <h3>Talk it through with us on a call.</h3>
          <p>We look at your website, ads and socials with you and point out the quickest wins — you keep the advice either way.</p>
          <a class="btn btn--solid btn--sm" href="<?= e($faq_book) ?>" data-mag><span class="btn__lb">Book a call</span><span class="btn__ic"><?= icon('calendar') ?></span></a>
          <p class="ask__mail">Or write to <span class="selectable"><?= e($site['email']) ?></span></p>
        </div>
      </div>
      <div class="fq__list" data-faq>
        <span class="fq__rail" aria-hidden="true"><span data-faq-rail></span></span>
        <?php foreach ($faq_list as $i => $f): ?>
          <div class="qa<?= $i ? '' : ' is-open' ?>" data-qa>
            <span class="qa__dot" aria-hidden="true"></span>
            <button class="qa__q" type="button" aria-expanded="<?= $i ? 'false' : 'true' ?>" aria-controls="qa-<?= $i ?>">
              <span class="mono qa__n"><?= sprintf('%02d', $i + 1) ?></span>
              <span class="qa__t"><?= e($f['q']) ?></span>
              <span class="chip mono qa__tag"><?= e($f['tag']) ?></span>
              <span class="qa__pl" aria-hidden="true"><i></i><i></i></span>
            </button>
            <div class="qa__a" id="qa-<?= $i ?>" role="region"><div><p><?= e($f['a']) ?></p></div></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <script type="application/ld+json"><?= json_encode($faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
  </section>
