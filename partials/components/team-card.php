<?php
/**
 * Team member card (spec C25). @dynamic
 * Use inside a loop over the `team` CPT. Real photo when the file actually
 * exists (see scm_has_real_thumbnail — this install's media library is mostly
 * empty); otherwise initials on navy rather than a finance stock photo, which
 * would misrepresent a person. Clicking the card opens the bio in
 * footer.php's .team-modal (wired in assets/js/markets.js). args: none
 */
$role = get_field('role') ?: '';
$linkedin = get_field('linkedin_url') ?: '';
$email = get_field('email_address') ?: '';
$has_photo = scm_has_real_thumbnail();
$photo_url = $has_photo ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : '';
$bio = apply_filters('the_content', get_the_content());
$initials = '';
foreach (explode(' ', get_the_title()) as $word) { $initials .= mb_substr($word, 0, 1); }
?>
<article class="scm-team-card" tabindex="0" role="button" aria-haspopup="dialog"
  data-name="<?php echo esc_attr(get_the_title()); ?>"
  data-role="<?php echo esc_attr(trim($role)); ?>"
  data-photo="<?php echo esc_url($photo_url); ?>"
  data-bio="<?php echo esc_attr($bio); ?>"
  data-linkedin="<?php echo esc_url($linkedin); ?>"
  data-email="<?php echo esc_attr($email); ?>">
  <div class="scm-team-card__photo">
    <?php if ($has_photo): the_post_thumbnail('medium'); else: ?>
      <span class="scm-team-card__initials"><?php echo esc_html(mb_strtoupper($initials)); ?></span>
    <?php endif; ?>
  </div>
  <div class="scm-team-card__meta">
    <strong><?php the_title(); ?></strong>
    <?php if ($linkedin): ?>
      <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr(get_the_title() . ' on LinkedIn'); ?>" onclick="event.stopPropagation()"><i class="fa-brands fa-linkedin" aria-hidden="true"></i></a>
    <?php endif; ?>
  </div>
  <?php if ($role): ?><span class="scm-meta"><?php echo esc_html(trim($role)); ?></span><?php endif; ?>
</article>
