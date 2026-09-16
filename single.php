<?php
/**
 * Single post — matches reference/single_article.png: full-bleed dark hero
 * (eyebrow, h1, lead, byline overlaid at the bottom), then a two-column body
 * (prose + share links / sidebar with search, related, mailing-list CTA,
 * categories). Every post gets the photo hero now — scm_the_thumbnail_url()
 * falls back to a pooled real photo when there's no real featured image file,
 * so this no longer needs a separate no-image layout (see theme-components.php).
 */
get_header();
?>
<?php while (have_posts()): the_post(); ?>
<article class="scm-article">

  <header class="scm-article__hero--photo" style="background-image:linear-gradient(180deg,rgba(6,26,37,.55),rgba(6,26,37,.88)),url('<?php echo esc_url(scm_the_thumbnail_url('full')); ?>')">
    <div class="scm-shell scm-on-dark">
      <span class="scm-eyebrow"><?php echo wp_kses_post(get_the_category_list(' · ')); ?></span>
      <h1><?php the_title(); ?></h1>
      <?php if ($excerpt = get_the_excerpt()): ?><p class="scm-article__lead"><?php echo esc_html($excerpt); ?></p><?php endif; ?>
      <?php scm_component('author-byline', ['on_dark' => true]); ?>
    </div>
  </header>

  <div class="scm-shell scm-article__layout">
    <div class="scm-article__share-col"><?php scm_component('share-links'); ?></div>
    <div class="scm-prose scm-article__body"><?php the_content(); ?></div>
    <?php scm_component('article-sidebar', ['post_id' => get_the_ID()]); ?>
  </div>
</article>
<?php scm_component('prev-next-post'); ?>
<?php endwhile; ?>
<?php get_footer(); ?>
