<?php
/**
 * Research cards + sticky membership CTA. @dynamic
 * Cards: category research (STATIC fallback cards if empty). CTA: member-cta.php.
 *
 * Not currently called from front-page.php (14 Sep 2026 homepage rebuild) —
 * its real research-category content is now covered by moving-markets.php's
 * pill-filtered grid, and member-cta.php became its own full-width "Trader
 * Hub" band lower on the homepage rather than a sticky sidebar here. Left
 * in place rather than deleted in case a dedicated /research/ page wants
 * this shape later. See docs/BUILD-NOTES.md.
 */
$q = scm_posts_by_category_slug('research', 3);
?>
<section class="scm-section">
  <div class="scm-shell scm-research-layout">
    <div>
      <?php scm_component('section-head', ['title' => 'Research <small class="scm-section-head__sub">Go beyond the headline.</small>', 'link' => scm_cat_link('research', home_url('/research/')), 'link_label' => 'View all research']); ?>
      <div class="scm-card-grid scm-card-grid--3">
        <?php if ($q->have_posts()): while ($q->have_posts()): $q->the_post(); scm_component('card-post'); endwhile; wp_reset_postdata(); ?>
        <?php else: /* STATIC fallback cards until the research category has posts */ ?>
          <?php foreach ([['carry-trade', 'Themes · 5 min read', 'How Carry Trades Work — and When They Break', 'A practical look at the mechanics and risks behind carry.'], ['oil-risk', 'Macro · 7 min read', 'What $100 Oil Means for Global Inflation', 'A deep dive into the inflationary impact of higher oil prices.'], ['bunds', 'Equities · 8 min read', 'The Investment Case for European Defence', 'Why European defence spending is accelerating and which stocks could benefit.']] as [$img, $meta, $title, $text]): ?>
            <article class="scm-card"><div class="scm-card__image"><img src="<?php echo esc_url(_i("editorial/$img.webp")); ?>" alt=""></div><div class="scm-card__body"><span class="scm-meta"><?php echo esc_html($meta); ?></span><h3><?php echo esc_html($title); ?></h3><p><?php echo esc_html($text); ?></p></div></article>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
    <?php scm_component('member-cta'); ?>
  </div>
</section>
