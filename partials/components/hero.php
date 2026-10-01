<?php
/**
 * Homepage hero. @dynamic — copy/image from ACF options ("Homepage (2026)" tab in
 * S&Co Settings) with defaults matching the reference (this hero's visual design
 * is the approved one, reference/PHOTO-2026-09-10-11-51-31.jpg — not restyled
 * for the "Morning Brief" mockup; only the signup form itself was a dead
 * placeholder, see below). The signup form is a CF7 shortcode if set, and now
 * defaults to the real mailing-list form (CF7 493, same one used in the
 * footer/article sidebar) instead of a fake form that posted to "#" — every
 * subscriber genuinely reaches the same real list, whichever form they used.
 */
$title_lines = scm_opt_lines('scm_hero_title', ['Markets.', 'Research.', 'Education.']);
$lead  = scm_opt('scm_hero_lead', 'Understand what is moving markets — and build the knowledge to act with confidence.');
$sub   = scm_opt('scm_hero_sub', 'Independent market intelligence from a trading business established in 2012.');
$image = scm_opt('scm_hero_image', _i('editorial/london-session.webp'));
$values = scm_opt_lines('scm_hero_values', ['Discipline', 'Knowledge', 'Opportunity', 'A Brighter', 'Tomorrow']);
$quote = scm_opt('scm_hero_quote', 'Better information leads to better decisions.');
$cite  = scm_opt('scm_hero_quote_cite', 'Samuel Leach · Founder, Samuel & Co Trading');
?>
<section class="scm-hero">
  <div class="scm-hero__grid">
    <div class="scm-hero__copy">
      <h1><?php echo implode('<br>', array_map('esc_html', $title_lines)); ?></h1>
      <p class="scm-hero__lead"><?php echo esc_html($lead); ?></p>
      <p class="scm-hero__sub"><?php echo esc_html($sub); ?></p>
      <div id="morning-brief-signup">
        <?php scm_component('newsletter-form', ['cta' => 'Get the Morning Brief — Free', 'source' => 'homepage-hero', 'layout' => 'inline', 'label' => 'Get the Morning Brief — free every weekday']); ?>
      </div>
      <small>Delivered every weekday morning. Free. Unsubscribe anytime.</small>
    </div>
    <div class="scm-hero__visual" style="background-image:url('<?php echo esc_url($image); ?>')">
      <div class="scm-hero__values"><?php echo implode('<br>', array_map('esc_html', $values)); ?></div>
      <div class="scm-hero__quote">
        <blockquote>“<?php echo esc_html($quote); ?>”</blockquote>
        <cite><?php echo esc_html($cite); ?></cite>
      </div>
    </div>
  </div>
</section>
