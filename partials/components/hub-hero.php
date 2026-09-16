<?php
/**
 * Standard hub/content-page hero (spec C2, B8). @dynamic (args)
 *
 * Standardised on reference/masthead-variants.png variant 01 ("Hero — standard,
 * image right") per the decision to pick one hero pattern and use it everywhere
 * instead of a different bespoke hero per page — this is the variant every hub
 * page (and most other page mockups) already converge on anyway. The other five
 * variants in that file (full-width overlay, split image-left, no-image, compact,
 * minimal) are intentionally not built — dropped rather than chased in parallel.
 *
 * args:
 *   eyebrow, title, lead (required)
 *   image_class  — modifier for .scm-hub-hero__image (background photo via CSS)
 *   quote        — optional italic quote card over the image
 *   primary      — [label, url] gold button (omit for no primary button)
 *   secondary    — [label, url] secondary hairline button (omit for no secondary)
 */
$a = wp_parse_args($args ?? [], [
  'eyebrow' => '', 'title' => '', 'lead' => '', 'image_class' => '', 'quote' => '',
  'primary' => null, 'secondary' => null,
]);
?>
<section class="scm-hub-hero">
  <div class="scm-shell scm-hub-hero__grid">
    <div class="scm-hub-hero__copy">
      <span class="scm-eyebrow"><?php echo esc_html($a['eyebrow']); ?></span>
      <h1><?php echo wp_kses_post($a['title']); ?></h1>
      <p><?php echo esc_html($a['lead']); ?></p>
      <?php if ($a['primary'] || $a['secondary']): ?>
      <div class="scm-actions-row">
        <?php if ($a['primary']): [$label, $url] = $a['primary']; ?>
          <a class="scm-button scm-button--gold" href="<?php echo esc_url($url); ?>"><?php echo esc_html($label); ?> →</a>
        <?php endif; ?>
        <?php if ($a['secondary']): [$label, $url] = $a['secondary']; ?>
          <a class="scm-button" href="<?php echo esc_url($url); ?>"><?php echo esc_html($label); ?> →</a>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
    <div class="scm-hub-hero__image <?php echo esc_attr($a['image_class']); ?>">
      <?php if ($a['quote']): ?><div class="scm-hub-hero__quote"><?php echo esc_html($a['quote']); ?></div><?php endif; ?>
    </div>
  </div>
</section>
