<?php get_header(); ?>
<?php while (have_posts()): the_post(); ?>
<article class="scm-page">
  <header class="scm-page__header"><div class="scm-shell scm-article__narrow"><h1><?php the_title(); ?></h1></div></header>
  <div class="scm-shell scm-article__narrow scm-prose"><?php the_content(); ?></div>
</article>
<?php endwhile; ?>
<?php get_footer(); ?>
