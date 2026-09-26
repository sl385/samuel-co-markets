<?php
/**
 * Homepage — newsroom layout (26 Sep 2026 design, reference/home_i_thin.png
 * and the supplied homepage design). Composed from partials/components/*.
 *
 * Sections, top to bottom:
 *   static-ticker (light)            — placeholder figures, needs a data feed
 *   hero grid: featured story + 3 overlay cards + Morning Brief panel
 *   Latest News list (news category) + Markets at a glance (static table)
 *   Analysis & Research cards (research category)
 *   Founder column band (posts by the founder)
 *   Featured products ("Take your knowledge further")
 *   Newsletter band + footer come from footer.php
 *
 * Stories: sticky posts lead; otherwise newest first. Each section excludes
 * posts already shown above it (scm_shown_ids).
 */
get_header();

$lead = new WP_Query(['posts_per_page' => 4, 'post_status' => 'publish']); // stickies first by default
$lead_ids = wp_list_pluck($lead->posts, 'ID');
scm_shown_ids($lead_ids);

scm_component('static-ticker', ['light' => true, 'note' => 'Delayed 15 min']);
?>

<section class="scm-section scm-section--flush">
  <div class="scm-shell scm-hero-grid">
    <?php if ($lead->have_posts()): $lead->the_post(); scm_component('card-feature'); ?>
      <div class="scm-hero-grid__stack">
        <?php while ($lead->have_posts()): $lead->the_post(); scm_component('card-overlay'); endwhile; wp_reset_postdata(); ?>
      </div>
    <?php endif; ?>
    <?php scm_component('brief-panel'); ?>
  </div>
</section>

<section class="scm-section">
  <div class="scm-shell scm-newsroom">
    <div class="scm-newsroom__news">
      <?php scm_component('section-head', ['title' => 'Latest News', 'link' => scm_cat_link('news', home_url('/news/')), 'link_label' => 'View all news']); ?>
      <?php $news = new WP_Query(['category_name' => 'news', 'posts_per_page' => 6, 'ignore_sticky_posts' => true, 'post__not_in' => scm_shown_ids()]);
      if ($news->have_posts()): ?>
        <div class="scm-news-list">
          <?php while ($news->have_posts()): $news->the_post(); scm_component('news-row'); endwhile; ?>
        </div>
        <?php scm_shown_ids(wp_list_pluck($news->posts, 'ID')); wp_reset_postdata(); ?>
      <?php else: ?><p class="scm-empty">No news posts yet.</p><?php endif; ?>
    </div>
    <div class="scm-newsroom__glance">
      <?php scm_component('section-head', ['title' => 'Markets at a glance', 'link' => home_url('/markets/'), 'link_label' => 'View all markets']); ?>
      <?php scm_component('static-markets-glance'); ?>
    </div>
  </div>
</section>

<section class="scm-section">
  <div class="scm-shell">
    <?php scm_component('section-head', ['title' => 'Analysis &amp; Research', 'link' => scm_cat_link('research', home_url('/research/')), 'link_label' => 'View all analysis']); ?>
    <?php $research = new WP_Query(['category_name' => 'research', 'posts_per_page' => 3, 'ignore_sticky_posts' => true, 'post__not_in' => scm_shown_ids()]);
    if ($research->have_posts()): ?>
      <div class="scm-card-grid scm-card-grid--3">
        <?php while ($research->have_posts()): $research->the_post(); scm_component('card-post', ['editorial' => true, 'words' => 22]); endwhile; ?>
      </div>
      <?php scm_shown_ids(wp_list_pluck($research->posts, 'ID')); wp_reset_postdata(); ?>
    <?php else: ?><p class="scm-empty">No research posts yet.</p><?php endif; ?>
  </div>
</section>

<?php
scm_component('founder-band');
scm_component('featured-products');
get_footer();
