<?php
/**
 * News-style archive: the posts page ("News & Research"), category archives
 * and their child-category (topic) archives.
 *
 * Layout: masthead → category pills (+ topic pills when inside a category)
 * → featured story (first post) → the rest as a dense news list (News) or
 * editorial cards (Research and everything else) → pagination.
 */
get_header();

$term = is_category() ? get_queried_object() : null;
$top  = $term ? ($term->parent ? get_term($term->parent, 'category') : $term) : null;
$title = $term ? $term->name : (get_the_title(get_option('page_for_posts')) ?: 'News & Research');
$lead  = $term && $term->description ? $term->description : 'Independent market commentary, trading education and company research from the Samuel & Co Trading team.';
$list_style = $top && $top->slug === 'news';
$posts_page = get_permalink(get_option('page_for_posts')) ?: home_url('/');

scm_component('masthead', ['eyebrow' => $top && $term->parent ? $top->name : 'News & Research', 'title' => $title, 'content' => $lead]);
?>
<section class="scm-section">
  <div class="scm-shell">
    <?php
    // Row 1: top-level categories. Row 2: topics (child categories) of the current one.
    $items = [['label' => 'All', 'url' => $posts_page, 'active' => !$term]];
    foreach (scm_top_categories() as $c) $items[] = ['label' => $c->name, 'url' => get_term_link($c), 'active' => $top && $top->term_id === $c->term_id];
    scm_component('pill-nav', ['items' => $items]);
    if ($top) {
      $children = get_terms(['taxonomy' => 'category', 'parent' => $top->term_id, 'hide_empty' => true]);
      if ($children && !is_wp_error($children)) {
        $topics = [['label' => 'All ' . $top->name, 'url' => get_term_link($top), 'active' => $term->term_id === $top->term_id]];
        foreach ($children as $c) $topics[] = ['label' => $c->name, 'url' => get_term_link($c), 'active' => $term->term_id === $c->term_id];
        echo '<div class="scm-topic-pills">'; scm_component('pill-nav', ['items' => $topics]); echo '</div>';
      }
    }
    ?>
    <?php if (have_posts()): ?>
      <?php $first = !is_paged(); ?>
      <?php if ($first): the_post(); ?>
        <div class="scm-archive-lead"><?php scm_component('card-feature', ['words' => 40]); ?></div>
      <?php endif; ?>
      <?php if ($list_style): ?>
        <div class="scm-news-list scm-news-list--archive">
          <?php while (have_posts()): the_post(); scm_component('news-row', ['thumb' => true]); endwhile; ?>
        </div>
      <?php else: ?>
        <div class="scm-card-grid scm-card-grid--3">
          <?php while (have_posts()): the_post(); scm_component('card-post', ['editorial' => true]); endwhile; ?>
        </div>
      <?php endif; ?>
      <?php the_posts_pagination(['prev_text' => '← Newer', 'next_text' => 'Older →']); ?>
    <?php else: ?><p class="scm-empty">Nothing published here yet.</p><?php endif; ?>
  </div>
</section>
<?php get_footer(); ?>
