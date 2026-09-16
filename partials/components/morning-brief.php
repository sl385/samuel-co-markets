<?php
/**
 * Morning Brief section — lead story + 3 supporting cards. @dynamic
 * Lead: latest post in category morning-market-brief. Key points: ACF repeater
 * scm_key_points on that post (falls back to a STATIC list — no real posts
 * exist in this category yet, see docs/BUILD-NOTES.md 14 Sep "Morning Brief
 * homepage" entry). Supporting cards: US Open (see us-open.php, real) plus
 * two honest "coming soon" cards — a video briefing and an economic
 * calendar were both in the homepage mock-up this was built from, but
 * neither has any real data source (no video taxonomy, no calendar feed)
 * so they're marked as upcoming rather than filled with invented content.
 * args: morning (WP_Query|null)
 */
$q = $args['morning'] ?? scm_first_in_category('morning-market-brief');
$fallback_points = ['Oil remains the dominant inflation story', 'US futures steady ahead of CPI', 'Gold benefits from renewed risk demand', 'Central-bank expectations remain in focus'];
?>
<section class="scm-section" id="morning-brief">
  <div class="scm-shell">
    <?php scm_component('section-head', ['eyebrow' => "Today's Morning Brief", 'title' => 'The trading day, brought into focus.', 'link' => scm_cat_link('morning-market-brief'), 'link_label' => 'View all briefings']); ?>
    <div class="scm-brief">
      <div class="scm-brief__lead">
        <?php if ($q): $q->the_post(); $brief_id = get_the_ID(); ?>
          <h2><?php the_title(); ?></h2>
          <p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), 34)); ?></p>
          <?php
          $points = [];
          if (function_exists('get_field') && ($rows = get_field('scm_key_points', $brief_id))) $points = array_filter(wp_list_pluck($rows, 'point'));
          if (!$points) $points = $fallback_points; // STATIC fallback
          ?>
          <span class="scm-brief-points__title">Key points this morning:</span>
          <ol class="scm-brief-points"><?php foreach ($points as $p): ?><li><?php echo esc_html($p); ?></li><?php endforeach; ?></ol>
          <a class="scm-button scm-button--gold" href="<?php the_permalink(); ?>">Read Today's Brief →</a>
        <?php else: ?>
          <?php /* STATIC fallback when no Morning Brief post exists yet */ $brief_id = 0; ?>
          <h2>Oil Near $100 as Markets Turn to CPI</h2>
          <p>Oil prices rise sharply as geopolitical tensions intensify, with traders now focused on this week's US inflation data and what it means for the Fed's next move.</p>
          <span class="scm-brief-points__title">Key points this morning:</span>
          <ol class="scm-brief-points"><?php foreach ($fallback_points as $p): ?><li><?php echo esc_html($p); ?></li><?php endforeach; ?></ol>
          <a class="scm-button scm-button--gold" href="#">Read Today's Brief →</a>
        <?php endif; ?>
      </div>
      <div class="scm-brief__image">
        <?php if ($brief_id && scm_has_real_thumbnail($brief_id)) echo get_the_post_thumbnail($brief_id, 'large'); else echo '<img src="' . esc_url(_i('editorial/2026-09-09-morning-v2-notext.webp')) . '" alt="">'; ?>
      </div>
      <?php wp_reset_postdata(); ?>
    </div>

    <div class="scm-brief-supporting">
      <?php scm_component('us-open'); ?>
      <div class="scm-mini-story scm-mini-story--soon">
        <span class="scm-meta">Coming soon</span>
        <h3>Market Watch: today's themes in 5 minutes.</h3>
        <p>A short video briefing on what's moving markets — in development.</p>
      </div>
      <div class="scm-mini-story scm-mini-story--soon">
        <span class="scm-meta">Coming soon</span>
        <h3>This week's key economic events.</h3>
        <p>A calendar of the data releases and events worth watching — in development.</p>
      </div>
    </div>
  </div>
</section>
