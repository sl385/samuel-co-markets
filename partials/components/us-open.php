<?php
/**
 * US Open mini-story (one of 3 cards in the Morning Brief supporting strip,
 * morning-brief.php). @dynamic
 * Latest post in category us-open. Shows a play affordance if the post has the
 * "video" post format.
 */
$q = scm_first_in_category('us-open');
if ($q) $q->the_post();
$url = $q ? get_permalink() : '#';
?>
<a class="scm-mini-story" href="<?php echo esc_url($url); ?>">
  <div class="scm-mini-story__copy">
    <span class="scm-meta">US Open</span>
    <?php if ($q): ?>
      <h3><?php the_title(); ?></h3>
    <?php else: ?>
      <?php /* STATIC fallback when no US Open post exists yet */ ?>
      <h3>A calmer open, but all eyes remain on inflation.</h3>
    <?php endif; ?>
    <span class="scm-underlink">Read update →</span>
  </div>
  <div class="scm-mini-story__art">
    <?php if ($q && scm_has_real_thumbnail()) the_post_thumbnail('thumbnail'); else echo '<img src="' . esc_url(_i('editorial/us-reopen.webp')) . '" alt="">'; ?>
    <?php if ($q && has_post_format('video')): ?><span class="scm-mini-story__play"><i class="fa-solid fa-play" aria-hidden="true"></i></span><?php endif; ?>
  </div>
</a>
<?php if ($q) wp_reset_postdata(); ?>
