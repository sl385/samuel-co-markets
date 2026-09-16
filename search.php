<?php
/**
 * Search results — WordPress's native search.php template hierarchy slot,
 * used automatically for any real `?s=` query (from the header search
 * icon's dedicated landing page, templates/template-search.php, or the
 * article-sidebar search box). Previously this fell back to index.php,
 * which is hardcoded for the News & Research archive (wrong title, wrong
 * category filter, and no indication the visitor had actually searched).
 *
 * @package WordPress
 * @subpackage Sam & Co
 */
get_header();
$query = get_search_query();
?>
<section class="scm-page__header"><div class="scm-shell">
  <span class="scm-eyebrow">Search</span>
  <h1><?php echo $query ? sprintf('Results for &ldquo;%s&rdquo;', esc_html($query)) : 'Search Samuel &amp; Co'; ?></h1>
</div></section>

<section class="scm-section"><div class="scm-shell scm-search-page">
  <?php scm_component('search-form'); ?>

  <?php if (have_posts()): ?>
    <p class="scm-search-count"><?php
      $total = (int) $GLOBALS['wp_query']->found_posts;
      printf('%d result%s', $total, $total === 1 ? '' : 's');
    ?></p>
    <div class="scm-search-results">
      <?php while (have_posts()): the_post(); scm_component('search-result'); endwhile; ?>
    </div>
    <?php the_posts_pagination(); ?>
  <?php else: ?>
    <p class="scm-search-empty">
      <?php echo $query ? sprintf('No results for &ldquo;%s&rdquo;. Try a different term, or ', esc_html($query)) : 'Try a search term above, or '; ?>
      <a href="<?php echo esc_url(home_url('/')); ?>">head back to the homepage</a>.
    </p>
  <?php endif; ?>
</div></section>

<?php get_footer(); ?>
