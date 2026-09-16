<?php
get_header();
$cat_id = (int) get_query_var('cat');
$title = is_category() ? single_cat_title('', false) : get_the_title(get_option('page_for_posts'));
if (!$title) $title = 'Latest';
?>
<?php
// Matches archive-event.php's masthead treatment (eyebrow + h1 + a real
// lead line for SEO/context) rather than the bare title this had before.
scm_component('masthead', [
  'eyebrow' => 'News & Research',
  'title' => $title,
  'content' => 'Independent market commentary, trading education and company research from the Samuel & Co Trading team.',
]);
?>
<section class="scm-section"><div class="scm-shell">
  <?php
  $cats = get_terms(['taxonomy' => 'category', 'hide_empty' => true]);
  if ($cats && !is_wp_error($cats)):
    $items = [['label' => 'All', 'url' => get_permalink(get_option('page_for_posts')) ?: home_url('/'), 'active' => !$cat_id]];
    foreach ($cats as $cat) {
      if (in_array($cat->slug, ['uncategorised', 'uncategorized'], true)) continue;
      $items[] = ['label' => $cat->name, 'url' => get_term_link($cat), 'active' => $cat_id === (int) $cat->term_id];
    }
    scm_component('pill-nav', ['items' => $items]);
  endif;
  ?>
  <?php if (have_posts()): ?>
  <div class="scm-card-grid scm-card-grid--3">
    <?php while (have_posts()): the_post(); scm_component('card-post'); endwhile; ?>
  </div>
  <?php the_posts_pagination(); ?>
  <?php else: ?><p>No content found.</p><?php endif; ?>
</div></section>
<?php get_footer(); ?>
