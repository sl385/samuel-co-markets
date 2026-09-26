<?php
/**
 * Small image card with text overlaid (the stacked column beside the
 * featured story). @dynamic — use inside a loop.
 */
$topic = scm_topic();
?>
<article <?php post_class('scm-overlay'); ?> style="background-image:url('<?php echo esc_url(scm_the_thumbnail_url('medium_large')); ?>')">
  <a class="scm-overlay__link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>"></a>
  <div class="scm-overlay__body">
    <?php if ($topic): ?><span class="scm-tag"><?php echo esc_html($topic->name); ?></span><?php endif; ?>
    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <span class="scm-overlay__meta"><?php echo esc_html(scm_time_ago()); ?> · <?php echo esc_html(scm_read_time()); ?> min read</span>
  </div>
</article>
