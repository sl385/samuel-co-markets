<?php
/**
 * Rules panel — compact definition-grid summary (spec: "supporting
 * components" batch, Funding rules panel). @dynamic
 * Deliberately reuses the same real per-row data as
 * funding-tier-card.php's fact list rather than inventing separate fields
 * (weekend holding / news trading etc. don't exist in the real data) — a
 * quick-reference view of one representative programme, sitting above the
 * full interactive matrix.
 * args: title, metrics — [[label, tooltip, value], ...]
 */
$a = wp_parse_args($args ?? [], ['title' => '', 'metrics' => []]);
?>
<div class="scm-rules-panel">
  <?php if ($a['title']): ?><h3 class="scm-rules-panel__title"><?php echo esc_html($a['title']); ?></h3><?php endif; ?>
  <div class="scm-rules-panel__grid">
    <?php foreach ($a['metrics'] as [$label, $tooltip, $value]): ?>
      <div class="scm-rules-panel__rule">
        <span>
          <?php echo esc_html($label); ?>
          <?php if ($tooltip): ?><span class="tooltip" data-tooltip="<?php echo esc_attr($tooltip); ?>"><i class="fa-solid fa-circle-info" aria-hidden="true"></i></span><?php endif; ?>
        </span>
        <strong><?php echo esc_html($value); ?></strong>
      </div>
    <?php endforeach; ?>
  </div>
</div>
