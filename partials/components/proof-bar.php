<?php
/**
 * Four-up proof bar under the hero. @dynamic — ACF repeater on the options
 * page ("Homepage (2026)" > Proof bar tab), defaults matching the reference.
 */
$default = [
  ['icon' => 'fa-solid fa-book-open', 'title' => 'Independent Analysis', 'text' => 'Clear thinking, not noise.'],
  ['icon' => 'fa-solid fa-chart-simple', 'title' => 'Real-world Education', 'text' => 'Built around markets.'],
  ['icon' => 'fa-solid fa-users', 'title' => 'A Global Community', 'text' => 'For serious traders.'],
  ['icon' => 'fa-solid fa-shield', 'title' => 'Trusted Since 2012', 'text' => 'More than a decade in markets.'],
];
$items = scm_opt('scm_proof_items', $default);
?>
<section class="scm-proof">
  <div class="scm-shell scm-proof__grid">
    <?php foreach ($items as $item): ?>
      <div><i class="scm-proof__icon <?php echo esc_attr($item['icon']); ?>" aria-hidden="true"></i><div><strong><?php echo esc_html($item['title']); ?></strong><span><?php echo esc_html($item['text']); ?></span></div></div>
    <?php endforeach; ?>
  </div>
</section>
