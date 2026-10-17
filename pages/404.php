<?php
/** Shown for any address that is not a page (the router sends the 404 status). */

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$page_title = 'Page not found | Webincline';
$noindex    = true;

require __DIR__ . '/../includes/header.php';
?>
<main id="main">
  <section class="sec sec-dark nf">
    <div class="nf__radar" aria-hidden="true"><i></i><i></i><i></i><span class="nf__sweep"></span></div>
    <div class="wrap nf__in">
      <p class="eb"><i></i>Error 404</p>
      <h1 class="nf__h"><?= title('Signal|*lost.*') ?></h1>
      <p class="lead">We could not find this page. It may have moved when we rebuilt the site.</p>
      <div class="ph__cta">
        <a class="btn btn--solid" href="/"><span class="btn__lb">Back to home</span><span class="btn__ic"><?= icon('arrow-up') ?></span></a>
        <a class="btn btn--glass" href="<?= e(link_or('/services', anchor('services'))) ?>"><span class="btn__lb">Our services</span><span class="btn__ic"><?= icon('arrow-up') ?></span></a>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
