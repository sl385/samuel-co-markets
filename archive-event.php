<?php 


get_header(); 

?>

<?php SCO_Helpers::createMiniHeader("Events"); ?>

<section class="section">
    <div class="container container--m">
            <?php if( have_posts() ) : ?>
            <ul class="grid grid--3 grid--news">
                <?php while (have_posts()) : the_post(); ?> 
                    <li><?php get_template_part('partials/loop', 'events'); ?></li>
                <?php endwhile; ?> 
            </ul>
            <?php else : ?>
                <div class="wp-content u-tac"><h2>There are no events scheduled at present. Please check back soon or sign up to our mailing list.</h2></div>
            <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>