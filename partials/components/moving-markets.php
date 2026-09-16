<?php
/**
 * "What's Moving Markets" — latest posts with category filter tabs. @dynamic
 * args: exclude (post IDs), count (default 4), tabs (category slugs to offer;
 * only ones that exist are shown). Filtering is client-side (markets.js).
 */
$a = wp_parse_args($args ?? [], ['exclude' => [], 'count' => 4, 'tabs' => ['macro', 'equities', 'fx', 'commodities', 'economy', 'companies']]);
$q = scm_latest_excluding($a['exclude'], $a['count']);
$tabs = array_filter(array_map(fn($s) => get_term_by('slug', $s, 'category'), $a['tabs']));
?>
<section class="scm-section">
  <div class="scm-shell">
    <?php scm_component('section-head', ['title' => "What's Moving Markets", 'link' => get_permalink(get_option('page_for_posts')) ?: home_url('/news/'), 'link_label' => 'View all news']); ?>
    <?php if ($tabs): ?>
    <ul class="scm-tabs" data-filter-target="#scm-markets-grid">
      <li><button type="button" class="is-active" data-filter="*">All</button></li>
      <?php foreach ($tabs as $t): ?><li><button type="button" data-filter="<?php echo esc_attr($t->slug); ?>"><?php echo esc_html($t->name); ?></button></li><?php endforeach; ?>
    </ul>
    <?php endif; ?>
    <div class="scm-card-grid scm-card-grid--4" id="scm-markets-grid">
      <?php while ($q->have_posts()): $q->the_post(); scm_component('card-post', ['words' => 20]); endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
