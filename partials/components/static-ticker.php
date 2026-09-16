<?php
/**
 * Market ticker strip. @static
 * Figures are placeholders. Needs a licensed market-data feed; when wired, keep
 * the markup and swap $ticks for the feed (label, price, change, direction).
 */
$ticks = [
  ['FTSE 100', '7,623.4', '+0.8%', 'up'], ['S&P 500', '5,482.1', '+0.4%', 'up'], ['Nasdaq', '17,421.8', '+0.6%', 'up'],
  ['GBP/USD', '1.2721', '-0.2%', 'down'], ['EUR/USD', '1.0953', '+0.1%', 'up'], ['Gold', '2,489.6', '+0.7%', 'up'],
  ['Brent Oil', '78.21', '+1.3%', 'up'], ['Bitcoin', '64,320', '+0.9%', 'up'], ['US 10Y', '4.28', '-0.4%', 'down'],
];
?>
<section class="scm-ticker" aria-label="Market snapshot">
  <div class="scm-shell scm-ticker__grid">
    <?php foreach ($ticks as [$label, $price, $change, $dir]): ?>
      <div class="scm-tick scm-tick--<?php echo esc_attr($dir); ?>"><span><?php echo esc_html($label); ?></span><strong><?php echo esc_html($price); ?><span class="scm-tick__change"><?php echo esc_html($change); ?></span></strong></div>
    <?php endforeach; ?>
    <a class="scm-ticker__more" href="<?php echo esc_url(home_url('/markets/')); ?>">View full markets →</a>
  </div>
</section>
