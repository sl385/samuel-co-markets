<?php
/**
 * Section heading row. @dynamic (args only)
 * args: eyebrow, title, link, link_label
 */
$a = wp_parse_args($args ?? [], ['eyebrow' => '', 'title' => '', 'link' => '', 'link_label' => '']);
?>
<div class="scm-section-head">
  <div>
    <?php if ($a['eyebrow']): ?><span class="scm-eyebrow"><?php echo esc_html($a['eyebrow']); ?></span><?php endif; ?>
    <?php if ($a['title']): ?><h2><?php echo wp_kses_post($a['title']); ?></h2><?php endif; ?>
  </div>
  <?php if ($a['link']): ?><a class="scm-underlink" href="<?php echo esc_url($a['link']); ?>"><?php echo esc_html($a['link_label'] ?: 'View all'); ?> →</a><?php endif; ?>
</div>
