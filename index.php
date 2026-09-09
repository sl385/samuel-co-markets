<?php get_header(); ?>
<section class="scm-section"><div class="scm-shell">
<?php if (have_posts()): while (have_posts()): the_post(); ?>
<article <?php post_class('scm-entry'); ?>><h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1><?php the_excerpt(); ?></article>
<?php endwhile; the_posts_pagination(); else: ?><p>No content found.</p><?php endif; ?>
</div></section>
<?php get_footer(); ?>
