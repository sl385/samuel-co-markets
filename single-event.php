<?php
/**
 * Single event. Real event_date/cost/url plus event_venue/event_sold_out
 * (includes/theme-acf.php) via partials/components/event-info.php. Was
 * falling back to the generic article single.php (byline, share links,
 * related-posts sidebar) which doesn't fit an event at all — this is its
 * own template, matched by WP's template hierarchy on the `event` post type.
 */
get_header();
?>
<?php while (have_posts()): the_post(); ?>
<?php
scm_component('masthead', [
  'eyebrow' => 'Event',
  'title' => get_the_title(),
  'image' => scm_the_thumbnail_url('large'),
]);
?>
<section class="scm-section">
  <div class="scm-shell scm-event-layout">
    <?php if (trim(get_the_content())): ?>
      <div class="scm-prose"><?php the_content(); ?></div>
    <?php else: ?>
      <div></div>
    <?php endif; ?>
    <?php scm_component('event-info'); ?>
  </div>
</section>
<?php endwhile; ?>
<?php get_footer(); ?>
