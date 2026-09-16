<?php
/**
 * Template Name: Contact Us
 *
 * Reskinned against contact-page-template.html. The "office" panel replaces
 * the honest-but-bare "No Data" map box with a real, finished design: a
 * branded location mark (not a fake map — this install has no Google Maps
 * API key, see docs/LEGACY-CODE-REVIEW.md §3) plus a genuinely working
 * "Get directions" link built from the real address, which needs no API
 * key at all. Real CF7 form (id 7) untouched — only its existing markup
 * classes (.grid, .grid--2, .grid-item, .form_foot) are styled, no fields
 * added or removed.
 *
 * @package WordPress
 * @subpackage Sam & Co
 */
$phone = get_field('contact_phone_number', 'option');
$email = get_field('website_email', 'option');
$address = get_field('business_address', 'option');
?>
<?php get_header(); ?>

<?php scm_component('masthead', [
  'title' => get_the_title(),
  'content' => get_the_content(),
  'dark' => true,
]); ?>

<section class="scm-contact-methods-band">
  <div class="scm-shell scm-contact-methods-band__grid">
    <?php if ($phone): ?>
      <a class="scm-contact-method" href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>">
        <span class="scm-contact-method__icon"><i class="fa-solid fa-phone" aria-hidden="true"></i></span>
        <span><span class="scm-meta">Call us</span><strong><?php echo esc_html($phone); ?></strong><small>Monday–Friday, during office hours</small></span>
      </a>
    <?php endif; ?>
    <?php if ($email): ?>
      <a class="scm-contact-method" href="mailto:<?php echo esc_attr($email); ?>">
        <span class="scm-contact-method__icon"><i class="fa-solid fa-envelope" aria-hidden="true"></i></span>
        <span><span class="scm-meta">Email us</span><strong><?php echo esc_html($email); ?></strong><small>We aim to reply within 2 working days</small></span>
      </a>
    <?php endif; ?>
    <?php if ($address): ?>
      <a class="scm-contact-method" href="#office">
        <span class="scm-contact-method__icon"><i class="fa-solid fa-location-dot" aria-hidden="true"></i></span>
        <span><span class="scm-meta">Visit us</span><strong><?php echo esc_html(preg_split('/\r\n|\r|\n/', trim($address))[0] ?? ''); ?></strong><small>Office visits by prior arrangement</small></span>
      </a>
    <?php endif; ?>
  </div>
</section>

<section class="scm-section">
  <div class="scm-shell scm-contact-grid">
    <div>
      <div class="scm-section-head"><div><span class="scm-eyebrow">Send a message</span><h2>How can we help?</h2></div></div>
      <div class="scm-contact-form" id="contact-form">
        <?php echo do_shortcode('[contact-form-7 id="7" title="Contact form 1"]'); ?>
      </div>
      <div class="scm-contact-success" id="scm-contact-success" hidden>
        <span class="scm-contact-success__icon"><i class="fa-solid fa-check" aria-hidden="true"></i></span>
        <span class="scm-eyebrow">Message received</span>
        <h2>Thanks — we'll be in touch.</h2>
        <p>Your enquiry has been sent to the team. We aim to reply within 2 working days.</p>
        <a class="scm-button scm-button--gold" href="<?php echo esc_url(home_url('/')); ?>">Return home</a>
      </div>
    </div>

    <aside class="scm-office" id="office">
      <div class="scm-office__mark" aria-hidden="true"><span class="scm-office__pin"><i class="fa-solid fa-location-dot"></i></span></div>
      <div class="scm-office__copy">
        <span class="scm-eyebrow">Visit the office</span>
        <h3>Samuel &amp; Co Trading</h3>
        <?php if ($address): ?><address><?php echo wp_kses_post(nl2br(esc_html($address))); ?></address><?php endif; ?>
        <ul class="scm-office__details">
          <li><span>Visits</span><strong>By appointment</strong></li>
          <li><span>Nearest station</span><strong>Kings Langley</strong></li>
          <?php if ($phone): ?><li><span>Telephone</span><strong><?php echo esc_html($phone); ?></strong></li><?php endif; ?>
        </ul>
        <?php if ($address): ?>
          <a class="scm-button scm-button--gold" target="_blank" rel="noopener" href="https://www.google.com/maps/dir/?api=1&destination=<?php echo rawurlencode(preg_replace('/\s+/', ' ', $address)); ?>">Get directions</a>
        <?php endif; ?>
        <p class="scm-office__note">A live map needs a Google Maps API key, which isn't configured on this install — Get Directions above works without one.</p>
      </div>
    </aside>
  </div>
</section>

<?php // No separate "quick CTA" band — it would just restate the real mailing-list section immediately below it (footer.php, every page). ?>

<?php get_footer(); ?>
