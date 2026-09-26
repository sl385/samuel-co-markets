<?php
/**
 * Featured story: full-bleed image, topic tag, big serif title, excerpt,
 * time · read time. @dynamic — use inside a loop.
 * args: words (excerpt length), tag (bool)
 */
$a = wp_parse_args($args ?? [], ['words' => 28, 'tag' => true]);
$topic = scm_topic();
?>
<article <?php post_class('scm-feature'); ?> style="background-image:url('<?php echo esc_url(scm_the_thumbnail_url('large')); ?>')">
  <a class="scm-feature__link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>"></a>
  <div class="scm-feature__body">
    <?php if ($a['tag'] && $topic): ?><a class="scm-tag" href="<?php echo esc_url(get_term_link($topic)); ?>"><?php echo esc_html($topic->name); ?></a><?php endif; ?>
    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), (int) $a['words'])); ?></p>
    <span class="scm-feature__meta"><?php echo esc_html(scm_time_ago()); ?> · <?php echo esc_html(scm_read_time()); ?> min read</span>
  </div>
</article>
