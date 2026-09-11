<?php get_header(); ?>
<?php while (have_posts()): the_post(); ?>
<article class="scm-article">
  <header class="scm-article__header"><div class="scm-shell scm-article__narrow"><span class="scm-eyebrow"><?php echo wp_kses_post(get_the_category_list(' · ')); ?></span><h1><?php the_title(); ?></h1><p class="scm-article__meta"><?php echo esc_html(get_the_date()); ?> · <?php echo esc_html(scm_read_time()); ?> min read · <?php the_author(); ?></p></div></header>
  <?php if (has_post_thumbnail()): ?><div class="scm-shell scm-article__hero"><?php the_post_thumbnail('full'); ?></div><?php endif; ?>
  <div class="scm-shell scm-article__narrow scm-prose"><?php the_content(); ?></div>
</article>
<?php endwhile; ?>
<?php get_footer(); ?>
