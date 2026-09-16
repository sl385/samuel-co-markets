<?php
/**
 * Template Name: Service
 *
 * @package WordPress
 * @subpackage Sam & Co
 */

$_content = get_field('header');
$woo_tax = get_field('woocoomerce_top_level_category');

$args = [
    'post_type' => 'product',
    'posts_per_page' => -1,
    //'orderby' => 'meta_value_num',
    //'meta_key' => '_price',
    'order' => 'desc'
];

if( isset($_GET['filter']) ) {

    $args['tax_query'] = [
        [
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => $_GET['filter'],
        ]
    ];

} else {

    $args['tax_query'] = [
        [
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => $woo_tax->slug,
        ]
    ];

}

$products = new WP_Query( $args );


?>
<?php get_header(); ?>

<?php woocommerce_output_all_notices(); ?>

    <section class="masthead section <?php if( $_content['dark_background'] ) : ?>section--dark<?php else : ?> section--nobase masthead--bg <?php endif; ?>">
        <div class="container">
            <article>
                <h1><span><?php echo $_content['header_sub_title']; ?></span> <?php echo $_content['header_title']; ?></h1>
                <?php echo wpautop( $_content['header_content'] ); ?>
                <?php if( $_content['header_button_url'] ) : ?>
                    <a href="<?php echo $_content['header_button_url']; ?>" class="button <?php if( !$_content['dark_background'] ) : ?>button-hover--alt<?php endif; ?>"><?php echo $_content['header_button_label']; ?></a>
                <?php endif; ?>
            </article>
            <figure>
                <img src="<?php echo $_content['header_image']; ?>" loading="lazy" alt="<?php echo $_content['header_title']; ?>" />
            </figure>
        </div>
        <?php if( !$_content['dark_background'] ) : ?>
        <div class="gfx gfx--grid gfx--grid-d"></div>
        <div class="gfx gfx--grid gfx--grid-d"></div>
        <?php endif; ?>
    </section>



<section class="section section--gfx" id="products">
    <div class="container">

            <?php $cats =  get_terms( 'product_cat', array( 'parent' => $woo_tax->term_id, 'orderby' => 'slug', 'hide_empty' => false ) );  ?>
            <a href="#" class="m-cat-toggle button"><span>Categories <i class="fas fa-angle-down"></i></span></a>
            <ul class="filter-list m-nav-toggle toggles-cards">
                <li class="term--all"><a data-filter-value="all" class="<?php if(!$_GET['filter']) : ?>active<?php endif ; ?> button button-hover--alt" href="<?php echo get_permalink( $post->ID ); ?>">All</a></li>
                <?php foreach( $cats as $cat ) :  ?>
               
                    <li class="term--<?php echo $cat->slug; ?>">
                        <a class="<?php if($cat->slug == @$_GET['filter']) : ?>active<?php endif ; ?> button button-hover--alt" href="<?php echo get_permalink( $post->ID ); ?>/?filter=<?php echo $cat->slug; ?>#products"
                            data-filter-value=".<?= $cat->slug; ?>"
                            ><?php echo $cat->name; ?></a>
                    </li>
           
                <?php endforeach ; ?>
            </ul>

            <!-- Products -->

            <ul class="grid grid--3 grid--products cards cards--pretty">

                <?php 
                    
                    if ( $products->have_posts() ) {
                        while ( $products->have_posts() ) : $products->the_post();
                            wc_get_template_part( 'content', 'product' );
                        endwhile;
                    } else {
                        echo __( 'No products found' );
                    }
                    wp_reset_postdata(); 
                ?>

           
                
            </ul>

            <!-- /Products -->

    </div>

    <div class="gfx gfx--grid gfx--grid-d" style="position:absolute;right: -20px; top: 10px;"></div>
    <div class="gfx gfx--grid gfx--grid-d" style="position:absolute;right: -20px; bottom: 100px;"></div>
    <div class="gfx gfx--grid gfx--grid-d" style="position:absolute;left: -20px; top: 50%;"></div>
</section>

<?php /*
<script>
    jQuery(document).ready(function($) {
        // -- Init Masonry
       
        var max = 0;
        $('.grid--item').each(function(index, el) {
            if( jQuery(this).height() > max ){
                max = jQuery(this).height();
            }
	    });

         $('.grid--item .card').css({
            'height': max, 
            'min-height': max, 
            'max-height': max
        });

         var $grid = jQuery('.grid').isotope({
            itemSelector: '.grid--item',
            masonry: {
                percentPosition: true,	
                columnWidth: '.grid--item',
            }
        });

        $('.toggles-cards li a').bind('click', function(e){

            $('.toggles-cards li a').removeClass('active');

            $(this).addClass('active');

            var selected_term = $(this).parent().attr('class');
            console.log( selected_term );
            $grid.isotope({ filter: "." + selected_term });

            return false;

        });



    });
</script>
<style type="text/css">

    .grid {
        display: block;
    }

   .grid--item {
        width: calc(33% - 30px);
        margin: 15px;
        padding-top: 4em;
    }

    .filter-list {
        margin-bottom: 0;
    }

    .cards {
        margin-top: 6em !important;
    }

    @media screen and (max-width: 1300px) {
        .grid .grid--item {
            width: calc(50% - 20px);
            margin: 10px;
        }
    }

    @media screen and (max-width: 800px) {
        .grid .grid--item {
            width: 100%;
            margin: 6em 0 0 0;
        }

        .grid .grid--item:first-child {
            margin-top: 3em;
        }
     
        .cards { 
            margin-top: 0em !important;
        }
    }

</style>
*/ ?>

<?php get_footer(); ?>