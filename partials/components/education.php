<?php
/**
 * Dark education band with Level 5 / Level 7. @dynamic
 * Products come from ACF options (post_object) or, failing that, Woo products
 * found by slug. If neither exists the STATIC fallback links are used.
 */
$title = scm_opt('scm_edu_title', 'Take Your Education Further');
$text  = scm_opt('scm_edu_text', 'Ofqual-regulated qualifications designed for serious traders.');
$levels = [
  ['opt' => 'scm_edu_level5', 'slug' => 'level-5-diploma-in-financial-trading', 'eyebrow' => 'Ofqual-regulated', 'name' => 'Level 5 Diploma in Financial Trading', 'cta' => 'Explore the Level 5', 'img' => 'london-session'],
  ['opt' => 'scm_edu_level7', 'slug' => 'formula-for-success', 'eyebrow' => 'Advanced education', 'name' => 'Level 7 Diploma in Applied Financial Trading', 'cta' => 'Explore the Level 7', 'img' => 'premarket-check'],
];
?>
<section class="scm-education"><div class="scm-shell scm-education__grid">
  <div class="scm-education__intro"><span class="scm-eyebrow">Education</span><h2><?php echo esc_html($title); ?></h2><p><?php echo esc_html($text); ?></p></div>
  <?php foreach ($levels as $l):
    $pid = (int) scm_opt($l['opt'], 0);
    if (!$pid) { $p = get_page_by_path($l['slug'], OBJECT, 'product'); $pid = $p ? $p->ID : 0; }
    $url  = $pid ? get_permalink($pid) : home_url('/product/' . $l['slug'] . '/'); // STATIC fallback URL
    $name = $pid ? get_the_title($pid) : $l['name'];
    $thumb = $pid && has_post_thumbnail($pid) ? get_the_post_thumbnail_url($pid, 'medium') : _i('editorial/' . $l['img'] . '.webp');
  ?>
    <a class="scm-qualification" href="<?php echo esc_url($url); ?>" style="background-image:url('<?php echo esc_url($thumb); ?>')">
      <span class="scm-meta"><?php echo esc_html($l['eyebrow']); ?></span>
      <h3><?php echo esc_html($name); ?></h3>
      <span><?php echo esc_html($l['cta']); ?> →</span>
    </a>
  <?php endforeach; ?>
</div></section>
