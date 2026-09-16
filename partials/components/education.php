<?php
/**
 * Dark education band with Level 5 / Level 7 / Trading Courses. @dynamic
 * Products come from ACF options (post_object) or, failing that, Woo products
 * found by slug. If neither exists the STATIC fallback links are used.
 * Level 7's fallback slug used to be "formula-for-success" — a stale slug
 * traced back to a dead link on the legacy homepage's own ACF field; it
 * never resolved to a real product, so an empty scm_edu_level7 option
 * silently fell through to the fully-hardcoded fallback instead of finding
 * the real Level 7 Diploma. Fixed to the real slug. See docs/BUILD-NOTES.md.
 */
$title = scm_opt('scm_edu_title', 'Take Your Education Further');
$text  = scm_opt('scm_edu_text', 'Ofqual-regulated qualifications designed for serious traders.');
$levels = [
  ['opt' => 'scm_edu_level5', 'slug' => 'level-5-diploma-in-financial-trading', 'eyebrow' => 'Ofqual-regulated', 'name' => 'Level 5 Diploma in Financial Trading', 'cta' => 'Explore the Level 5', 'img' => 'london-session'],
  ['opt' => 'scm_edu_level7', 'slug' => 'level-7-diploma-in-applied-financial-trading', 'eyebrow' => 'Advanced education', 'name' => 'Level 7 Diploma in Applied Financial Trading', 'cta' => 'Explore the Level 7', 'img' => 'premarket-check'],
];
?>
<section class="scm-education"><div class="scm-shell scm-education__grid">
  <div class="scm-education__intro"><span class="scm-eyebrow">Education</span><h2><?php echo esc_html($title); ?></h2><p><?php echo esc_html($text); ?></p></div>
  <?php foreach ($levels as $l):
    $pid = (int) scm_opt($l['opt'], 0);
    if (!$pid) { $p = get_page_by_path($l['slug'], OBJECT, 'product'); $pid = $p ? $p->ID : 0; }
    $url  = $pid ? get_permalink($pid) : home_url('/product/' . $l['slug'] . '/'); // STATIC fallback URL
    $name = $pid ? get_the_title($pid) : $l['name'];
    $thumb = $pid && scm_has_real_thumbnail($pid) ? get_the_post_thumbnail_url($pid, 'medium') : _i('editorial/' . $l['img'] . '.webp');
  ?>
    <a class="scm-qualification" href="<?php echo esc_url($url); ?>" style="background-image:url('<?php echo esc_url($thumb); ?>')">
      <span class="scm-meta"><?php echo esc_html($l['eyebrow']); ?></span>
      <h3><?php echo esc_html($name); ?></h3>
      <span><?php echo esc_html($l['cta']); ?> →</span>
    </a>
  <?php endforeach; ?>
  <?php
  // Third "practical learning" card — the homepage mock-up this was built
  // from bundled "courses and trading tools" into one card with no single
  // real destination; Trading Courses (the real page) was chosen as the
  // honest anchor, same call as the "Choose your route" section above it
  // on the homepage (partials/components/routes.php).
  ?>
  <a class="scm-qualification" href="<?php echo esc_url(home_url('/trading-courses/')); ?>" style="background-image:url('<?php echo esc_url(_i('editorial/carry-trade.webp')); ?>')">
    <span class="scm-meta">Practical learning</span>
    <h3>Trading Courses</h3>
    <span>Browse all courses →</span>
  </a>
</div></section>
