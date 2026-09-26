<?php
/**
 * Market ticker strip. @static
 * Figures are placeholders. Needs a licensed market-data feed; when wired, keep
 * the markup and swap $ticks for the feed (label, price, change, direction).
 * Scrolls continuously like a real ticker tape (CSS animation, pauses on
 * hover and respects prefers-reduced-motion) — the list is duplicated once
 * in the track so the loop is seamless.
 */
$a = wp_parse_args($args ?? [], ['light' => false, 'note' => 'Delayed 15 min']);
$ticks = [
  ['FTSE 100', '7,623.4', '+0.8%', 'up'], ['S&P 500', '5,482.1', '+0.4%', 'up'], ['Nasdaq', '17,421.8', '+0.6%', 'up'],
  ['GBP/USD', '1.2721', '-0.2%', 'down'], ['EUR/USD', '1.0953', '+0.1%', 'up'], ['Gold', '2,489.6', '+0.7%', 'up'],
  ['Brent Oil', '78.21', '+1.3%', 'up'], ['Bitcoin', '64,320', '+0.9%', 'up'], ['US 10Y', '4.28', '-0.4%', 'down'],
];
?>
<section class="scm-ticker<?php echo $a['light'] ? ' scm-ticker--light' : ''; ?>" aria-label="Market snapshot">
  <div class="scm-shell scm-ticker__bar">
    <div class="scm-ticker__viewport">
      <div class="scm-ticker__track">
        <?php for ($i = 0; $i < 2; $i++): foreach ($ticks as [$label, $price, $change, $dir]): ?>
          <div class="scm-tick scm-tick--<?php echo esc_attr($dir); ?>"<?php if ($i > 0) echo ' aria-hidden="true"'; ?>><span><?php echo esc_html($label); ?></span><strong><?php echo esc_html($price); ?><span class="scm-tick__change"><?php echo esc_html($change); ?></span></strong></div>
        <?php endforeach; endfor; ?>
      </div>
    </div>
    <?php if ($a['note']): ?><span class="scm-ticker__note"><?php echo esc_html($a['note']); ?></span><?php endif; ?>
    <a class="scm-ticker__more" href="<?php echo esc_url(home_url('/markets/')); ?>">View full markets →</a>
  </div>
</section>
