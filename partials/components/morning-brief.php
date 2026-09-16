<?php
/**
 * Morning Brief lead + image + US Open aside. @dynamic
 * Lead: latest post in category morning-market-brief. Key points: ACF repeater
 * scm_key_points on that post (falls back to a STATIC list). US Open: see us-open.php.
 * args: morning (WP_Query|null)
 */
$q = $args['morning'] ?? scm_first_in_category('morning-market-brief');
$fallback_points = ['Oil remains the dominant inflation story', 'US futures steady ahead of CPI', 'Gold benefits from renewed risk demand', 'Central-bank expectations remain in focus'];
?>
<section class="scm-section">
  <div class="scm-shell scm-brief">
    <div class="scm-brief__lead">
      <span class="scm-eyebrow">The Morning Brief</span>
      <?php if ($q): $q->the_post(); $brief_id = get_the_ID(); ?>
        <h2><?php the_title(); ?></h2>
        <p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), 34)); ?></p>
        <div class="scm-actions-row">
          <a class="scm-button scm-button--gold" href="<?php the_permalink(); ?>">Read Today's Brief →</a>
          <a class="scm-underlink" href="<?php echo esc_url(scm_cat_link('morning-market-brief')); ?>">View Brief Archive</a>
        </div>
        <?php
        $points = [];
        if (function_exists('get_field') && ($rows = get_field('scm_key_points', $brief_id))) $points = array_filter(wp_list_pluck($rows, 'point'));
        if (!$points) $points = $fallback_points; // STATIC fallback
        ?>
      <?php else: ?>
        <?php /* STATIC fallback when no Morning Brief post exists yet */ $points = $fallback_points; $brief_id = 0; ?>
        <h2>Oil Near $100 as Markets Turn to CPI</h2>
        <p>Oil prices rise sharply as geopolitical tensions intensify, with traders now focused on this week's US inflation data and what it means for the Fed's next move.</p>
        <div class="scm-actions-row"><a class="scm-button scm-button--gold" href="#">Read Today's Brief →</a><a class="scm-underlink" href="#">View Brief Archive</a></div>
      <?php endif; ?>
      <span class="scm-brief-points__title">Key points this morning:</span>
      <ol class="scm-brief-points"><?php foreach ($points as $p): ?><li><?php echo esc_html($p); ?></li><?php endforeach; ?></ol>
    </div>
    <div class="scm-brief__image">
      <?php if ($brief_id && has_post_thumbnail($brief_id)) echo get_the_post_thumbnail($brief_id, 'large'); else echo '<img src="' . esc_url(_i('editorial/2026-09-09-morning-v2-notext.webp')) . '" alt="">'; ?>
      <div class="scm-image-caption">Higher Prices.<br>Bigger Questions.</div><?php /* STATIC caption — candidate for an ACF field on the brief post */ ?>
    </div>
    <?php wp_reset_postdata(); ?>
    <?php scm_component('us-open'); ?>
  </div>
</section>
