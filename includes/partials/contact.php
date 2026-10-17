<?php
/**
 * Contact form and availability card, closing most pages.
 * @var array $site @var array $services
 * Optional: $ct_eb (eyebrow), $ct_title, $ct_lead, $ct_pick (service name to pre-select).
 */
$csrf        = $csrf ?? csrf_token();   // header.php starts the session before any output
$form_status = $_GET['status'] ?? '';
$ct_eb    = $ct_eb ?? '(10) Let’s talk';
$ct_title = $ct_title ?? 'Ready to|*grow?*';
$ct_lead  = $ct_lead ?? 'Tell us about your business. We look at your website, ads and socials and come back with the quickest wins — whether we work together or not.';
$ct_pick  = $ct_pick ?? '';
?>
  <!-- ===================== CONTACT / CTA ===================== -->
  <section class="sec sec-dark ct" id="contact">
    <div class="ct__rings" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i></div>
    <div class="wrap ct__grid">
      <div>
        <p class="eb" data-rv><i></i><?= e($ct_eb) ?></p>
        <h2 class="ct__h" data-hd><?= title($ct_title) ?></h2>
        <p class="lead" data-rv><?= e($ct_lead) ?></p>

        <form class="form" action="/contact-submit.php" method="post" novalidate data-contact-form data-rv>
          <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
          <input type="hidden" name="ts" value="<?= time() ?>">
          <input type="hidden" name="back" value="<?= e(current_path()) ?>">
          <div class="hp" aria-hidden="true"><label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
          <div class="form__row">
            <label class="fld"><span class="sr-only">Name</span><input id="f-name" name="name" type="text" autocomplete="name" required maxlength="100" placeholder="Your name"></label>
            <label class="fld"><span class="sr-only">Work email</span><?= icon('mail') ?><input id="f-email" name="email" type="email" autocomplete="email" required maxlength="150" placeholder="Work email"></label>
          </div>
          <div class="form__row">
            <label class="fld"><span class="sr-only">Phone or WhatsApp</span><?= icon('phone') ?><input id="f-phone" name="phone" type="tel" autocomplete="tel" maxlength="30" placeholder="Phone / WhatsApp"></label>
            <label class="fld fld--sel"><span class="sr-only">What do you need?</span>
              <select id="f-need" name="services[]">
                <option value="">What do you need?</option>
                <?php foreach ($services as $s): ?><option<?= $s['name'] === $ct_pick ? ' selected' : '' ?>><?= e($s['name']) ?></option><?php endforeach; ?>
                <option>Not sure yet</option>
              </select>
            </label>
          </div>
          <label class="fld fld--area"><span class="sr-only">About your business</span><textarea id="f-message" data-lenis-prevent name="message" rows="2" required maxlength="3000" placeholder="What do you sell, and what would you like to grow?"></textarea></label>
          <div class="form__foot">
            <button class="btn btn--solid" type="submit" data-mag><span class="btn__lb">Send message</span><span class="btn__ic"><?= icon('arrow-up') ?></span></button>
            <p class="form__st mono" role="status" aria-live="polite" data-form-status><?php
              if ($form_status === 'sent') echo 'Thanks — your message is on its way.';
              elseif ($form_status === 'error') echo 'Something went wrong. Please email ' . e($site['email']) . '.';
              else echo 'No spam. We reply by email or WhatsApp.';
            ?></p>
          </div>
        </form>
      </div>

      <aside class="avail" data-rv>
        <p class="mono"><i class="live"></i>Our time · Chandigarh, India</p>
        <p class="avail__clk" data-clock>--:--:--</p>
        <div class="avail__seg" aria-hidden="true"><?php for ($k = 0; $k < 9; $k++): ?><i style="--i:<?= $k ?>"></i><?php endfor; ?></div>
        <p class="mono avail__mk"><?= e(implode(' · ', $site['markets'])) ?></p>
        <ul class="avail__list">
          <li><?= icon('mail') ?><span class="selectable"><?= e($site['email']) ?></span></li>
          <?php foreach ($site['phones'] as $p): ?><li><?= icon('phone') ?><a href="tel:<?= e($p['tel']) ?>"><?= e($p['label']) ?></a></li><?php endforeach; ?>
          <li><?= icon('whatsapp') ?><a href="https://wa.me/<?= e($site['whatsapp']) ?>" target="_blank" rel="noopener">Chat on WhatsApp</a></li>
          <li><?= icon('pin') ?><span><?= e($site['address']['line1']) ?>, <?= e($site['address']['city']) ?>, <?= e($site['address']['country']) ?></span></li>
        </ul>
      </aside>
    </div>
  </section>
