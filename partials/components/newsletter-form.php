<?php
/**
 * Native Morning Brief signup form → Beehiiv (includes/theme-beehiiv.php).
 * @dynamic
 * args: cta (button label), source (utm_source), layout 'stacked'|'inline',
 *       label (optional line above), id
 * If Beehiiv isn't configured yet the form still renders; submissions get a
 * clear "not configured" message and editors see a note.
 */
$a = wp_parse_args($args ?? [], ['cta' => "Get the Brief — It's Free", 'source' => 'website', 'layout' => 'stacked', 'label' => '', 'id' => 'bh-' . wp_unique_id()]);
$configured = function_exists('scm_beehiiv_configured') && scm_beehiiv_configured();
$success_title = scm_opt('beehiiv_success_title', "You're on the list.");
$success_text  = scm_opt('beehiiv_success_text', 'Look out for your confirmation email.');
?>
<div class="scm-newsletter scm-bh scm-bh--<?php echo esc_attr($a['layout']); ?>" data-bh>
  <?php if ($a['label']): ?><span class="scm-hero__signup-label"><?php echo esc_html($a['label']); ?></span><?php endif; ?>
  <form class="scm-bh-form" data-bh-form data-source="<?php echo esc_attr($a['source']); ?>" novalidate>
    <div class="scm-bh-form__row">
      <label class="scm-visually-hidden" for="<?php echo esc_attr($a['id']); ?>">Email address</label>
      <input class="scm-field" type="email" name="email" id="<?php echo esc_attr($a['id']); ?>" placeholder="Enter your email address" autocomplete="email" inputmode="email" required>
      <button class="scm-button scm-button--gold" type="submit"><span><?php echo esc_html($a['cta']); ?></span></button>
    </div>
    <label class="scm-check scm-bh-form__consent">
      <input type="checkbox" name="consent" value="1" required>
      <span>I consent to my data being stored and to receive the Morning Brief. See our <a href="<?php echo esc_url(get_privacy_policy_url() ?: home_url('/privacy-policy/')); ?>">Privacy Policy</a>.</span>
    </label>
    <input type="text" name="website" class="scm-bh-form__hp" tabindex="-1" autocomplete="off" aria-hidden="true">
    <p class="scm-field-help scm-field-help--error scm-bh-form__msg" role="alert" hidden></p>
  </form>
  <div class="scm-newsletter-success" hidden>
    <strong><?php echo esc_html($success_title); ?></strong>
    <span><?php echo esc_html($success_text); ?></span>
  </div>
  <?php if (!$configured && current_user_can('edit_theme_options')): ?>
    <p class="scm-review-note scm-review-note--inline">Beehiiv isn't configured: add <code>BEEHIIV_API_KEY</code> and <code>BEEHIIV_PUBLICATION_ID</code> to wp-config.php, or fill S&amp;Co Settings → Integrations. (Only editors see this.)</p>
  <?php endif; ?>
</div>
