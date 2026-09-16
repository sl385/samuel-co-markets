<?php
/**
 * Pill navigation (spec A7). @dynamic
 *
 * The one filter/tab/pill-nav pattern for the whole site — was previously
 * three near-duplicate implementations (moving-markets.php's JS-filter tabs,
 * index.php's .scm-filter category list with its own square-cornered CSS,
 * the masthead's .scm-subnav underline links) all doing the same job with
 * different markup and different CSS. One component, one CSS block
 * (_tabs.scss) now — edit the look in one place. See docs/BUILD-NOTES.md.
 *
 * args:
 *   items — [{label, url, active} | {label, filter, active}, ...]
 *           'url' = real link (server-side navigation, e.g. category archives).
 *           'filter' = client-side JS filter value (assets/js/markets.js reads
 *           data-filter / data-filter-target); use one or the other per item.
 *   filter_target — CSS selector for data-filter-target (only when any item
 *                   uses 'filter')
 *   dark — bool, default false (on-dark variant for use inside a dark band,
 *          e.g. the masthead's sub-nav)
 */
$a = wp_parse_args($args ?? [], ['items' => [], 'filter_target' => '', 'dark' => false]);
if (!$a['items']) return;
?>
<ul class="scm-tabs<?php echo $a['dark'] ? ' scm-tabs--on-dark' : ''; ?>"<?php if ($a['filter_target']): ?> data-filter-target="<?php echo esc_attr($a['filter_target']); ?>"<?php endif; ?>>
  <?php foreach ($a['items'] as $item): $active = !empty($item['active']); ?>
    <li>
      <?php if (isset($item['filter'])): ?>
        <button type="button" class="<?php echo $active ? 'is-active' : ''; ?>" data-filter="<?php echo esc_attr($item['filter']); ?>"><?php echo esc_html($item['label']); ?></button>
      <?php else: ?>
        <a class="<?php echo $active ? 'is-active' : ''; ?>" href="<?php echo esc_url($item['url'] ?? '#'); ?>"><?php echo esc_html($item['label']); ?></a>
      <?php endif; ?>
    </li>
  <?php endforeach; ?>
</ul>
