<?php
/**
 * Homepage hero. @dynamic — copy/image from ACF options ("Homepage (2026)" tab in
 * S&Co Settings) with defaults matching the reference. The signup form is a
 * CF7 shortcode if set, otherwise a static placeholder form (posts to #).
 */
$title_lines = scm_opt_lines('scm_hero_title', ['Markets.', 'Research.', 'Education.']);
$lead  = scm_opt('scm_hero_lead', 'Understand what is moving markets — and build the knowledge to act with confidence.');
$sub   = scm_opt('scm_hero_sub', 'Independent market intelligence from a trading business established in 2012.');
$image = scm_opt('scm_hero_image', _i('editorial/london-session.webp'));
$values = scm_opt_lines('scm_hero_values', ['Discipline', 'Knowledge', 'Opportunity', 'A Brighter', 'Tomorrow']);
$quote = scm_opt('scm_hero_quote', 'Better information leads to better decisions.');
$cite  = scm_opt('scm_hero_quote_cite', 'Samuel Leach · Founder, Samuel & Co Trading');
$form  = scm_opt('scm_signup_form', '');
?>
<section class="scm-hero">
  <div class="scm-shell scm-hero__grid">
    <div class="scm-hero__copy">
      <h1><?php echo implode('<br>', array_map('esc_html', $title_lines)); ?></h1>
      <p class="scm-hero__lead"><?php echo esc_html($lead); ?></p>
      <p class="scm-hero__sub"><?php echo esc_html($sub); ?></p>
      <div id="morning-brief-signup">
      <?php if ($form): ?>
        <?php echo do_shortcode($form); ?>
      <?php else: ?>
        <?php /* STATIC: placeholder until the Morning Brief form is wired to CF7 / the CRM */ ?>
        <form class="scm-signup" action="#" method="post">
          <input type="email" name="email" placeholder="Enter your email address" required>
          <button class="scm-button scm-button--gold" type="submit">Get the Morning Brief — Free</button>
        </form>
      <?php endif; ?>
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
