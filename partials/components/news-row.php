<?php
/**
 * One row in a news list: time/date · headline · topic. @dynamic — in a loop.
 * args: thumb (bool, adds a small image + excerpt for archive pages)
 */
$a = wp_parse_args($args ?? [], ['thumb' => false, 'words' => 20]);
$topic = scm_topic();
$ts = get_post_time('U', true);
$recent = (time() - $ts) < DAY_IN_SECONDS;
?>
<article <?php post_class('scm-news-row' . ($a['thumb'] ? ' scm-news-row--thumb' : '')); ?>>
  <time class="scm-news-row__time" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html($recent ? get_the_time('H:i') : get_the_date('j M')); ?></time>
  <?php if ($a['thumb']): ?><a class="scm-news-row__thumb" href="<?php the_permalink(); ?>"><?php scm_the_thumbnail('thumbnail'); ?></a><?php endif; ?>
  <div class="scm-news-row__body">
    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <?php if ($a['thumb']): ?><p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), (int) $a['words'])); ?></p><?php endif; ?>
  </div>
  <?php if ($topic): ?><a class="scm-news-row__topic" href="<?php echo esc_url(get_term_link($topic)); ?>"><?php echo esc_html($topic->name); ?></a><?php endif; ?>
</article>
