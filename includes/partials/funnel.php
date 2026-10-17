<?php
/**
 * "How we work": the five steps next to the example funnel.
 * @var array $process @var array $funnel_demo
 * Optional: $fn_eb (eyebrow, e.g. "(04) How we work"), $fn_title, $fn_lead.
 */
$fn_eb    = $fn_eb ?? '(04) How we work';
$fn_title = $fn_title ?? 'From attention|to *customers.*';
$fn_lead  = $fn_lead ?? 'Every project runs the same five steps. You see where we are, and what each step added, in every report.';
?>
  <!-- ===================== PROCESS + FUNNEL ===================== -->
  <section class="sec sec-grey fn" id="process" data-fn>
    <div class="fn__sticky">
      <div class="wrap fn__grid">
        <div class="fn__l">
          <p class="eb" data-rv><i></i><?= e($fn_eb) ?></p>
          <h2 class="h2" data-hd><?= title($fn_title) ?></h2>
          <p class="lead" data-rv><?= e($fn_lead) ?></p>
          <ol class="steps">
            <?php foreach ($process as $i => $s): ?>
              <li class="step" data-step>
                <span class="step__n mono"><?= sprintf('%02d', $i + 1) ?></span>
                <div><h3><?= e($s['title']) ?> <span class="chip mono"><?= e($s['when']) ?></span></h3><p><?= e($s['body']) ?></p></div>
              </li>
            <?php endforeach; ?>
          </ol>
        </div>

        <div class="fn__r" data-rv>
          <?php $in = [0, 7, 14, 21, 27]; // funnel wall inset (%) at the top of each band, plus the bottom ?>
          <div class="funnel" data-funnel>
            <?php foreach ($funnel_demo as $i => $f): ?>
              <div class="band" style="--i:<?= $i ?>;--a:<?= $in[$i] ?>%;--b:<?= $in[$i + 1] ?>%" data-band data-val="<?= (int) $f['value'] ?>" tabindex="0">
                <span class="band__shape"><span class="band__fill"></span></span>
                <span class="band__in">
                  <span class="chip mono"><b><?= sprintf('%02d', $i + 1) ?></b> <?= e($f['stage']) ?></span>
                  <b class="band__v" data-band-v><?= number_format($f['value']) ?></b>
                </span>
                <span class="band__tip mono"><i class="live"></i><?= e($f['rate']) ?></span>
              </div>
            <?php endforeach; ?>
            <canvas class="fn__cv" data-fn-cv aria-hidden="true"></canvas>
          </div>
          <p class="fn__note mono"><i class="live"></i>Example funnel · illustrative numbers</p>
        </div>
      </div>
    </div>
  </section>
