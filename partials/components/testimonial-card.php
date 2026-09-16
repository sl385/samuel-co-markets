<?php
/**
 * Testimonial card (spec C24). @dynamic
 * Use inside a loop over the `success_story` CPT. Star rating is a fixed 5-star
 * decoration per the design (reference/design_system.png) — there is no per-review
 * rating field in the data, so this is not standing in for a real number.
 * args: none
 */
$course = get_field('course_taken') ?: '';
$payout = get_field('payout_earnt') ?: '';
?>
<article class="scm-testimonial">
  <div class="scm-testimonial__head">
    <?php if (has_post_thumbnail()): ?>
      <span class="scm-testimonial__avatar"><?php the_post_thumbnail('thumbnail'); ?></span>
    <?php endif; ?>
    <div>
      <span class="scm-testimonial__stars" aria-hidden="true">★★★★★</span>
      <strong class="scm-testimonial__name"><?php the_title(); ?></strong>
      <?php if ($course): ?><span class="scm-meta"><?php echo esc_html($course); ?></span><?php endif; ?>
    </div>
  </div>
  <blockquote><?php echo esc_html(wp_trim_words(get_the_content(), 40)); ?></blockquote>
  <?php if ($payout): ?><span class="scm-testimonial__payout">Funded payout: <?php echo esc_html($payout); ?></span><?php endif; ?>
</article>
