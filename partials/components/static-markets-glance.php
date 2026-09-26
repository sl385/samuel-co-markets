<?php
/**
 * "Markets at a glance" tabbed table with sparklines. @static
 * Placeholder figures, same gap as static-ticker.php: needs a licensed
 * market-data feed. When wired, keep the markup and feed $tabs from it:
 * tab => [ [instrument, price, 1d, 1w, 1m, [12 sparkline points]] ... ].
 */
$tabs = [
  'Indices' => [
    ['FTSE 100', '7,623.4', '+0.4%', '+1.2%', '+3.1%', [3,4,4,5,4,6,6,7,7,8,8,9]],
    ['S&P 500', '5,031.2', '-0.2%', '+0.8%', '+2.1%', [4,5,5,6,5,6,7,7,6,7,8,8]],
    ['Nasdaq', '17,420.6', '-0.3%', '+1.4%', '+2.8%', [5,5,6,6,7,7,8,7,8,9,9,8]],
    ['DAX', '18,261.4', '+0.3%', '+1.1%', '+2.9%', [4,4,5,5,6,6,6,7,7,8,8,9]],
    ['Nikkei 225', '38,487.2', '+0.6%', '+2.3%', '+5.1%', [3,3,4,5,5,6,7,7,8,9,9,10]],
  ],
  'Commodities' => [
    ['Gold', '2,348.1', '+0.6%', '+1.9%', '+4.2%', [4,4,5,5,6,6,7,7,7,8,9,9]],
    ['Brent Oil', '86.21', '+1.2%', '-0.6%', '+3.8%', [6,5,5,4,5,6,6,7,7,8,8,9]],
    ['Silver', '27.94', '+0.9%', '+2.4%', '+6.0%', [3,4,4,5,5,6,6,7,8,8,9,10]],
    ['Copper', '4.51', '-0.4%', '+1.1%', '+2.2%', [5,5,6,6,6,7,7,6,7,7,8,8]],
    ['Nat Gas', '1.87', '-1.8%', '-3.2%', '-6.5%', [9,9,8,8,7,7,6,6,5,5,4,4]],
  ],
  'Currencies' => [
    ['GBP/USD', '1.2734', '+0.1%', '+0.4%', '+1.2%', [5,5,5,6,6,6,6,7,7,7,7,8]],
    ['EUR/USD', '1.0851', '-0.1%', '+0.2%', '+0.6%', [5,5,6,5,5,6,6,6,6,7,6,7]],
    ['USD/JPY', '154.62', '+0.3%', '+0.9%', '+2.1%', [4,4,5,5,6,6,7,7,7,8,8,9]],
    ['GBP/EUR', '1.1735', '+0.2%', '+0.2%', '+0.5%', [5,5,5,6,6,6,6,6,7,7,7,7]],
    ['AUD/USD', '0.6612', '-0.3%', '-0.8%', '+0.4%', [6,6,5,5,5,6,6,5,5,6,6,6]],
  ],
  'Crypto' => [
    ['Bitcoin', '62,431', '+0.8%', '+3.6%', '-2.1%', [8,8,9,9,8,7,7,6,6,7,7,8]],
    ['Ethereum', '3,012', '+1.1%', '+2.9%', '-4.0%', [9,9,8,8,7,7,6,6,6,6,7,7]],
    ['Solana', '142.8', '+2.4%', '+6.1%', '+8.3%', [3,4,4,5,5,6,7,7,8,8,9,10]],
  ],
  'Bonds' => [
    ['US 10Y', '4.28%', '-0.4%', '+0.6%', '+1.9%', [6,6,5,5,6,6,7,7,7,8,8,8]],
    ['UK 10Y', '4.05%', '-0.2%', '+0.3%', '+1.4%', [6,6,6,5,6,6,6,7,7,7,8,8]],
    ['DE 10Y', '2.41%', '-0.1%', '+0.2%', '+0.8%', [5,5,5,5,6,6,6,6,7,7,7,7]],
    ['US 2Y', '4.71%', '+0.1%', '+0.5%', '+1.2%', [5,5,6,6,6,7,7,7,7,8,8,8]],
  ],
];
$dir = fn($v) => strpos($v, '-') === 0 ? 'down' : 'up';
$spark = function (array $pts, $cls) {
  $w = 64; $h = 22; $n = count($pts); $min = min($pts); $max = max($pts) ?: 1; $range = max(1, $max - $min);
  $coords = [];
  foreach ($pts as $i => $p) $coords[] = round($i * ($w / ($n - 1)), 1) . ',' . round($h - (($p - $min) / $range) * ($h - 2) - 1, 1);
  return '<svg class="scm-spark scm-spark--' . $cls . '" viewBox="0 0 ' . $w . ' ' . $h . '" width="' . $w . '" height="' . $h . '" aria-hidden="true"><polyline points="' . implode(' ', $coords) . '"/></svg>';
};
$uid = 'glance-' . wp_unique_id();
?>
<div class="scm-glance" data-glance data-static>
  <ul class="scm-tabs scm-tabs--sm" role="tablist">
    <?php $i = 0; foreach (array_keys($tabs) as $name): $slug = sanitize_title($name); ?>
      <li><button type="button" role="tab" class="<?php echo $i === 0 ? 'is-active' : ''; ?>" data-panel="<?php echo esc_attr($uid . '-' . $slug); ?>" aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"><?php echo esc_html($name); ?></button></li>
    <?php $i++; endforeach; ?>
  </ul>
  <?php $i = 0; foreach ($tabs as $name => $rows): $slug = sanitize_title($name); ?>
  <div class="scm-glance__panel" id="<?php echo esc_attr($uid . '-' . $slug); ?>" role="tabpanel"<?php if ($i > 0) echo ' hidden'; ?>>
    <table class="scm-glance__table">
      <thead><tr><th>Instrument</th><th>Price</th><th>1D</th><th>1W</th><th>1M</th><th><span class="scm-visually-hidden">Trend</span></th></tr></thead>
      <tbody>
      <?php foreach ($rows as [$inst, $price, $d1, $w1, $m1, $pts]): ?>
        <tr>
          <th scope="row"><?php echo esc_html($inst); ?></th>
          <td><?php echo esc_html($price); ?></td>
          <td class="scm-glance__chg scm-glance__chg--<?php echo $dir($d1); ?>"><?php echo esc_html($d1); ?></td>
          <td class="scm-glance__chg scm-glance__chg--<?php echo $dir($w1); ?>"><?php echo esc_html($w1); ?></td>
          <td class="scm-glance__chg scm-glance__chg--<?php echo $dir($m1); ?>"><?php echo esc_html($m1); ?></td>
          <td><?php echo $spark($pts, $dir($m1)); ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php $i++; endforeach; ?>
  <p class="scm-glance__note">Illustrative figures — live data pending a licensed market feed.</p>
</div>
