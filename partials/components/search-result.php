<?php
/**
 * Search result row (search.php). @dynamic
 * Use inside a loop — real content, any searchable post type (article,
 * course, event, product, ...). Only shows a thumbnail when a real one
 * exists (scm_has_real_thumbnail) rather than substituting an unrelated
 * stock photo, since a wrong fallback would mislead here more than it
 * would on an editorial card.
 */
$type_obj = get_post_type_object(get_post_type());
$type_label = $type_obj ? $type_obj->labels->singular_name : '';
$has_thumb = scm_has_real_thumbnail();
?>
<article <?php post_class('scm-search-result'); ?>>
  <?php if ($has_thumb): ?>
    <a class="scm-search-result__image" href="<?php the_permalink(); ?>"><?php scm_the_thumbnail('thumbnail'); ?></a>
  <?php endif; ?>
  <div class="scm-search-result__body">
    <?php if ($type_label): ?><span class="scm-meta"><?php echo esc_html($type_label); ?></span><?php endif; ?>
    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), 26)); ?></p>
  </div>
</article>
