<?php
/**
 * Contact — matches reference/contact.png's structure (hero+quote, get-in-touch
 * info row, message form, quick answers, closing CTA) with real data: the ACF
 * business address/phone/email already used elsewhere in the theme, and the
 * real Contact Form 7 (id 7) already live on the old page. No Google Maps API
 * key is configured (see docs/LEGACY-CODE-REVIEW.md 3), so the map is a marked
 * placeholder rather than a fabricated graphic.
 */
get_header();
$phone = function_exists('get_field') ? get_field('contact_phone_number', 'option') : '';
$email = function_exists('get_field') ? get_field('website_email', 'option') : '';
$address = function_exists('get_field') ? get_field('business_address', 'option') : '';
?>
<section class="scm-about-hero">
  <div class="scm-shell scm-about-hero__grid">
    <div class="scm-about-hero__copy">
      <span class="scm-eyebrow">Contact Us</span>
      <h1>We're here to help.</h1>
      <p>Whether you have a question about our education programmes, membership, or just want to learn more about Samuel &amp; Co Trading, we'd love to hear from you.</p>
    </div>
    <div class="scm-about-hero__image" style="background-image:url('<?php echo esc_url(_i('fallback/fallback-5-lse.webp')); ?>')">
      <div class="scm-about-hero__quote">&ldquo;Real people. Real experience.&rdquo;</div>
    </div>
  </div>
</section>

<section class="scm-section">
  <div class="scm-shell scm-contact-layout">
    <div>
      <h2 class="scm-contact-heading">Get in touch</h2>
      <p class="scm-article__meta" style="margin-bottom:24px;">Choose the best way to reach us.</p>
      <div class="scm-contact-methods">
        <div><i class="fa-solid fa-envelope" aria-hidden="true"></i><div><strong>Email</strong><?php if ($email): ?><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a><?php else: ?><span>No Data</span><?php endif; ?></div></div>
        <div><i class="fa-solid fa-phone" aria-hidden="true"></i><div><strong>Phone</strong><?php if ($phone): ?><a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a><?php else: ?><span>No Data</span><?php endif; ?></div></div>
        <div><i class="fa-solid fa-location-dot" aria-hidden="true"></i><div><strong>Our office</strong><?php if ($address): ?><span><?php echo wp_kses_post(nl2br(esc_html($address))); ?></span><?php else: ?><span>No Data</span><?php endif; ?></div></div>
      </div>
      <div class="scm-contact-map">No Data — a map needs a Google Maps API key, which isn't configured on this install (see docs/LEGACY-CODE-REVIEW.md).</div>
    </div>
    <div class="scm-contact-form">
      <h2 class="scm-contact-heading">Send us a message</h2>
      <p class="scm-article__meta" style="margin-bottom:20px;">Fill in the form below and we'll get back to you as soon as possible.</p>
      <?php echo do_shortcode('[contact-form-7 id="7" title="Contact form 1"]'); ?>
    </div>
  </div>
</section>

<section class="scm-about-belief" style="background-image:url('<?php echo esc_url(_i('fallback/fallback-3-stock-market.webp')); ?>')">
  <div class="scm-shell scm-about-belief__grid scm-on-dark">
    <div>
      <span class="scm-eyebrow">Still Have A Question?</span>
      <h2>We're always happy to help.</h2>
    </div>
    <a class="scm-button scm-button--on-dark" href="#">Contact our team →</a>
  </div>
</section>
<?php get_footer(); ?>
