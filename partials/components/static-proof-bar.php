<?php
/**
 * Four-up proof bar under the hero. @static
 * To make dynamic: an ACF repeater on the options page (icon, title, text).
 */
$items = [
  ['fa-regular fa-book-open', 'Independent Analysis', 'Clear thinking, not noise.'],
  ['fa-solid fa-chart-simple', 'Real-world Education', 'Built around markets.'],
  ['fa-solid fa-users', 'A Global Community', 'For serious traders.'],
  ['fa-regular fa-shield', 'Trusted Since 2012', 'More than a decade in markets.'],
];
?>
<section class="scm-proof">
  <div class="scm-shell scm-proof__grid">
    <?php foreach ($items as [$icon, $title, $text]): ?>
      <div><i class="scm-proof__icon <?php echo esc_attr($icon); ?>" aria-hidden="true"></i><div><strong><?php echo esc_html($title); ?></strong><span><?php echo esc_html($text); ?></span></div></div>
    <?php endforeach; ?>
  </div>
</section>
