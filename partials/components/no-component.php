<?php
/**
 * "No component" placeholder. @static
 * Honest marker for a section that doesn't have a real .scm-* component yet,
 * instead of either leaving it raw/broken-looking or faking a design with
 * one-off CSS. Border + background + a label saying what's missing, so it's
 * obvious at a glance during review what still needs building — see
 * docs/BUILD-NOTES.md ("legacy CSS dropped, mark gaps instead of faking them").
 * args: label (what this section is), note (optional, more detail)
 */
$a = wp_parse_args($args ?? [], ['label' => '', 'note' => '']);
?>
<div class="scm-no-component">
  <span class="scm-no-component__tag">No component</span>
  <strong><?php echo esc_html($a['label']); ?></strong>
  <?php if ($a['note']): ?><span><?php echo esc_html($a['note']); ?></span><?php endif; ?>
</div>
