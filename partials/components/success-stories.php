<?php
/**
 * Success stories grid (spec C24, grid variant). @dynamic
 * Real `success_story` posts flagged display_on_home_page; falls back to the
 * latest N stories if none are flagged. "No Data" if the CPT has no posts at all —
 * this should not be faked with invented testimonials.
 * args: count (default 3)
 */
$a = wp_parse_args($args ?? [], ['count' => 3]);
$q = new WP_Query([
  'post_type' => 'success_story',
  'posts_per_page' => $a['count'],
  'meta_key' => 'display_on_home_page',
  'meta_value' => '1',
]);
if (!$q->have_posts()) {
  wp_reset_postdata();
  $q = new WP_Query(['post_type' => 'success_story', 'posts_per_page' => $a['count']]);
}
?>
<?php if ($q->have_posts()): ?>
<section class="scm-section">
  <div class="scm-shell">
    <?php scm_component('section-head', ['eyebrow' => 'Success Stories', 'title' => 'Real traders, real results.']); ?>
    <div class="scm-card-grid scm-card-grid--3">
      <?php while ($q->have_posts()): $q->the_post(); scm_component('testimonial-card'); endwhile; ?>
    </div>
  </div>
</section>
<?php else: /* No Data — design (reference/design_system.png C24) shows a testimonial grid; the success_story CPT has no posts to source it from. */ ?>
<?php endif; wp_reset_postdata(); ?>
