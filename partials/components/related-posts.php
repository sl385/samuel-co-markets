<?php
/**
 * Related posts (spec C22, new). @dynamic
 * Same-category posts excluding the current one; falls back to latest posts
 * if the current post has no category with siblings.
 * args: post_id (default current), count (default 3)
 */
$a = wp_parse_args($args ?? [], ['post_id' => get_the_ID(), 'count' => 3]);
$cats = wp_get_post_categories($a['post_id']);
$q = new WP_Query([
  'post_type' => 'post',
  'posts_per_page' => $a['count'],
  'post__not_in' => [$a['post_id']],
  'category__in' => $cats ?: null,
  'ignore_sticky_posts' => true,
]);
if (!$q->have_posts()) {
  wp_reset_postdata();
  $q = new WP_Query(['post_type' => 'post', 'posts_per_page' => $a['count'], 'post__not_in' => [$a['post_id']]]);
}
?>
<?php if ($q->have_posts()): ?>
<section class="scm-section scm-section--soft">
  <div class="scm-shell">
    <?php scm_component('section-head', ['title' => 'Related']); ?>
    <div class="scm-card-grid scm-card-grid--3">
      <?php while ($q->have_posts()): $q->the_post(); scm_component('card-post', ['words' => 16]); endwhile; ?>
    </div>
  </div>
</section>
<?php endif; wp_reset_postdata(); ?>
