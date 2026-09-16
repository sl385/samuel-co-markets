<?php
get_header();
$is_woo_page = function_exists('is_account_page') && (is_account_page() || is_cart() || is_checkout());
?>
<?php while (have_posts()): the_post(); ?>
<article class="scm-page<?php echo $is_woo_page ? ' scm-page--wide' : ''; ?>">
  <header class="scm-page__header"><div class="scm-shell scm-article__narrow"><h1><?php the_title(); ?></h1></div></header>
  <?php if ($is_woo_page): ?>
    <div class="scm-shell scm-page__body"><div class="woo-customer-layout"><?php the_content(); ?></div></div>
  <?php else: ?>
    <div class="scm-shell scm-article__narrow scm-prose"><?php the_content(); ?></div>
  <?php endif; ?>
</article>
<?php endwhile; ?>
<?php get_footer(); ?>
