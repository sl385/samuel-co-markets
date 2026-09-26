<?php
/**
 * Post card (image, category · read time, title, excerpt). @dynamic
 * Use inside a loop. args: words (excerpt length, default 18), show_cat (bool)
 */
$a = wp_parse_args($args ?? [], ['words' => 18, 'show_cat' => true, 'editorial' => false]);
$cats = get_the_category();
$cat_slugs = implode(' ', wp_list_pluck($cats, 'slug'));
$cat_name = $cats ? $cats[0]->name : '';
$topic = scm_topic();
?>
<article <?php post_class('scm-card' . ($a['editorial'] ? ' scm-card--editorial' : '')); ?> data-cats="<?php echo esc_attr($cat_slugs); ?>">
  <a class="scm-card__image" href="<?php the_permalink(); ?>"><?php scm_the_thumbnail('medium_large'); ?></a>
  <div class="scm-card__body">
    <?php if ($a['editorial']): ?>
      <?php if ($topic): ?><span class="scm-eyebrow scm-eyebrow--tight"><?php echo esc_html($topic->name); ?></span><?php endif; ?>
      <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
      <p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), (int) $a['words'])); ?></p>
      <span class="scm-meta scm-meta--byline"><?php echo esc_html(scm_time_ago()); ?> · <?php echo esc_html(scm_read_time()); ?> min read · By <?php the_author(); ?></span>
    <?php else: ?>
      <span class="scm-meta"><?php if ($a['show_cat'] && $cat_name) echo esc_html($cat_name) . ' · '; ?><?php echo esc_html(scm_read_time()); ?> min read</span>
      <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
      <p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), (int) $a['words'])); ?></p>
    <?php endif; ?>
  </div>
</article>
