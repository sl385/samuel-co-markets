<?php
/**
 * Prev / next post links (spec C21). @dynamic
 * Known gap noted in CLAUDE.md: single.php had no prev/next nav. Uses core
 * adjacent-post lookup (same-category by default).
 */
$prev = get_previous_post();
$next = get_next_post();
if (!$prev && !$next) return;
?>
<nav class="scm-prevnext" aria-label="Post navigation">
  <div class="scm-shell scm-article__narrow scm-prevnext__row">
    <?php if ($prev): ?>
      <a class="scm-prevnext__link scm-prevnext__link--prev" href="<?php echo esc_url(get_permalink($prev)); ?>">
        <span class="scm-meta">← Previous</span>
        <span><?php echo esc_html(get_the_title($prev)); ?></span>
      </a>
    <?php else: ?><span></span><?php endif; ?>
    <?php if ($next): ?>
      <a class="scm-prevnext__link scm-prevnext__link--next" href="<?php echo esc_url(get_permalink($next)); ?>">
        <span class="scm-meta">Next →</span>
        <span><?php echo esc_html(get_the_title($next)); ?></span>
      </a>
    <?php endif; ?>
  </div>
</nav>
