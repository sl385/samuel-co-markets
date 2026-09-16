<?php
/**
 * Content strip (spec C27): image + heading + rich text + optional button.
 * @dynamic — real ACF "content strip" field groups (About's content_strip_3,
 * Get Funded's content_strip / content_strip_2 / content_strip_3) all share
 * this exact shape (main_title, sub_title, content, image, button, optional
 * PDF). One component instead of duplicating the markup at each call site.
 * args: eyebrow, title, content (html), image, seed (for the image fallback
 * pool), button [label, url], pdf_url, reverse (bool — image on the right)
 */
$a = wp_parse_args($args ?? [], [
  'eyebrow' => '', 'title' => '', 'content' => '', 'image' => 0, 'seed' => 0,
  'button' => null, 'pdf_url' => '', 'reverse' => false,
]);
?>
<div class="scm-shell scm-content-strip<?php echo $a['reverse'] ? ' scm-content-strip--reverse' : ''; ?>">
  <figure class="scm-content-strip__image">
    <img src="<?php echo esc_url(scm_acf_image_url($a['image'], 'large', $a['seed'])); ?>" alt="<?php echo esc_attr($a['title']); ?>">
  </figure>
  <article>
    <?php if ($a['eyebrow']): ?><span class="scm-eyebrow"><?php echo esc_html($a['eyebrow']); ?></span><?php endif; ?>
    <?php if ($a['title']): ?><h2><?php echo esc_html($a['title']); ?></h2><?php endif; ?>
    <?php echo wp_kses_post($a['content']); ?>
    <div class="scm-actions-row">
      <?php if ($a['button'] && !empty($a['button'][1])): [$label, $url] = $a['button']; ?>
        <a class="scm-button scm-button--gold" href="<?php echo esc_url($url); ?>"><?php echo esc_html($label); ?></a>
      <?php endif; ?>
      <?php if ($a['pdf_url']): ?>
        <a class="scm-button" href="<?php echo esc_url($a['pdf_url']); ?>" target="_blank" rel="noopener"><i class="fa-solid fa-file-pdf" aria-hidden="true"></i> Download PDF</a>
      <?php endif; ?>
    </div>
  </article>
</div>
