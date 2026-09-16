<?php
/**
 * Template Name: Markets
 *
 * MOCKUP — per your steer: real post type/content source for this hub is
 * still TBC ("I am going to assume it will have its own post type or
 * articles but will confirm when I know more"), so this is deliberately
 * static/concept content to review, not wired to real ACF fields the way
 * every other page this project has built has been. The one part that IS
 * real: the "Market information" cards below pull genuine latest posts
 * (same real news/research/blog pool as the homepage's News & Research
 * section) rather than invented headlines, since real content already
 * exists and is a safer stand-in than fabricated ones — but whether this
 * hub ultimately reads from a dedicated CPT or this same article pool is
 * exactly the open question flagged in the review note below.
 *
 * The real "Market snapshot" figures are the same placeholder set as
 * static-ticker.php (@static there too, needs a licensed feed) — shown
 * here as cards instead of a scrolling ticker, per request.
 *
 * @package WordPress
 * @subpackage Sam & Co
 */
get_header();

$ticks = [
  ['FTSE 100', '7,623.4', '+0.8%', 'up'], ['S&P 500', '5,482.1', '+0.4%', 'up'], ['Nasdaq', '17,421.8', '+0.6%', 'up'], ['DAX', '18,921.5', '-0.3%', 'down'],
  ['GBP/USD', '1.2721', '-0.2%', 'down'], ['EUR/USD', '1.0953', '+0.1%', 'up'], ['Gold', '2,489.6', '+0.7%', 'up'], ['Brent Oil', '78.21', '+1.3%', 'up'],
];
?>

<?php scm_component('masthead', [
  'eyebrow' => 'Markets',
  'title' => 'Real-time insight. A clearer perspective.',
  'content' => 'Live market data, expert analysis and the context you need to make better decisions.',
  'primary' => ['Explore markets', '#snapshot'],
]); ?>

<section class="scm-section">
  <div class="scm-shell">
    <div class="scm-review-note">
      <span class="scm-review-note__tag">TBC — static content</span>
      <p>This page is a concept mock-up, not real data. The "Market snapshot" figures need a licensed market-data feed (same known gap as the homepage ticker). The cards below currently pull real recent articles from the existing news/research/blog pool, standing in until we've confirmed whether this hub gets its own dedicated post type or continues to reuse those categories — let us know which direction you'd like and we'll wire it up properly.</p>
    </div>
  </div>
</section>

<section class="scm-section" id="snapshot">
  <div class="scm-shell">
    <?php scm_component('section-head', ['eyebrow' => 'Market snapshot', 'title' => 'A quick read on today\'s markets']); ?>
    <div class="scm-market-grid">
      <?php foreach ($ticks as [$label, $price, $change, $dir]): ?>
        <div class="scm-market-card scm-market-card--<?php echo esc_attr($dir); ?>">
          <span class="scm-meta"><?php echo esc_html($label); ?></span>
          <strong><?php echo esc_html($price); ?></strong>
          <span class="scm-market-card__change"><i class="fa-solid fa-arrow-<?php echo $dir === 'up' ? 'up' : 'down'; ?>" aria-hidden="true"></i> <?php echo esc_html($change); ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="scm-section scm-section--soft">
  <div class="scm-shell">
    <?php scm_component('section-head', ['eyebrow' => 'Market information', 'title' => 'What\'s moving markets', 'link' => home_url('/news/'), 'link_label' => 'View all research']); ?>
    <div class="scm-card-grid scm-card-grid--4">
      <?php $q = scm_latest_excluding([], 8); while ($q->have_posts()): $q->the_post(); scm_component('card-post', ['words' => 16]); endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>

<?php // No separate signup band here — it would duplicate the real global newsletter section immediately below it (footer.php, every page), same call as the Contact page earlier. ?>

<?php get_footer(); ?>
