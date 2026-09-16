<?php
/**
 * Breadcrumb (spec B10). @dynamic
 * args: items — optional [[label, url_or_empty], ...]; defaults to
 * Home / current category / current title for a singular post.
 */
$a = wp_parse_args($args ?? [], ['items' => null]);
$items = $a['items'];
if ($items === null) {
  $items = [['Home', home_url('/')]];
  if (is_singular('post')) {
    $cats = get_the_category();
    if ($cats) $items[] = [$cats[0]->name, get_term_link($cats[0])];
    $items[] = [get_the_title(), ''];
  } elseif (is_singular()) {
    $items[] = [get_the_title(), ''];
  }
}
if (count($items) < 2) return;
?>
<nav class="scm-breadcrumb" aria-label="Breadcrumb">
  <div class="scm-shell">
    <ol>
      <?php foreach ($items as $i => [$label, $url]): $last = $i === count($items) - 1; ?>
        <li<?php if ($last) echo ' aria-current="page"'; ?>>
          <?php if ($url && !$last): ?><a href="<?php echo esc_url($url); ?>"><?php echo esc_html($label); ?></a>
          <?php else: ?><span><?php echo esc_html($label); ?></span><?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</nav>
