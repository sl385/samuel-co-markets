<?php
/**
 * Page masthead (spec B8). @dynamic
 *
 * Replaces the legacy per-template masthead markup duplicated across
 * templates/template-{about,home,service,funded-2025}.php (each hand-rolling
 * the same `.masthead.section--dark` block) and SCO_Helpers::createMiniHeader()
 * (archive-event.php) — one component instead of five, all reading the same
 * real ACF "header" field group those templates already share
 * (header_title, header_content, header_button_url/label, header_image, ...).
 * See docs/BUILD-NOTES.md.
 *
 * args:
 *   eyebrow, title, content (html, already wpautop'd if needed)
 *   primary/secondary — [label, url] button pairs (either may be omitted)
 *   image — url; when present, two-column layout (image right); when absent,
 *           a centered dark band
 *   dark — bool, default true (light variant for e.g. Contact)
 *   logos — [[link, logo_url], ...] press-logo row (Home only)
 *   subnav — [[label, url, is_active], ...] pill row below (Events archive)
 */
$a = wp_parse_args($args ?? [], [
  'eyebrow' => '', 'title' => '', 'content' => '', 'primary' => null, 'secondary' => null,
  'image' => '', 'dark' => true, 'logos' => [], 'subnav' => [],
]);
$has_image = (bool) $a['image'];
?>
<section class="scm-masthead<?php echo $a['dark'] ? ' scm-masthead--dark scm-on-dark' : ''; ?><?php echo $has_image ? ' scm-masthead--image' : ''; ?>">
  <div class="scm-shell scm-masthead__grid">
    <div class="scm-masthead__copy">
      <?php if ($a['eyebrow']): ?><span class="scm-eyebrow"><?php echo esc_html($a['eyebrow']); ?></span><?php endif; ?>
      <?php if ($a['title']): ?><h1><?php echo wp_kses_post($a['title']); ?></h1><?php endif; ?>
      <?php if ($a['content']): ?><div class="scm-masthead__lead"><?php echo wp_kses_post($a['content']); ?></div><?php endif; ?>
      <?php if ($a['primary'] || $a['secondary']): ?>
      <div class="scm-actions-row">
        <?php if ($a['primary']): [$label, $url] = $a['primary']; ?>
          <a class="scm-button scm-button--gold" href="<?php echo esc_url($url); ?>"><?php echo esc_html($label); ?></a>
        <?php endif; ?>
        <?php if ($a['secondary']): [$label, $url] = $a['secondary']; ?>
          <a class="scm-button<?php echo $a['dark'] ? ' scm-button--on-dark' : ''; ?>" href="<?php echo esc_url($url); ?>"><?php echo esc_html($label); ?></a>
        <?php endif; ?>
      </div>
      <?php endif; ?>
      <?php if ($a['logos']): ?>
      <div class="scm-masthead__logos">
        <span class="scm-meta">Samuel &amp; Co. in the news</span>
        <ul>
          <?php foreach ($a['logos'] as [$link, $logo]): if (!$logo) continue; ?>
            <li><a target="_blank" rel="noopener" href="<?php echo esc_url($link); ?>"><img src="<?php echo esc_url($logo); ?>" loading="lazy" alt=""></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>
    </div>
    <?php if ($has_image): ?>
      <div class="scm-masthead__image" style="background-image:url('<?php echo esc_url($a['image']); ?>')"></div>
    <?php endif; ?>
  </div>
  <?php if ($a['subnav']): ?>
  <nav class="scm-subnav"><div class="scm-shell">
    <?php scm_component('pill-nav', [
      'dark' => true,
      'items' => array_map(fn($s) => ['label' => $s[0], 'url' => $s[1], 'active' => $s[2] ?? false], $a['subnav']),
    ]); ?>
  </div></nav>
  <?php endif; ?>
</section>
