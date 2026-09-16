<?php 


get_header(); 

?>

<?php
// Bare title, no lead copy at all — nothing for search engines (or a
// visitor) to read before the list itself. Real event types on this
// install are live trading sessions, workshops, briefings and open days
// (see `wp post list --post_type=event`), so this describes what's
// genuinely here rather than generic filler.
scm_component('masthead', [
  'eyebrow' => 'Events',
  'title' => 'Upcoming Trading Events',
  'content' => 'Join Samuel & Co Trading for live trading sessions, funded-trader workshops, market briefings and careers evenings — in person and online.',
]);
?>

<section class="scm-section">
  <div class="scm-shell">
    <?php if (have_posts()): ?>
      <div class="scm-card-grid scm-card-grid--3">
        <?php while (have_posts()): the_post(); scm_component('card-event'); endwhile; ?>
      </div>
      <?php the_posts_pagination(); ?>
    <?php else: ?>
      <p>There are no events scheduled at present. Please check back soon or sign up to our mailing list.</p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>