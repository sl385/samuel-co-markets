<?php
/**
 * "What's Moving Markets" — latest posts with category filter tabs. @dynamic
 * args: exclude (post IDs), count (default 4), tabs (category slugs to offer;
 * only ones that exist are shown). Filtering is client-side (markets.js).
 * Default tabs are the real live-site categories (see `wp term list category`) —
 * News/Research/Blog — not the invented Markets-hub taxonomy (macro/equities/fx/...)
 * this originally shipped with, which matched nothing and silently rendered no pills.
 */
$a = wp_parse_args($args ?? [], ['exclude' => [], 'count' => 4, 'tabs' => ['news', 'research', 'blog']]);
$q = scm_latest_excluding($a['exclude'], $a['count']);
$tabs = array_filter(array_map(fn($s) => get_term_by('slug', $s, 'category'), $a['tabs']));
?>
<section class="scm-section">
  <div class="scm-shell">
    <?php scm_component('section-head', ['eyebrow' => 'Latest thinking', 'title' => 'News & Research', 'link' => get_permalink(get_option('page_for_posts')) ?: home_url('/news/'), 'link_label' => 'View all research']); ?>
    <?php if ($tabs): scm_component('pill-nav', [
      'filter_target' => '#scm-markets-grid',
      'items' => array_merge(
        [['label' => 'All', 'filter' => '*', 'active' => true]],
        array_map(fn($t) => ['label' => $t->name, 'filter' => $t->slug], $tabs)
      ),
    ]); endif; ?>
    <div class="scm-card-grid scm-card-grid--4" id="scm-markets-grid">
      <?php while ($q->have_posts()): $q->the_post(); scm_component('card-post', ['words' => 20]); endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
