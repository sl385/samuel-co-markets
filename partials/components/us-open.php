<?php
/**
 * US Open aside (inside the Morning Brief grid). @dynamic
 * Latest post in category us-open. Shows a play affordance if the post has the
 * "video" post format.
 */
$q = scm_first_in_category('us-open');
?>
<aside class="scm-usopen">
  <span class="scm-eyebrow">US Open</span>
  <?php if ($q): $q->the_post(); ?>
    <h3><?php the_title(); ?></h3>
    <p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), 24)); ?></p>
    <a class="scm-button scm-button--gold" href="<?php the_permalink(); ?>">Read the latest update →</a>
    <a class="scm-usopen__archive" href="<?php echo esc_url(scm_cat_link('us-open')); ?>">View all US Open updates</a>
    <a class="scm-usopen__media" href="<?php the_permalink(); ?>">
      <?php if (has_post_thumbnail()) the_post_thumbnail('medium_large'); else echo '<img src="' . esc_url(_i('editorial/us-reopen.webp')) . '" alt="">'; ?>
      <?php if (has_post_format('video')): ?><span class="scm-usopen__play"><i class="fa-solid fa-play"></i></span><?php endif; ?>
    </a>
  <?php else: ?>
    <?php /* STATIC fallback when no US Open post exists yet */ ?>
    <h3>A calmer open, but all eyes on inflation</h3>
    <p>Equity markets steady as traders assess CPI data, corporate earnings and the latest Fed commentary.</p>
    <a class="scm-button scm-button--gold" href="#">Read the latest update →</a>
    <a class="scm-usopen__archive" href="#">View all US Open updates</a>
    <span class="scm-usopen__media"><img src="<?php echo esc_url(_i('editorial/us-reopen.webp')); ?>" alt=""><span class="scm-usopen__play"><i class="fa-solid fa-play"></i></span></span>
  <?php endif; wp_reset_postdata(); ?>
</aside>
